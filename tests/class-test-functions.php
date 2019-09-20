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
		$content = "<p>Juicer Test is ahead of the curve in the post-acute setting in recognizing patients who are at risk for sepsis with Cerner's Sepsis Management solution. Learn how early intervention prevented our patients from becoming septic and/or transferring from our hospitals 77% of the time. <a href=\"https://test.site.dev/2zulhdo\" class=\"juicer-post__sharing-link\">Read More</a></p>";

		$this->assertEquals(
			$content,
			juicer_get_the_content()
		);
	}

	/**
	 * Test get_image_url function.
	 */
	public function test_get_image_url() {
		// This image has expired, so it will return an empty string.
		$this->assertEquals(
			'',
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
		/*
		 * For the purpose of the author image test, we need to use an
		 * actual query to Facebook's Graph API. We do a single hit for the
		 * author image based on the URL
		 * https://graph.facebook.com/20531316728/picture
		 * which points to Facebook's own Facebook account avatar.
		 *
		 * This will slow down unit tests but we only do a single API
		 * query. In production, once we have this URL, we cache it
		 * indefinitely, so we never need to make this request again (until
		 * the cache is cleared).
		 *
		 * We also need to strip off everything in the URL after the ?.
		 * This is because the query string in the Facebook image URL is
		 * time-sensitive and will change. The source image will not
		 * (but does not return a working image).
		 */
		$this->assertEquals(
			'https://scontent.xx.fbcdn.net/v/t1.0-1/p50x50/58818464_10158354585756729_7126855515920924672_n.png',
			str_replace( strpbrk( juicer_get_author_image(), '?' ), '', juicer_get_author_image() )
		);
	}

	/**
	 * Test the load more button and custom parameters.
	 */
	public function test_load_more_button() {
		ob_start();
		juicer_load_more_button();
		$button = ob_get_clean();

		// Test the load more button with default params.
		$this->assertEquals(
			'<div class="centered-load-more-wrapper "><button class="juicer-feed__load-more btn-load-more btn btn-large" aria-label="Load more">Load more</button><div class="juicer-feed__loading"></div></div>',
			preg_replace( '/\s+/', '', $button )
		);

		ob_start();
		juicer_load_more_button( [
			'aria_label'       => 'Aria Label',
			'button_text'      => 'Button Text',
			'button_class'     => 'button-class',
			'container_class'  => 'container',
		] );
		$button = ob_get_clean();

		// Test the load more button with custom parameters.
		$this->assertEquals(
			'<div class="centered-load-more-wrapper container"><button class="juicer-feed__load-more button-class" aria-label="Aria Label">Button Text</button><div class="juicer-feed__loading"></div></div>',
			preg_replace( '/\s+/', '', $button )
		);
	}
		);
	}
}
