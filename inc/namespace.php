<?php
/**
 * HM Juicer
 *
 * Main plugin namespace for HM Juicer plugin.
 *
 * @package HM\Juicer
 */

namespace HM\Juicer;

use Altis;
use Asset_Loader;
use HM\Juicer\Settings;

const JUICER_ENDPOINT = 'https://www.juicer.io/api/feeds/';

/**
 * Kick everything off.
 */
function bootstrap() {
	if (
		// If none of the Juicer constants are defined...
		! defined( 'JUICER_ID' ) ||
		! defined( 'JUICER_SHORT_URL' ) ||
		! defined( 'JUICER_LONG_URL' ) ||
		! defined( 'JUICER_SITE_NAME' ) ||
		! has_altis_config()
	) {
		// ...load the settings page.
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

	// Enqueue custom assets for HM Juicer.
	$js_handle = 'hm-juicer-js';
	$js_dependencies = [ 'images-loaded' ];
	$css_handle = 'hm-juicer-css';

	if ( function_exists( 'Asset_Loader\\autoenqueue' ) ) {
		// Developent mode. Use Asset Loader to manage Webpack assets.

		// JS.
		Asset_Loader\autoenqueue( plugins_url( '/build', dirname( __FILE__ ) ), 'juicer.js', [
			'handle'  => $js_handle,
			'scripts' => $js_dependencies,
		] );

		// CSS.
		Asset_Loader\autoenqueue( plugins_url( '/build', dirname( __FILE__ ) ), 'styles.css', [
			'handle' => $css_handle,
		] );

	} else {
		// Production mode. Use standard WordPress enqueueing for built assets.

		// JS.
		wp_enqueue_script(
			$js_handle,
			plugins_url( '/build/juicer.js', dirname( __FILE__ ) ),
			$js_dependencies,
			'0.0.1', // TODO: use plugin version.
			true
		);

		// CSS.
		wp_enqueue_style(
			$css_handle,
			plugins_url( '/build/styles.css', dirname( __FILE__ ) ),
			[],
			'0.0.1' // TODO: use plugin version.
		);
	}

	// TODO: Add Font Awesome package to the plugin.
}

/**
 * Check for an Altis configuration option.
 *
 * @return bool True if an Altis config option exists. False if we're not on Altis or the config option doesn't exist for Juicer.
 */
function has_altis_config() : bool {
	if ( ! function_exists( 'Altis\\get_config' ) ) {
		return false;
	}

	if ( ! isset( Altis\get_config()['hm-juicer'] ) ) {
		return false;
	}

	return true;
}

/**
 * Get the Juicer feed name from the constant or CMB2, whichever is defined.
 *
 * If neither is defined, returns false.
 *
 * @return mixed Either the JUICER_ID from the constant defined in wp-config or options, or false if neither is set.
 */
function get_id() {
	// Check Altis config first.
	$juicer_id = has_altis_config() ? Altis\get_config()['hm-juicer']['juicer-id'] : false;
	if ( ! empty( $juicer_id ) ) {
		return $juicer_id;
	}

	// Check the JUICER_ID constant and return it if it exists.
	if ( defined( 'JUICER_ID' ) ) {
		return JUICER_ID;
	}

	// Return the option, if it exists.
	return Settings\juicer_get_option( 'juicer_id', false );
}

/**
 * Get the Juicer short url from the constant or CMB2, whichever is defined.
 *
 * If neither is defined, returns false.
 *
 * @return mixed Either the JUICER_SHORT_URL from the constant defined in wp-config or options, or false if neither is set.
 */
function get_short_url() {
	// Get Altis config first.
	$short_url = has_altis_config() ? Altis\get_config()['hm-juicer']['juicer-short-url'] : false;

	// If the Altis setting is not defined, check the JUICER_SHORT_URL constant and use that if it exists.
	if ( empty( $short_url ) ) {
		if ( defined( 'JUICER_SHORT_URL' ) ) {
			$short_url = JUICER_SHORT_URL;
		} else {
			// Return the option, if it exists.
			$short_url = Settings\juicer_get_option( 'JUICER_SHORT_URL', false );
		}
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
	$long_url = has_altis_config() ? Altis\get_config()['hm-juicer']['juicer-long-url'] : false;

	// If the Altis setting is not defined, check the JUICER_LONG_URL constant and use that if it exists.
	if ( empty( $long_url ) ) {
		if ( defined( 'JUICER_LONG_URL' ) ) {
			$long_url = JUICER_LONG_URL;
		} else {
			// Return the option, if it exists.
			$long_url = Settings\juicer_get_option( 'JUICER_LONG_URL', false );
		}
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
	// Check the Altis config first.
	$site_name = has_altis_config() ? Altis\get_config()['hm-juicer']['juicer-site-name'] : false;
	if ( ! empty( $site_name ) ) {
		return $site_name;
	}

	// Check the JUICER_SITE_NAME constant and return it if it exists.
	if ( defined( 'JUICER_SITE_NAME' ) ) {
		return JUICER_SITE_NAME;
	}

	// Return the option, if it exists.
	return Settings\juicer_get_option( 'JUICER_SITE_NAME', false );
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
