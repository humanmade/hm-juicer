<?php
/**
 * HM Juicer load more functions.
 *
 * @package HM\Juicer
 */

namespace HM\Juicer\LoadMore;

use Asset_Loader;

/**
 * Kick it off.
 */
function bootstrap() {
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );
	add_action( 'wp_ajax_juicer_load_more', __NAMESPACE__ . '\\ajax_handler' );
	add_action( 'wp_ajax_nopriv_juicer_load_more', __NAMESPACE__ . '\\ajax_handler' );
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
	$args       = isset( $_POST['args'] ) ? wp_unslash( $_POST['args'] ) : [];
	$page       = isset( $args['page'] ) ? absint( $args['page'] ) : 1;
	$post_count = isset( $args['post_count'] ) ? absint( $args['post_count'] ) : 10;

	// Get the template markup.
	ob_start();
	juicer_feed( $post_count, $page );
	$output = trim( ob_get_clean() );

	if ( '' === $output ) {
		return new \WP_Error( 'juicer_end_of_feed', __( 'There are no more posts to load.', 'hm-juicer' ) );
	} else {
		$output = str_replace( [ '<ul class="' . juicer_get_wrapper_classes() . '">', '</ul>' ], '', $output );
	}

	$response = new \stdClass();
	$response->body = $output;
	$response->page = $page;
	$response->post_count = $post_count;

	return $response;
}

/**
 * Enqueue styles and scripts.
 */
function enqueue_scripts() {
	$handle       = 'hm-juicer-load-more';
	$dependencies = [ 'jquery', 'underscore', 'hm-juicer' ];

	if ( function_exists( 'Asset_Loader\\autoenqueue' ) ) {
		/**
		 *  Developent mode. Use Asset Loader to manage Webpack assets.
		 */

		$manifest = dirname( __DIR__ ) . '/build/dev/asset-manifest.json';

		// JS.
		Asset_Loader\autoregister( $manifest, 'load_more', [
			'handle'  => $handle,
			'scripts' => $dependencies,
		] );

	} else {
		/**
		 * Production mode. Use standard WordPress enqueueing for built assets.
		 */

		// JS.
		wp_enqueue_script(
			$handle,
			plugins_url( '/build/prod/' . $handle . '.js', dirname( __FILE__ ) ),
			$dependencies,
			'0.1.0',
			true
		);
	}
}
