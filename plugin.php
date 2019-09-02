<?php
/**
 * Plugin Name: HM Juicer
 * Description: Integrates with Juicer API for social feeds and allows accessible display of social media content.
 * Version: 0.1.0
 * Author: Human Made
 * Author URI: https://github.com/humanmade
 *
 * @package HM/Juicer
 */

namespace HM\Juicer;

use HM\Autoloader;

const JUICER_ENDPOINT = 'https://www.juicer.io/api/feeds/';

Autoloader\register_class_path( __NAMESPACE__, dirname( __FILE__ ) . '/inc/' );

require_once __DIR__ . '/inc/namespace.php';

// Kick it off.
bootstrap();
