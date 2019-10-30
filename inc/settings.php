<?php
/**
 * HM Juicer Settings
 *
 * This should only load if the JUICER_ID is not defined in the wp-config.php file.
 *
 * @package HM\Juicer
 */

namespace HM\Juicer\Settings;

/**
 * Kick it off.
 */
function bootstrap() {
	// Load CMB2 if it's not loaded.
	if ( ! defined( 'CMB2_LOADED' ) ) {
		require_once dirname( __FILE__ ) . '/vendor/cmb2/cmb2/init.php';
	}

	// CMB2 exists, we can create the options page.
	add_action( 'cmb2_admin_init', __NAMESPACE__ . '\\options_page' );
}

/**
 * Register the CMB2 options page.
 */
function options_page() {
	$cmb = new_cmb2_box( [
		'id' => 'juicer_settings_menu',
		'title' => esc_html__( 'Juicer Settings', 'hm-juicer' ),
		'object_types' => [ 'options-page' ],
		'option_key' => 'juicer_options',
		'menu_title' => esc_html__( 'Juicer', 'hm-juicer' ),
		'parent_slug' => 'options-general.php',
		'save_button' => esc_html__( 'Save Juicer Settings', 'hm-juicer' ),
	] );

	$cmb->add_field( [
		'name' => esc_html__( 'Feed Name', 'hm-juicer' ),
		'desc' => esc_html__( 'This is the customized name/URL of your Juicer feed. It can be found on your main Juicer account page or in your account url, e.g. https://www.juicer.io/feeds/yourfeedname.', 'hm-juicer' ),
		'id'   => 'juicer_id',
		'type' => 'text',
	] );

	$cmb->add_field( [
		'name' => esc_html__( 'Short URL', 'hm-juicer' ),
		'desc' => esc_html__( 'This is the short URL that you use for sharing on social networks, which you can create from a variety of services, including https://bitly.com/. This helps to identify which URLs belong to you, and improves the accessibility and readability of read more links in cards.', 'hm-juicer' ),
		'id'   => 'juicer_short_url',
		'type' => 'text',
	] );

	$cmb->add_field( [
		'name' => esc_html__( 'Long URL', 'hm-juicer' ),
		'desc' => esc_html__( 'This is the main URL for your site, or any other additional URL that you use for sharing content on social networks and want to associate with your site name, in the field below.', 'hm-juicer' ),
		'id'   => 'juicer_long_url',
		'type' => 'text',
	] );

	$cmb->add_field( [
		'name' => esc_html__( 'Site Name', 'hm-juicer' ),
		'desc' => esc_html__( 'This is your site name and is used for read more links that link to one of the URLs identified above.', 'hm-juicer' ),
		'id'   => 'juicer_site_name',
		'type' => 'text',
	] );
}

/**
 * Get Juicer option.
 *
 * Wrapper for cmb2_get_option. Since this file is only loaded when CMB2 has already been loaded, we can make sure that we never run into errors with CMB2 functions not being defined.
 *
 * @param string $option_name The Juicer option to request.
 * @param bool   $default     A default value.
 *
 * @return mixed              The option value.
 */
function juicer_get_option( string $option_name, $default = false ) {
	return cmb2_get_option( 'juicer_options', $option_name, $default );
}
