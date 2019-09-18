<?php
/**
 * HM Juicer load more functions.
 *
 * @package HM\Juicer
 */

namespace HM\Juicer\LoadMore;

use HM\Asset_Loader;

/**
 * Kick it off.
 */
function bootstrap() {
	// TODO: Conditionally enqueue scripts and styles only if the plugin is being used.
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
	$args = wp_unslash( $_POST['args'] );
	$page = isset( $args['page'] ) ? absint( $args['page'] ) : 1;
	$post_count = isset( $args['post_count'] ) ? absint( $args['post_count'] ) : 10;

	// Get the template markup.
	ob_start();
	juicer_feed( $post_count, $page );
	$output = str_replace( [ '<ul class="' . juicer_get_wrapper_classes() . '">', '</ul>' ], '', ob_get_clean() );

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

	// Enqueue Images Loaded Script.
	wp_enqueue_script( 'images-loaded', '//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.1/imagesloaded.pkgd.min.js', [], null, true );

	// Enqueue custom JS for the HM Juicer layout.
	Asset_Loader\enqueue_script( [
		'name'      => 'hm-juicer-load-more',
		'handle'    => 'hm-juicer-load-more',
		'build_dir' => dirname( __DIR__ ) . '/build',
		'deps'      => [ 'jquery', 'underscore' ],
		'in_footer' => true,
	] );

	// Enqueue custom JS for the HM Juicer layout.
	Asset_Loader\enqueue_script( [
		'name'      => 'hm-juicer-js',
		'handle'    => 'hm-juicer-js',
		'build_dir' => dirname( __DIR__ ) . '/build',
		'deps'      => [ 'images-loaded' ],
		'in_footer' => true,
	] );

	// Enqueue custom CSS for the HM Juicer layout.
	Asset_Loader\enqueue_style( [
		'name'      => 'hm-juicer-style',
		'handle'    => 'hm-juicer-style',
		'build_dir' => dirname( __DIR__ ) . '/build',
	] );

	// TODO: Add Font Awesome package to the plugin.
}
