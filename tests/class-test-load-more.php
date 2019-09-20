<?php
/**
 * HM Juicer helper Load More test class
 *
 * @package HM\Juicer
 */

namespace HM\Juicer\LoadMore;

/**
 * The main test class
 */
class Test_Load_More extends \WP_UnitTestCase {
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
	 * Test the prepare_response function.
	 */
	public function test_prepare_response() {
		$response = prepare_response();

		// Make sure the response is an object.
		$this->assertTrue(
			is_object( $response )
		);

		// Make sure the response has a body attribute.
		$this->assertObjectHasAttribute(
			'body',
			$response
		);

		// The response should be the actual feed items, so it should start with a <li> tag.
		$this->assertStringStartsWith(
			'<li class="juicer-post juicer-grid__item hide ">',
			$response->body
		);

		// The response also has a page attribute to tell what page we should be on.
		$this->assertObjectHasAttribute(
			'page',
			$response
		);

		// By default the page is 1.
		$this->assertEquals(
			1,
			$response->page
		);

		// The response also has a post count attribute, to determine how many posts to fetch.
		$this->assertObjectHasAttribute(
			'post_count',
			$response
		);

		// The post count defaults to 10.
		$this->assertEquals(
			10,
			$response->post_count
		);
	}
}
