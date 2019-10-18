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
		wp_cache_set( "response_per_$count-page_$page", $feed, 'juicer', 6 * HOUR_IN_SECONDS );
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
		$post->image_url           = validate_image( $item->id, $item->image );
		$post->additional_images   = $item->additional_photos;
		$post->source              = esc_html( $item->source->source );
		$post->source_url          = esc_url_raw( $item->full_url );
		$post->sharing_link        = ! empty( $item->external ) ? esc_url_raw( $item->external ) : '';
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
 * Get the read more link text, based on the post type and source.
 *
 * @param string $external_src_link   The URL of the original post source (photo, video, linked article).
 * @param string $social_post_src     The social network the post is pulled from.
 * @param string $social_profile_name The profile name of the social network account.
 *
 * @return string         The filtered Juicer post message.
 */
function get_read_more_text( $external_src_link, $social_post_src, $social_profile_name ) {
	// Get just the host portion of the URL.
	$external_src_href = wp_parse_url( $external_src_link )['host'];
	$external_src_href = str_replace( 'www.', '', $external_src_href );

	// If the JUICER_SHORT_URL constant is set in wp-config, get the value.
	if ( defined( 'JUICER_SHORT_URL' ) ) {
		$short_url = JUICER_SHORT_URL;

		// If the url doesn't have a scheme, add one for consistency.
		if ( strpos( $short_url, 'http' ) !== 0 ) {
			$short_url = 'https://' . $short_url;
		}

		// Get just the host portion of the URL.
		$short_url = wp_parse_url( $short_url )['host'];
		$short_url = str_replace( 'www.', '', $short_url );
	}

	// If the JUICER_LONG_URL constant is set in wp-config, get the value.
	if ( defined( 'JUICER_LONG_URL' ) ) {
		$long_url = JUICER_LONG_URL;

		// If the url doesn't have a scheme, add one for consistency.
		if ( strpos( $long_url, 'http' ) !== 0 ) {
			$long_url = 'https://' . $long_url;
		}

		// Get just the host portion of the URL.
		$long_url = wp_parse_url( $long_url )['host'];
		$long_url = str_replace( 'www.', '', $long_url );
	}

	// If the JUICER_SITE_NAME constant is set in wp-config, get the value.
	if ( defined( 'JUICER_SITE_NAME' ) ) {
		$site_name = JUICER_SITE_NAME;
	} else {
		$site_name = $social_profile_name;
	}

	/**
	 * If the original linked article is from one of the URLs provided in the constants,
	 * use the site name constant or social network profile name.
	 *
	 * If the url contains the social network name and the keyword video or photo,
	 * set the post type acordingly and use the name of the social network.
	 *
	 * Otherwise, use the host portion of the external_src_link url.
	 */
	if ( ( isset( $short_url ) && $short_url === $external_src_href )
		|| ( isset( $long_url ) && $long_url === $external_src_href ) ) {
		$post_type     = 'post';
		$read_more_src = $site_name;

	} elseif ( strpos( $external_src_link, strtolower( $social_post_src ) ) && strpos( $external_src_link, 'video' ) ) {
		$post_type     = 'video';
		$read_more_src = $social_post_src;

	} elseif ( strpos( $external_src_link, strtolower( $social_post_src ) ) && preg_match( '(image|photo)', $external_src_link ) ) {
		$post_type     = 'photo';
		$read_more_src = $social_post_src;

	} else {
		$post_type     = 'post';
		$read_more_src = $external_src_href;
	}

	// Set text based on post type.
	if ( 'photo' === $post_type ) {
		// translators: the item source (Facebook, Twitter, Original Website).
		$link_text = sprintf( __( 'View the photo on %s', 'hm-juicer' ), esc_html( $read_more_src ) );

	} elseif ( 'video' === $post_type ) {
		// translators: the item source (Facebook, Twitter, Original Website).
		$link_text = sprintf( __( 'Watch the video on %s', 'hm-juicer' ), esc_html( $read_more_src ) );

	} else {
		// translators: the item source (Facebook, Twitter, Original Website).
		$link_text = sprintf( __( 'Read original post on %s', 'hm-juicer' ), esc_html( $read_more_src ) );
	}

	return $link_text;
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
	$social_profile_name = ( $item->poster_display_name ) ? esc_html( $item->poster_display_name ) : esc_html( $item->poster_name );
	$social_post_src     = $item->source->source;
	$external_src_link   = $item->external;
	$link_text           = get_read_more_text( $external_src_link, $social_post_src, $social_profile_name );
	$link_url            = $external_src_link;

	// Get message from post.
	$content = wp_kses( make_clickable( $message ), allowed_html() );
	// Search content for links.
	preg_match( '/<a ?.*>(.*)<\/a>/', $content, $link_matches );

	/**
	 * If the last link in the content is the same as the external_src_link, replace it.
	 * Otherwise, add a read more link.
	 */
	if ( isset( $link_matches[1] ) && $external_src_link === $link_matches[1] ) {
		$content = str_replace( "<a href=\"$link_url\">$link_url</a>", "<a href=\"$link_url\" class=\"juicer-post__sharing-link\">$link_text</a>", $content );
	} else {
		$content = $content . "<a href=\"$link_url\" class=\"juicer-post__sharing-link\">$link_text</a>";
	}

	return $content;
}

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
 * Validate a remote Juicer item image.
 *
 * @param int   $item_id    The unique Juicer item ID.
 * @param mixed $source_url URL should be a string, but might be empty.
 *
 * @return string           The validated image URL or an empty string.
 */
function validate_image( int $item_id, $source_url ) : string {
	// Check for a cached version of the image. If we find one, return that early.
	$cached_image = wp_cache_get( "source_image_$item_id", 'juicer' );

	if ( $cached_image ) {
		return esc_url_raw( $cached_image );
	}

	if ( empty( $source_url ) ) {
		$source_url = '';
	}

	$remote_image = wp_remote_get( $source_url );

	if ( 200 !== wp_remote_retrieve_response_code( $remote_image ) ) {
		$source_url = '';
	}

	// Doublecheck Facebook CDN to make sure the image is actually a valid image.
	if ( false !== strpos( $source_url, 'fbcdn' ) ) {
		$headers = wp_remote_retrieve_headers( $remote_image );
		if ( isset( $headers['x-error'] ) ) {
			$source_url = '';
		}
	}

	if ( '' === $source_url ) {
		// Cache broken/invalid source image urls indefinitely.
		wp_cache_set( "source_image_$item_id", $source_url, 'juicer' );
		return $source_url;
	}

	// Cache valid source URLs for a week. This still opens up the possibility of broken images if we've cached a URL and the cache hasn't been updated yet.
	wp_cache_set( "source_image_$item_id", $source_url, 'juicer', WEEK_IN_SECONDS );

	return esc_url_raw( $source_url );
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
	$avatar_url = validate_image( $item->id, $item->poster_image );

	if ( '' === $avatar_url ) {
		// Force the default avatar if a valid avatar image was not found.
		$avatar_url = get_avatar_url( 0, [
			'default'       => 'mystery',
			'force_default' => true,
		] );
	} else {
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
				wp_cache_set( 'facebook_avatar_url', $avatar_url, 'juicer', WEEK_IN_SECONDS );
				break;

			default:
				break;
		}
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
