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
class Test_API extends \WP_UnitTestCase {
	/**
	 * Load the helper functions in the constructor.
	 */
	public function __construct() {
		parent::__construct();

		require_once __DIR__ . '/helpers.php';
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
		\cache_mock_data();

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

	/**
	 * Test the prepare_post_items function.
	 */
	public function test_prepare_post_items() {
		$mock_data = '[{"id":378483666,"external_id":"1373959692752139","external_created_at":"2019-09-03T11:50:08.000-07:00","full_url":"https://www.facebook.com/99999999999999999/posts/1373959692752139","image":"https://external.xx.fbcdn.net/safe_image.php?d=AQC_cyqDeqv-mmmZ&w=720&h=720&url=https%3A%2F%2Fblog.testenv.com%2Ftachyon%2Fsites%2F4%2F2019%2F06%2FiStock-1003536156.jpg%3Ffit%3D1254%252C836&cfs=1&sx=0&sy=0&sw=836&sh=836&_nc_hash=AQAN9xUHntdGV7gd","external":"https://test.site.dev/2zulhdo","like_count":47,"comment_count":1,"tagged_users":null,"poster_url":"https://facebook.com/profile.php?id=99999999999999999","poster_id":"99999999999999999","location":null,"height":720,"width":720,"edit":null,"position":null,"deleted_at":null,"deleted_by":null,"additional_photos":[],"external_location_id":null,"message":"<p>Juicer Test is ahead of the curve in the post-acute setting in recognizing patients who are at risk for sepsis with Cerner\'s Sepsis Management solution. Learn how early intervention prevented our patients from becoming septic and/or transferring from our hospitals 77% of the time. <a target=\"_blank\" class=\"auto\" href=\"https://test.site.dev/2zulhdo\">https://test.site.dev/2zulhdo</a></p>","unformatted_message":"Juicer Test is ahead of the curve in the post-acute setting in recognizing patients who are at risk for sepsis with Cerner\'s Sepsis Management solution. Learn how early intervention prevented our patients from becoming septic and/or transferring from our hospitals 77% of the time. https://test.site.dev/2zulhdo","description":"35","feed":"testenv","likes":47,"comments":1,"poster_image":"https://graph.facebook.com/20531316728/picture","poster_name":"Juicer Test","poster_display_name":"HM Juicer","source":{"id":404340,"term":"testenv","term_type":"username","source":"Facebook","options":"","name":null,"allowed":null,"disallowed":null,"queue":false}}]';

		// Prepare the mock data.
		$prepared_posts = prepare_post_items( json_decode( $mock_data ) );

		// For the rest of this test, we're just going to work off the first post in the posts array.
		$post = $prepared_posts[0];

		/*
		 * Test all the single post objects against the mock data.
		 */

		$this->assertEquals(
			378483666,
			$post->id
		);

		$this->assertEquals(
			strtotime( '2019-09-03T11:50:08.000-07:00' ),
			$post->post_date
		);

		$this->assertEquals(
			sprintf( esc_html__( '%s ago', 'hm-juicer' ), human_time_diff( strtotime( '2019-09-03T11:50:08.000-07:00' ), current_time( 'U' ) ) ),
			$post->post_date_humanized
		);

		$this->assertEquals(
			"<p>Juicer Test is ahead of the curve in the post-acute setting in recognizing patients who are at risk for sepsis with Cerner's Sepsis Management solution. Learn how early intervention prevented our patients from becoming septic and/or transferring from our hospitals 77% of the time. <a href=\"https://test.site.dev/2zulhdo\" class=\"source-link\">Read More</a></p>",
			$post->post_content
		);

		$this->assertEquals(
			'https://external.xx.fbcdn.net/safe_image.php?d=AQC_cyqDeqv-mmmZ&w=720&h=720&url=https%3A%2F%2Fblog.testenv.com%2Ftachyon%2Fsites%2F4%2F2019%2F06%2FiStock-1003536156.jpg%3Ffit%3D1254%252C836&cfs=1&sx=0&sy=0&sw=836&sh=836&_nc_hash=AQAN9xUHntdGV7gd',
			$post->image_url
		);

		$this->assertEquals(
			[],
			$post->additional_images
		);

		$this->assertEquals(
			'Facebook',
			$post->source
		);

		$this->assertEquals(
			'https://www.facebook.com/99999999999999999/posts/1373959692752139',
			$post->source_url
		);

		$this->assertEquals(
			'https://test.site.dev/2zulhdo',
			$post->sharing_link
		);

		$this->assertEquals(
			47,
			$post->likes
		);

		$this->assertEquals(
			1,
			$post->comments
		);

		$this->assertEquals(
			'HM Juicer',
			$post->author_name
		);

		$this->assertEquals(
			'https://facebook.com/profile.php?id=99999999999999999',
			$post->author_url
		);

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
			str_replace( strpbrk( $post->author_image, '?' ), '', $post->author_image )
		);
	}

