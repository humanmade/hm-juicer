<?php
/**
 * HM Juicer load more functions.
 *
 * @package HM\Juicer
 */

use HM\Juicer;

use HM\Asset_Loader;

	// Allow the paged argument to be overridden.
	if ( ! $page || absint( $page ) === 0 ) {
		// Bump the paged value by 1. Default to page 2.
		$page = get_query_var( 'page' ) ? get_query_var( 'page' ) + 1 : 2;
	}

/**
 * HM Juicer 'Load More' AJAX handler.
 */
function ajax_handler() {

	// Prepare response.
	$response = prepare_response();

	// Return response.
	if ( is_wp_error( $response ) ) {
		wp_send_json_error( $response );
	} else {
		wp_send_json_success( $response );
	}

    // Don't forget to stop execution afterward.
    wp_die();
}

/**
 * Prepare data for a response.
 *
 * @return array Response data to send back to the client.
 */
function prepare_response() {

	// This doesn't work.
	$output = juicer_feed();

	return [
		'body'          => $output,
	];
}
