<?php
/**
 * Main HM Juicer and API test class
 *
 * Tests base and API functionality in namespace.php and api.php
 *
 * @package HM\Juicer
 */

namespace HM\Juicer;

/**
 * The main test class
 */
class Test_Api extends \WP_UnitTestCase {
	/**
	 * Path to Juicer API mock data.
	 *
	 * @var string Juicer API mock data path.
	 */
	protected static $mock_data = __DIR__ . '/data/juicer-mock.json';

	/**
	 * Test that the juicer_id function returns the correct ID.
	 *
	 * JUICER_ID is set in the testing environment's bootstrap.php.
	 */
	public function test_juicer_id() {
		$this->assertEquals(
			'testenv',
			juicer_id(),
			__( 'Juicer feed name (JUICER_ID) was not `testenv` as expected. Make sure your bootstrap.php in your testing environment is set correctly.', 'hm-juicer' )
		);
	}

	/**
	 * Test that the juicer endpoint is what we expect for the feed name set in bootstrap.php.
	 */
	public function test_juicer_api_url() {
		$this->assertEquals(
			'https://www.juicer.io/api/feeds/testenv',
			juicer_api_url()
		);
	}
}
