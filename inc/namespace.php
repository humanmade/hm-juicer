<?php
/**
 * HM Juicer
 *
 * Main plugin namespace for HM Juicer plugin.
 *
 * @package HM/Juicer
 */

namespace HM\Juicer;

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
}
