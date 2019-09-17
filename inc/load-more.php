<?php
/**
 * HM Juicer load more functions.
 *
 * @package HM\Juicer
 */

use HM\Juicer;

/**
 * HM Juicer 'Load More' button.
 *
 * @param array $args  Array of arguments.
 */
function hm_juicer_load_more_button( array $args = [], $page = 0 ) {
	global $juicer_posts;

	// Allow the paged argument to be overridden.
	if ( ! $page || absint( $page ) === 0 ) {
		// Bump the paged value by 1. Default to page 2.
		$page = get_query_var( 'page' ) ? get_query_var( 'page' ) + 1 : 2;
	}

	$query_vars = [
		'per'  => absint( $args['post_count'] ),
		'page' => $page,
	];

	// Ensure anything not set falls back to defaults.
	foreach ( $query_vars as $key => $val ) {
		if ( empty( $val ) ) {
			unset( $query_vars[ $key ] );
		}
	}

	$default_args = [
		'aria_label'       => __( 'Load more', 'hm-juicer' ),
		'button_text'      => __( 'Load more', 'hm-juicer' ),
		'button_class'     => 'btn-load-more btn btn-large',
		'container_class'  => '',
		'list_class'       => '.juicer-feed',
		'paged_offset'     => 0,
		'template'         => [
			'name'            => '',
			'vars'            => [],
		],
	];

	$args = wp_parse_args( $args, array_merge( $query_vars, $default_args ) );

	// Enqueue 'load more' script.
	wp_enqueue_script( 'hm-juicer-load-more' );

	wp_localize_script(
		'hm-juicer-load-more', 'hmJuicerLoadMore', [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'args'    => $args,
		]
	);

	// Print the button.
	printf(
		/* translators: %1$2 becomes the container class. %2$s becomes the button class.  %3$s becomes the Aria label. %4$s becomes the button text. */
		'<div class="centered-load-more-wrapper %1$s">
			<button class="juicer-feed__load-more %2$s" aria-label="%3$s">%4$s</button>
		</div>',
		esc_attr( $args['container_class'] ),
		esc_attr( $args['button_class'] ),
		esc_attr( $args['aria_label'] ),
		esc_html( $args['button_text'] )
	);
}

/**
 * HM Juicer 'Load More' AJAX handler.
 */
function hm_juicer_load_more_handler() {

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
