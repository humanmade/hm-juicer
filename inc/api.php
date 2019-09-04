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
	$feed = wp_cache_get( "response_per_$count-page_$page", 'juicer' );

	// Check for a cached response.
	if ( ! $feed ) {
		// If no cached response, make a new API request.
		$url = juicer_api_url() . '?' . http_build_query( [
			'per'  => $count,
			'page' => $page,
		] );

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
		$post->post_date_humanized = human_time_diff( $post->post_date, current_time( 'U' ) );
		$post->post_content        = wp_kses_post( apply_filters( 'juicer_filter_post_content', $item->message ) );
		$post->image_url           = esc_url_raw( $item->image );
		$post->additional_images   = $item->additional_photos;
		$post->source              = esc_html( $item->source->source );
		$post->source_url          = esc_url_raw( $item->full_url );
		$post->sharing_link        = esc_url_raw( $item->external );
		$post->likes               = absint( $item->like_count );
		$post->comments            = absint( $item->comment_count );
		$post->author_name         = ( $item->poster_display_name ) ? esc_html( $item->poster_display_name ) : esc_html( $item->poster_name );
		$post->author_url          = esc_url_raw( $item->poster_url );
		$post->author_image        = esc_url_raw( $item->poster_image );

		$posts[ $i ] = $post;
	}

	return $posts;
}
