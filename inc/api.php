<?php
/**
 * Juicer API
 *
 * Handles all the functionality around communicating back and forth with the Juicer API.
 *
 * @package HM/Juicer
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
	$cached_response = wp_cache_get( "response_per_$count-page_$page", 'juicer' );

	// Check for a cached response.
	if ( $cached_response ) {
		$feed = json_decode( $cached_response );
	} else {
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

		wp_cache_set( "response_per_$count-page_$page", $response, 'juicer', DAY_IN_SECONDS );

		$feed = json_decode( wp_remote_retrieve_body( $response ) );
	}

	// Bail if there weren't any post items.
	if ( count( $feed->posts->items ) === 0 ) {
		return false;
	}

	return $feed->posts->items;
}
