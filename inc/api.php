<?php
/**
 * Juicer API
 *
 * Handles all the functionality around communicating back and forth with the Juicer API.
 *
 * @package HM\Juicer
 */

namespace HM\Juicer;

/**
 * Get Juicer feed posts.
 *
 * @param int $count The number of items to fetch. Defaults to 10.
 * @param int $page  The page to get items from. $count 10 and $page 2 would get the next 10 posts in the feed.
 *
 * @return mixed     WP_Error on API error, false if no feed items, an array of item objects if request was successful.
 */
function get_posts( $count = 10, $page = 1 ) {
	global $juicer_posts;

	$feed = wp_cache_get( "response_per_$count-page_$page", 'juicer' );

	// Check for a cached response.
	if ( ! $feed ) {
		// If no cached response, make a new API request.
		$url = add_query_arg( [
			'per'  => $count,
			'page' => $page,
		], api_url() );

		$response = wp_safe_remote_get( $url );

		// Bail with a WP_Error if there was an API response error.
		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return new \WP_Error( 'juicer_bad_response', wp_remote_retrieve_response_message( $response ), $response );
		}

		// Pull out the response body and cache it.
		$feed = wp_remote_retrieve_body( $response );
		wp_cache_set( "response_per_$count-page_$page", $feed, 'juicer', DAY_IN_SECONDS );
	}

	$feed = json_decode( $feed );

	// Bail if there weren't any post items.
	if ( count( $feed->posts->items ) === 0 ) {
		return false;
	}

	// Update the $juicer_posts global to the array of posts.
	$juicer_posts = prepare_post_items( $feed->posts->items );

	// Return the juicer posts.
	return $juicer_posts;
}

/**
 * Take an array of Juicer feed items and return just the data that we need.
 *
 * @param array $items The array of Juicer feed post objects.
 *
 * @return array       The modified Juicer feed array.
 */
function prepare_post_items( array $items ) : array {
	$posts = [];

	foreach ( $items as $i => $item ) {
		$post = new \stdClass;
		$post->id                  = absint( $item->id );
		$post->post_date           = strtotime( $item->external_created_at );
		$post->post_date_humanized = maybe_humanize_time( $post->post_date );
		$post->post_content        = apply_filters( 'juicer_filter_item_content', $item->message, $item );
		$post->image_url           = esc_url_raw( $item->image );
		$post->additional_images   = $item->additional_photos;
		$post->source              = esc_html( $item->source->source );
		$post->source_url          = esc_url_raw( $item->full_url );
		$post->sharing_link        = esc_url_raw( $item->external );
		$post->likes               = absint( $item->like_count );
		$post->comments            = absint( $item->comment_count );
		$post->author_name         = ( $item->poster_display_name ) ? esc_html( $item->poster_display_name ) : esc_html( $item->poster_name );
		$post->author_url          = esc_url_raw( $item->poster_url );
		$post->author_image        = get_author_image( $item );

		$posts[ $i ] = $post;
	}

	return $posts;
}

/**
 * Filter callback to modify the Juicer post text.
 *
 * @param string $message The original message from the Juicer post object.
 * @param object $item    The full Juicer post object.
 *
 * @return string         The filtered Juicer post message.
 */
function get_item_content( string $message, $item ) : string {
	$content = wp_kses( make_clickable( $message ), allowed_html() );
	preg_match( '/<a ?.*>(.*)<\/a>/', $content, $link_matches );

	if ( $item->external === $link_matches[1] ) {
		$link_text = __( 'Read More', 'hm-juicer' );
		$link_url  = $link_matches[1];
		$content   = str_replace( "<a href=\"$link_url\">$link_url</a>", "<a href=\"$link_url\" class=\"source-link\">$link_text</a>", $content );
	}

	return $content;
}

add_filter( 'juicer_filter_item_content', __NAMESPACE__ . '\\get_item_content', 10, 2 );

/**
 * Allowed HTML tags for wp_kses. This will strip targets and classes out of <a> tags.
 *
 * @return array Array of allowed tags.
 */
function allowed_html() : array {
	return [
		'a'      => [
			'href'  => [],
		],
		'br'     => [],
		'p'      => [],
		'em'     => [],
		'strong' => [],
	];
}

/**
 * Get the author image from the Juicer post item.
 *
 * Some sources need special handling to get the original author image (avatar). This function takes care of the special handling or returns the source image if it does not need special handling.
 *
 * Currently only supports Facebook.
 *
 * @param object $item The Juicer post item.
 *
 * @return string|WP_Error The author image (avatar) if one could be retrieved or a WP_Error if there was a problem.
 */
function get_author_image( $item ) {
	$source     = $item->source->source;
	$avatar_url = $item->poster_image;

	// Currently we've only tested Facebook.
	switch ( $source ) {
		case 'Facebook':
			// Try to get the avatar from the object cache.
			$cached_avatar_url = wp_cache_get( 'facebook_avatar_url', 'juicer' );

			if ( $cached_avatar_url ) {
				return esc_url_raw( $cached_avatar_url );
			}

			/*
			 * We don't have a cached avatar, so we need to query the Facebook Graph API.
			 * The URL will redirect to the image, but when queried,
			 * it returns an API endpoint. Hitting the endpoint directly
			 * will return the actual image in the response body, but
			 * all the info we need is actually in the response headers,
			 * so we need to dig into those to get the actual source URL.
			 */

			$header = wp_remote_head( $item->poster_image, [
				'type'     => 'small',
				'redirect' => false,
			] );

			if ( empty( $header ) ) {
				return new \WP_Error( 'juicer_bad_facebook_avatar_url', esc_html__( 'The poster image for the Facebook user we got from Juicer was bad.', 'hm-juicer' ), $item->poster_image );
			}

			$http_headers = wp_remote_retrieve_headers( $header );
			$avatar_url   = $http_headers['location'];

			// Cache the avatar and don't expire.
			wp_cache_set( 'facebook_avatar_url', $avatar_url, 'juicer' );
			break;

		default:
			break;
	}

	return esc_url_raw( $avatar_url );
}

/**
 * Humanize time if less than 35 days old. Otherwise, display a formatted date.
 *
 * @param int $timestamp The Unix timestamp to check.
 *
 * @return string   The humanized time or the date string.
 */
function maybe_humanize_time( int $timestamp ) : string {
	$today     = new \DateTime();
	$post_date = new \DateTime( "@$timestamp" );
	$interval  = date_diff( $post_date, $today )->format( '%a' );

	if ( $interval < 35 ) {
		// Translators: %s is a humanized time (e.g. 5 days).
		return sprintf( esc_html__( '%s ago', 'hm-juicer' ), human_time_diff( $timestamp, current_time( 'U' ) ) );
	}

	return date( 'M j, Y', $timestamp );
}
