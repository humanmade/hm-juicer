<?php
/**
 * HM Juicer
 *
 * Main plugin namespace for HM Juicer plugin.
 *
 * @package HM\Juicer
 */

namespace HM\Juicer;

use HM\Asset_Loader;
use HM\Juicer\Settings;

/**
 * Kick everything off.
 */
function bootstrap() {
	// If the JUICER ID wasn't defined, load a settings page to set it there.
	if ( ! defined( 'JUICER_ID' ) ) {
		require_once __DIR__ . '/settings.php';
		Settings\bootstrap();
	}

	// TODO: Conditionally enqueue scripts and styles only if the plugin is being used.
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\juicer_enqueue_scripts' );
}

/**
 * Get the Juicer feed name from the constant or CMB2, whichever is defined.
 *
 * If neither is defined, returns false.
 *
 * @return mixed Either the JUICER_ID from the constant defined in wp-config or options, or false if neither is set.
 */
function get_id() {
	// Check the JUICER_ID constant and return it if it exists.
	if ( defined( 'JUICER_ID' ) ) {
		return JUICER_ID;
	}

	// Return the option, if it exists.
	return cmb2_get_option( 'juicer_options', 'juicer_id', false );
}

/**
 * Get the Juicer feed API endpoint URL.
 *
 * This expects that juicer_id returns a string. If juicer_id returns false, api_url will return false also.
 *
 * @see get_id()
 * @return mixed Either the full Juicer feed API url or false if juicer_id returns false.
 */
function api_url() {
	// Bail if the ID isn't set. This is intended to be an authoritative URL, so it's no help if the feed name doesn't exist.
	if ( ! get_id() ) {
		return false;
	}

	return JUICER_ENDPOINT . get_id();
}

/**
 * Enqueue styles and scripts.
 */
function juicer_enqueue_scripts() {

	// Enqueue Images Loaded Script.
	wp_enqueue_script( 'images-loaded', '//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.1/imagesloaded.pkgd.min.js', [], null, true );

	// Enqueue custom JS for the HM Juicer layout.
	Asset_Loader\enqueue_script( [
		'name'      => 'hm-juicer-js',
		'handle'    => 'hm-juicer',
		'build_dir' => dirname( __DIR__ ) . '/build',
		'deps'      => [ 'images-loaded' ],
		'in_footer' => true,
	] );

	// Enqueue custom CSS for the HM Juicer layout.
	Asset_Loader\enqueue_style( [
		'name'      => 'hm-juicer-style',
		'handle'    => 'hm-juicer',
		'build_dir' => dirname( __DIR__ ) . '/build',
	] );
}
