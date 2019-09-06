<?php
/**
 * HM Juiicer functions
 *
 * Public helper functions for the HM Juicer plugin.
 *
 * @package HM\Juicer
 */

use HM\Juicer;

/**
 * Get the Juicer feed name from the constant or CMB2, whichever is defined.
 *
 * If neither is defined, returns false.
 *
 * @return mixed Either the JUICER_ID from the constant defined in wp-config or options, or false if neither is set.
 */
function juicer_id() {
	return Juicer\get_id();
}

/**
 * Get the Juicer feed API endpoint URL.
 *
 * This expects that juicer_id returns a string. If juicer_id returns false, juicer_api_url will return false also.
 *
 * @see juicer_id()
 * @return mixed Either the full Juicer feed API url or false if juicer_id returns false.
 */
function juicer_api_url() {
	return Juicer\api_url();
}

/**
 * Display the main Juicer social media feed.
 *
 * @param int $count The number of posts to display.
 * @param int $page  The page to display.
 */
function juicer_feed( $count = 10, $page = 1 ) {
	global $juicer_posts;
	$juicer_posts = get_posts( $count, $page );

	ob_start();
	load_template( plugin_dir_path( dirname( __FILE__ ) ) . 'templates/part-juicer-feed.php' );
	echo ob_get_clean();
}

/**
 * Check whether the Juicer Loop has posts.
 *
 * @return bool Returns true if there are posts remaining, false if not.
 */
function juicer_have_posts() : bool {
	global $juicer_posts;

	if ( $juicer_posts && ! empty( $juicer_posts ) ) {
		return true;
	}

	return false;
}

/**
 * Populates the $juicer_post global and removes the current post from the $juicer_posts array.
 */
function juicer_the_post() {
	global $juicer_posts, $juicer_post;

	$juicer_post = $juicer_posts[0];

	unset( $juicer_posts[0] );

	$juicer_posts = array_values( $juicer_posts );
}

/**
 * Return the current Juicer post object.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return object The current Juicer post object.
 */
