<?php
/**
 * Plugin Name: HM Juicer
 * Description: Integrates with Juicer API for social feeds and allows accessible display of social media content.
 * Version: 0.1.0
 * Author: Human Made
 * Author URI: https://github.com/humanmade
 *
 * @package HM\Juicer
 */

namespace HM\Juicer;

use HM\Juicer\LoadMore;

const ROOT_DIR = __DIR__;

require_once ROOT_DIR . '/inc/namespace.php';
require_once ROOT_DIR . '/inc/api.php';
require_once ROOT_DIR . '/inc/functions.php';
require_once ROOT_DIR . '/inc/load-more.php';

// Require the Asset Loader package.
if ( file_exists( ROOT_DIR . '/vendor/humanmade/asset-loader/asset-loader.php' ) ) {
	require_once ROOT_DIR . '/vendor/humanmade/asset-loader/asset-loader.php';
} else {
	wp_die( __( 'Dependencies required for HM Juicer were not found. Did you run <code>npm run setup</code>?', 'hm-juicer' ), __( 'Plugin depenencies not found', 'hm-juicer' ) );
}

// Kick it off.
bootstrap();
LoadMore\bootstrap();
