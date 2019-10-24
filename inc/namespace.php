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

const JUICER_ENDPOINT = 'https://www.juicer.io/api/feeds/';

/**
 * Kick everything off.
 */
function bootstrap() {
	// If the JUICER ID wasn't defined, load a settings page to set it there.
	if ( ! defined( 'JUICER_ID' ) ) {
		require_once __DIR__ . '/settings.php';
		Settings\bootstrap();
	}

	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );
	add_filter( 'juicer_filter_item_content', __NAMESPACE__ . '\\get_item_content', 10, 2 );
}


/**
 * Enqueue styles and scripts.
 */
function enqueue_scripts() {
	// Enqueue Images Loaded Script.
	wp_register_script( 'images-loaded', '//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.1/imagesloaded.pkgd.min.js', [], null, true );

	// Enqueue custom JS for the HM Juicer layout.
	Asset_Loader\register_script( [
		'name'      => 'hm-juicer-js',
		'handle'    => 'hm-juicer-js',
		'build_dir' => dirname( __DIR__ ) . '/build',
		'deps'      => [ 'images-loaded' ],
		'in_footer' => true,
	] );

	// TODO: Add Font Awesome package to the plugin.
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
 * Get the Juicer short url from the constant or CMB2, whichever is defined.
 *
 * If neither is defined, returns false.
 *
 * @return mixed Either the JUICER_SHORT_URL from the constant defined in wp-config or options, or false if neither is set.
 */
function get_short_url() {
	$short_url = false;

	// Check the JUICER_SHORT_URL constant and return it if it exists.
	if ( defined( 'JUICER_SHORT_URL' ) ) {
		$short_url = JUICER_SHORT_URL;
	} else {
		// Return the option, if it exists.
		$short_url = cmb2_get_option( 'juicer_options', 'JUICER_SHORT_URL', false );
	}

	// If the url doesn't have a scheme, add one for consistency.
	if ( $short_url && strpos( $short_url, 'http' ) !== 0 ) {
		$short_url = 'https://' . $short_url;
	}

	return $short_url;
}

/**
 * Get the Juicer long url from the constant or CMB2, whichever is defined.
 *
 * If neither is defined, returns false.
 *
 * @return mixed Either the JUICER_LONG_URL from the constant defined in wp-config or options, or false if neither is set.
 */
function get_long_url() {
	$long_url = false;

	// Check the JUICER_LONG_URL constant and return it if it exists.
	if ( defined( 'JUICER_LONG_URL' ) ) {
		$long_url = JUICER_LONG_URL;
	} else {
		// Return the option, if it exists.
		$long_url = cmb2_get_option( 'juicer_options', 'JUICER_LONG_URL', false );
	}

	// If the url doesn't have a scheme, add one for consistency.
	if ( $long_url && strpos( $long_url, 'http' ) !== 0 ) {
		$long_url = 'https://' . $long_url;
	}

	return $long_url;
}

/**
 * Get the Juicer site name from the constant or CMB2, whichever is defined.
 *
 * If neither is defined, returns false.
 *
 * @return mixed Either the JUICER_SITE_NAME from the constant defined in wp-config or options, or false if neither is set.
 */
function get_site_name() {
	// Check the JUICER_SITE_NAME constant and return it if it exists.
	if ( defined( 'JUICER_SITE_NAME' ) ) {
		return JUICER_SITE_NAME;
	}

	// Return the option, if it exists.
	return cmb2_get_option( 'juicer_options', 'JUICER_SITE_NAME', false );
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
