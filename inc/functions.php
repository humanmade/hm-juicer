<?php
/**
 * HM Juiicer functions
 *
 * Public helper functions for the HM Juicer plugin.
 *
 * @package HM\Juicer
 */

use HM\Juicer;

/**
 * Get the Juicer feed name from the constant or CMB2, whichever is defined.
 *
 * If neither is defined, returns false.
 *
 * @return mixed Either the JUICER_ID from the constant defined in wp-config or options, or false if neither is set.
 */
function juicer_id() {
	return Juicer\get_id();
}

/**
 * Get the Juicer feed API endpoint URL.
 *
 * This expects that juicer_id returns a string. If juicer_id returns false, juicer_api_url will return false also.
 *
 * @see juicer_id()
 * @return mixed Either the full Juicer feed API url or false if juicer_id returns false.
 */
function juicer_api_url() {
	return Juicer\api_url();
}