function juicer_get_post() : object {
	global $juicer_post;

	if ( is_null( $juicer_post ) ) {
		_doing_it_wrong( 'juicer_get_post()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return $juicer_post;
}

/**
 * Return the Juicer post date of the current Juicer post.
 *
 * Must be used inside the Juicer Loop
 *
 * @param string $date_format The PHP date format string. Defaults to Unix time.
 * @see date()
 *
 * @return string              The Juicer post date.
 */
function juicer_get_date( $date_format = 'U' ) : string {
	global $juicer_post;

	if ( is_null( $juicer_post ) ) {
		_doing_it_wrong( 'juicer_get_date()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return date( $juicer_post->post_date, $date_format );
}

/**
 * Display the Juicer post date of the current Juicer post.
 *
 * Must be used inside the Juicer Loop
 *
 * @param string $date_format The PHP date format string. Defaults to Unix time.
 * @see date()
 */
function juicer_the_date( $date_format = 'U' ) {
	echo juicer_get_date( $date_format );
}

/**
 * Return the humanized time (e.g. "1 day ago") of the current Juicer post.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return string The humanized time of the Juicer post.
 */
function juicer_get_humanized_time() : string {
	global $juicer_post;

	if ( is_null( $juicer_post ) ) {
		_doing_it_wrong( 'juicer_get_humanized_time()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return $juicer_post->post_date_humanized;
}

/**
 * Display the humanized time (e.g. "1 day ago") of the current Juicer post.
 *
 * Must be used inside the Juicer Loop.
 */
function juicer_the_humanized_time() {
	echo juicer_get_humanized_time();
}

/**
 * Return the Juicer post content of the current Juicer post.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return string The content of the Juicer post.
 */
function juicer_get_the_content() : string {
	global $juicer_post;

	if ( is_null( $juicer_post ) ) {
		_doing_it_wrong( 'juicer_get_the_content()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	/**
	 * Filter the Juicer post content.
	 *
	 * @var string The original Juicer post content.
	 */
	return apply_filters( 'juicer_filter_the_content', $juicer_post->post_content );
}

/**
 * Display the Juicer post content of the current Juicer post.
 *
 * Must be used inside the Juicer Loop.
 */
function juicer_the_content() {
	echo juicer_get_the_content();
}

/**
 * Return the URL to the Juicer post featured image.
 *
 * Must be used inside the Juicer Loop
 *
 * @return string The Juicer social image.
 */
function juicer_get_image_url() : string {
	global $juicer_post;

	if ( is_null( $juicer_post ) ) {
		_doing_it_wrong( 'juicer_get_image_url()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return $juicer_post->image_url;
}

/**
 * Display the URL to the Juicer post featured image.
 *
 * Must be used inside the Juicer Loop
 */
function juicer_the_image_url() {
	echo juicer_get_image_url();
}

/**
 * Return the social media source for the Juicer post.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return string The Juicer social media source (e.g. Facebook).
 */
function juicer_get_source() : string {
	global $juicer_post;

	if ( is_null( $juicer_post ) ) {
		_doing_it_wrong( 'juicer_get_source()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return $juicer_post->source;
}

/**
 * Display the social media source for the Juicer post.
 *
 * Must be used inside the Juicer Loop.
 */
function juicer_the_source() {
	echo juicer_get_source();
}

/**
 * Return the sharing link for the posted social media object.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return string The social media sharing link.
 */
function juicer_get_sharing_link() : string {
	global $juicer_post;

	if ( is_null( $juicer_post ) ) {
		_doing_it_wrong( 'juicer_get_sharing_link()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return $juicer_post->sharing_link;
}

/**
 * Display the sharing link for the posted social media object.
 *
 * Must be used inside the Juicer Loop.
 */
function juicer_the_sharing_link() {
	echo juicer_get_sharing_link();
}

/**
 * The like count for the current social media post.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return int The post like count.
 */
function juicer_get_like_count() : int {
	global $juicer_post;

	if ( is_null( $juicer_post ) ) {
		_doing_it_wrong( 'juicer_get_like_count()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return 0;
	}

	return $juicer_post->likes;
}

/**
 * Display the like count for the current social media post.
 *
 * Must be used inside the Juicer Loop.
 */
function juicer_the_like_count() {
	echo juicer_get_like_count();
}

/**
 * The comment count for the social media post.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return int The post comment count.
 */
function juicer_get_comment_count() : int {
	global $juicer_post;

	if ( is_null( $juicer_post ) ) {
		_doing_it_wrong( 'juicer_get_comment_count()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return 0;
	}

	return $juicer_post->comments;
}

/**
 * Display the comment count for the social media post.
 *
 * Must be used inside the Juicer Loop.
 */
function juicer_the_comment_count() {
	echo juicer_get_comment_count();
}

/**
 * The social media account display name or account name.  If display name exists, display name is used, otherwise the account name is used.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return string The social media author/account name.
 */
function juicer_get_author_name() : string {
	global $juicer_post;

	if ( is_null( $juicer_post ) ) {
		_doing_it_wrong( 'juicer_get_author_name()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return $juicer_post->author_name;
}

/**
 * Display the social media account display name or account name.  If display name exists, display name is used, otherwise the account name is used.
 *
 * Must be used inside the Juicer Loop.
 */
function juicer_the_author_name() {
	echo juicer_get_author_name();
}

/**
 * Return the link to the social media author profile.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return string The link to the social media author profile.
 */
function juicer_get_author_url() : string {
	global $juicer_post;

	if ( is_null( $juicer_post ) ) {
		_doing_it_wrong( 'juicer_get_author_url()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return $juicer_post->author_url;
}

/**
 * Display the link to the social media author profile.
 *
 * Must be used inside the Juicer Loop.
 */
function juicer_the_author_url() {
	echo juicer_get_author_url();
}
 *
 * Must be used inside the Juicer Loop.
 *
 * @return string The URL to the social media account avatar.
 */
function juicer_get_author_image() : string {
	global $juicer_post;

	if ( is_null( $juicer_post ) ) {
		_doing_it_wrong( 'juicer_get_author_image()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return $juicer_post->author_image;
}

/**
 * Display the social media account avatar URL.
 *
 * Must be used inside the Juicer Loop.
 */
function juicer_the_author_image() {
	echo juicer_get_author_image();
}
