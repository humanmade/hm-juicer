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
	 * Cache the mock API data.
	 *
	 * We can create a mock API request by simply caching the data in the juicer-mock.json file since Juicer\get_posts checks the cache first.
	 */
	private function cache_mock_data() {
		$api_data = file_get_contents( self::$mock_data );
		wp_cache_set( 'response_per_10-page_1', $api_data, 'juicer', DAY_IN_SECONDS );
	}

	/**
	 * Test that the juicer_id function returns the correct ID.
	 *
	 * JUICER_ID is set in the testing environment's bootstrap.php.
	 */
	public function test_get_id() {
		$this->assertEquals(
			'testenv',
			get_id(),
			__( 'Juicer feed name (JUICER_ID) was not `testenv` as expected. Make sure your bootstrap.php in your testing environment is set correctly.', 'hm-juicer' )
		);
	}

	/**
	 * Test that the juicer endpoint is what we expect for the feed name set in bootstrap.php.
	 */
	public function test_api_url() {
		$this->assertEquals(
			'https://www.juicer.io/api/feeds/testenv',
			api_url()
		);
	}

	/**
	 * Test the API get posts function and make sure the data we get is what we expected.
	 */
	public function test_get_posts() {
		// Cache the mock data before trying to get posts.
		$this->cache_mock_data();

		// Get the posts from Juicer.
		$posts = get_posts();

		// Test that we got 10, it's the default and it's also the number of posts in the mock data.
		$this->assertEquals(
			10,
			count( $posts )
		);

		// Test that each post in the array is itself an object.
		foreach ( $posts as $post ) {
			$this->assertTrue(
				is_object( $post )
			);
		}

		// Test that the Author name on a Juicer post is what we expect.
		$this->assertEquals(
			'Juicer Test',
			$posts[0]->author_name
		);
	}
}
