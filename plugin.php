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

require_once __DIR__ . '/inc/namespace.php';
require_once __DIR__ . '/inc/api.php';
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/load-more.php';

// Require the Asset Loader package.
if ( file_exists( __DIR__ . '/vendor/humanmade/asset-loader/asset-loader.php' ) ) {
	require_once __DIR__ . '/vendor/humanmade/asset-loader/asset-loader.php';
} else {
	wp_die( __( 'Dependencies required for HM Juicer were not found. Did you run <code>npm run setup</code>?', 'hm-juicer' ), __( 'Plugin depenencies not found', 'hm-juicer' ) );
}

// Kick it off.
bootstrap();
LoadMore\bootstrap();
