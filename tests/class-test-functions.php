<?php
/**
 * HM Juicer helper functions test class
 *
 * @package HM\Juicer
 */

/**
 * The main test class
 */
class Test_Functions extends \WP_UnitTestCase {
	/**
	 * Load the helper functions in the constructor.
	 */
	public function __construct() {
		parent::__construct();

		require_once __DIR__ . '/helpers.php';
	}

}
