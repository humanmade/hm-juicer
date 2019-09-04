<?php
/**
 * Main HM Juicer test class
 *
 * Tests base functionality in namespace.php
 *
 * @package HM\Juicer
 */

namespace HM\Juicer;

/**
 * The main test class
 */
class Test_Plugin extends \WP_UnitTestCase {
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
}
