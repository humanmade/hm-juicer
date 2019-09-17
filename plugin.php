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

const JUICER_ENDPOINT = 'https://www.juicer.io/api/feeds/';

require_once __DIR__ . '/inc/namespace.php';
require_once __DIR__ . '/inc/api.php';
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/load-more.php';

// Kick it off.
bootstrap();
LoadMore\bootstrap();