	/**
	 * Test the maybe_humanize_time function.
	 */
	public function test_maybe_humanize_time() {
		$now        = current_time( 'U' );
		$yesterday  = $now - DAY_IN_SECONDS;
		$two_weeks  = $now - ( 2 * WEEK_IN_SECONDS );
		$a_month    = $now - MONTH_IN_SECONDS;
		$two_months = $now - ( 2 * MONTH_IN_SECONDS );

		/*
		 * All of these tests will make sure the humanized time is used.
		 */
		$this->assertEquals(
			'1 min ago',
			maybe_humanize_time( $now )
		);

		$this->assertEquals(
			'1 day ago',
			maybe_humanize_time( $yesterday )
		);

		$this->assertEquals(
			'2 weeks ago',
			maybe_humanize_time( $two_weeks )
		);

		$this->assertEquals(
			'1 month ago',
			maybe_humanize_time( $a_month )
		);

		/*
		 * Test to make sure we flip to the full date string.
		 */
		$this->assertEquals(
			date( 'M j, Y', $two_months ),
			maybe_humanize_time( $two_months )
		);
	}

	/**
	 * Test the get_item_content function.
	 *
	 * We do a couple tests here to make sure we're only grabbing the last link in the message and changing the link text there to Read More with a custom class.
	 */
	public function test_get_item_content() {
		$post           = new \stdClass();
		$post->external = 'http://test.dev/external-link/';

		$content_simple = '<p>It\'s cold, damn cold. Ha, ha, ha, Einstein, you little devil. Einstein\'s clock is exactly one minute behind mine, it\'s still ticking. Like I always told you, if you put your mind to it you could accomplish anything. Yeah, but you\'re uh, you\'re so, you\'re so thin. <a target="_blank" class="auto" href="http://test.dev/external-link/">http://test.dev/external-link/</a></p>';

		$content_link_inside = '<p>Oh, I\'ve been so worried about you ever since you ran off the other night. Are you okay? I\'m sorry I have to go. Isn\'t he a dream boat? <a href="http://test.dev/link-inside/">Another link inside the content.</a> Uh, well, okay Biff, uh, I\'ll finish that on up tonight and I\'ll bring it over first thing tomorrow morning. Now, now, Biff, now, I never noticed any blindspot before when I would drive it. Hi, son. <a target="_blank" class="auto" href="http://test.dev/external-link/">http://test.dev/external-link/</a></p>';

		$content_link_raw = '<p>Back to the future. No no no this sucker\'s electrical, but I need a nuclear reaction to generate the one point twenty-one gigawatts of electricity- http://test.dev/link-raw/ But, what are you blind McFly, it\'s there. How else do you explain that wreck out there? <a target="_blank" class="auto" href="http://test.dev/external-link/">http://test.dev/external-link/</a></p>';

		// Test that the link text is changed to Read More and the source-link class is added.
		$this->assertEquals(
			'<p>It\'s cold, damn cold. Ha, ha, ha, Einstein, you little devil. Einstein\'s clock is exactly one minute behind mine, it\'s still ticking. Like I always told you, if you put your mind to it you could accomplish anything. Yeah, but you\'re uh, you\'re so, you\'re so thin. <a href="http://test.dev/external-link/" class="source-link">Read More</a></p>',
			get_item_content( $content_simple, $post )
		);

		// Test that the link inside the content is preserved and the final link text is replaced with Read More.
		$this->assertEquals(
			'<p>Oh, I\'ve been so worried about you ever since you ran off the other night. Are you okay? I\'m sorry I have to go. Isn\'t he a dream boat? <a href="http://test.dev/link-inside/">Another link inside the content.</a> Uh, well, okay Biff, uh, I\'ll finish that on up tonight and I\'ll bring it over first thing tomorrow morning. Now, now, Biff, now, I never noticed any blindspot before when I would drive it. Hi, son. <a href="http://test.dev/external-link/" class="source-link">Read More</a></p>',
			get_item_content( $content_link_inside, $post )
		);

		// Test that a raw URL inside the content is transformed into a link.
		$this->assertEquals(
			'<p>Back to the future. No no no this sucker\'s electrical, but I need a nuclear reaction to generate the one point twenty-one gigawatts of electricity- <a href="http://test.dev/link-raw/">http://test.dev/link-raw/</a> But, what are you blind McFly, it\'s there. How else do you explain that wreck out there? <a href="http://test.dev/external-link/" class="source-link">Read More</a></p>',
			get_item_content( $content_link_raw, $post )
		);
	}
}
