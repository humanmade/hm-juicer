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

	/**
	 * Test the unset posts function.
	 *
	 * This function clears out the $juicer_posts global. We'll also use it in other tests, so we're testing it early.
	 */
	public function test_juicer_unset_posts() {
		$juicer_unset_posts = juicer_unset_posts();

		$this->assertTrue(
			empty( $juicer_unset_posts )
		);
	}

	/**
	 * Test the have_posts function in the Juicer Loop.
	 */
	public function test_have_posts() {
		// Right now, we should have juicer posts.
		$this->assertTrue(
			juicer_have_posts()
		);

		// Reset the Juicer Loop.
		juicer_unset_posts();

		// Now have_posts should return false.
		$this->assertNotTrue(
			juicer_have_posts()
		);
	}

	/**
	 * Test the the_post function in the Juicer Loop.
	 */
	public function test_the_post() {
		global $juicer_posts, $juicer_post;

		// Record how many posts were in the $juicer_posts global before running the_post.
		$before_post_count = count( $juicer_posts );

		juicer_the_post();

		// Record how many posts were in the $juicer_posts global after running the_post.
		$after_post_count = count( $juicer_posts );

		// Make sure that the $juicer_post exists and it's an object.
		$this->assertInternalType(
			'object',
			$juicer_post
		);

		// juicer_the_post unsets one of the posts from the $juicer_posts global, so make sure that the after count is one less than the before count.
		$this->assertEquals(
			$before_post_count - 1,
			$after_post_count
		);

		// We're not going to check every property of the juicer_post object, but we can at least make sure that the current post ID is what we think it should be.
		$this->assertEquals(
			378483666,
			$juicer_post->id
		);
	}
}
