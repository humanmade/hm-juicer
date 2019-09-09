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

	/**
	 * Test the get_post function. This just returns the current post object.
	 */
	public function test_get_post() {
		global $juicer_post;

		$this->assertSame(
			$juicer_post,
			juicer_get_post()
		);
	}

	/**
	 * Test the get_date function. juicer_get_date can take a parameter for a date format string. By default it returns Unix time.
	 */
	public function test_get_date() {
		$this->assertEquals(
			strtotime( '2019-09-03T11:50:08.000-07:00' ),
			juicer_get_date()
		);

		$this->assertEquals(
			'2019-09-03 6:50pm',
			juicer_get_date( 'Y-m-d g:ia' )
		);
	}

	/**
	 * Test the humanized_time function. This uses human_time_diff, and we need that to get the string to test, so this is sort of derivative and not really testing a whole lot.
	 */
	public function test_humanized_time() {
		$humanized_time_diff = human_time_diff( strtotime( '2019-09-03T11:50:08.000-07:00' ), time() );

		$this->assertEquals(
			sprintf( '%s ago', $humanized_time_diff ),
			juicer_get_humanized_time()
		);
	}

	/**
	 * Test the get_the_content function.
	 */
	public function test_get_the_content() {
		$content = "<p>Juicer Test is ahead of the curve in the post-acute setting in recognizing patients who are at risk for sepsis with Cerner's Sepsis Management solution. Learn how early intervention prevented our patients from becoming septic and/or transferring from our hospitals 77% of the time. <a target=\"_blank\" class=\"auto\" href=\"https://test.site.dev/2zulhdo\">https://test.site.dev/2zulhdo</a></p>";

		$this->assertEquals(
			$content,
			juicer_get_the_content()
		);
	}

	/**
	 * Test get_image_url function.
	 */
	public function test_get_image_url() {
		$this->assertEquals(
			'https://external.xx.fbcdn.net/safe_image.php?d=AQC_cyqDeqv-mmmZ&w=720&h=720&url=https%3A%2F%2Fblog.testenv.com%2Ftachyon%2Fsites%2F4%2F2019%2F06%2FiStock-1003536156.jpg%3Ffit%3D1254%252C836&cfs=1&sx=0&sy=0&sw=836&sh=836&_nc_hash=AQAN9xUHntdGV7gd',
			juicer_get_image_url()
		);
	}

	/**
	 * Test the get_source function.
	 */
	public function test_get_source() {
		$this->assertEquals(
			'Facebook',
			juicer_get_source()
		);
	}

	/**
	 * Test the get_sharing_link function.
	 */
	public function test_get_sharing_link() {
		$this->assertEquals(
			'https://test.site.dev/2zulhdo',
			juicer_get_sharing_link()
		);
	}

	/**
	 * Test the get_like_count function.
	 */
	public function test_get_like_count() {
		$this->assertEquals(
			47,
			juicer_get_like_count()
		);
	}

	/**
	 * Test the get_comment_count function.
	 */
	public function test_get_comment_count() {
		$this->assertEquals(
			1,
			juicer_get_comment_count()
		);
	}

	/**
	 * Test the get_author_name function.
	 */
	public function test_get_author_name() {
		$this->assertEquals(
			'Juicer Test',
			juicer_get_author_name()
		);
	}

	/**
	 * Test the get_author_url function.
	 */
	public function test_get_author_url() {
		$this->assertEquals(
			'https://facebook.com/profile.php?id=99999999999999999',
			juicer_get_author_url()
		);
	}

	/**
	 * Test the get_author_image function.
	 */
	public function test_get_author_image() {
		$this->assertEquals(
			'https://graph.facebook.com/99999999999999999/picture',
			juicer_get_author_image()
		);
	}
}