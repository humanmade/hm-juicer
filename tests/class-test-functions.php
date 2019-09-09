<?php
/**
 * HM Juicer helper functions test class
 *
 * @package HM\Juicer
 */

namespace HM\Juicer;

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

	/**
	 * Before each test.
	 * We want to set up the $juicer_posts global before each test because we use it in all tests.
	 */
	public function setUp() {
		global $juicer_posts;

		parent::setUp();

		// Cache the mock data so we can use it.
		cache_mock_data();

		// Set up $juicer_posts global. Usually this happens in the juicer_feed function.
		$juicer_posts = get_posts();
	}

}
