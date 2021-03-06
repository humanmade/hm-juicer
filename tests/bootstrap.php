<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Hm_Juicer
 */

// Define a JUICER_ID for testing.
defined( 'JUICER_ID' ) or define( 'JUICER_ID', 'testenv' );
defined( 'JUICER_SHORT_URL' ) or define( 'JUICER_SHORT_URL', 'test.ly' );
defined( 'JUICER_LONG_URL' ) or define( 'JUICER_LONG_URL', 'test.site.dev' );
defined( 'JUICER_SITE_NAME' ) or define( 'JUICER_SITE_NAME', 'Juicer Test Site' );

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run .bin/install-wp-tests.sh ?" . PHP_EOL; // WPCS: XSS ok.
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the files being tested.
 */
function _manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/plugin.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
