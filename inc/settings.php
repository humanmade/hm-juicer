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
	// Die if CMB2 does not exist.
	if ( ! defined( 'CMB2_LOADED' ) ) {
		wp_die( __( 'No Juicer feed name set and CMB2 was not found. The HM Juicer plugin expects either a JUICER_ID constant to be set in your wp-config.php file or CMB2 to be available in your project to load a settings page.', 'hm-juicer' ), __( 'Plugin dependencies not found', 'hm-juicer' ) );
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
}
