<?php
/**
 * HM Juicer functions
 *
 * Public helper functions for the HM Juicer plugin.
 *
 * @package HM\Juicer
 */

use HM\Juicer;

/**
 * Display the main Juicer social media feed.
 *
 * @param int  $count     The number of posts to display.
 * @param int  $page      The page to display.
 * @param bool $load_more Whether to include a load more button.
 */
function juicer_feed( $count = 10, $page = 1, $load_more = false ) {
	global $juicer_posts;
	$juicer_posts = Juicer\get_posts( $count, $page );

	/**
	 * Allow the feed template name to be filtered.
	 *
	 * To allow robust custom templating, it might be desirable to change the feed template file name to something totally different. In this case, developers might want to use this filter to change the feed template slug to something other than 'feed'
	 *
	 * The single post template is called inside the feed template file (by default, although this could be different if a custom feed template was used), so if that template name was customized, it would be reflected in the custom feed template.
	 *
	 * @var string The Juicer feed template slug. Defaults to 'feed' (for part-juicer-feed.php).
	 */
	$feed = apply_filters( 'juicer_filter_feed_template', 'feed' );

	ob_start();
	juicer_get_template( $feed, $count, $load_more );
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
 * Determine if we're in the Juicer Loop by checking the $juicer_post global.
 *
 * @return bool True if we're in the Juicer Loop. False otherwise.
 */
function juicer_in_the_loop() : bool {
	global $juicer_post;

	// Return true if $juicer_post is not empty.
	return ! is_null( $juicer_post );
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

	if ( ! juicer_in_the_loop() ) {
		_doing_it_wrong( 'juicer_get_post()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return $juicer_post;
}

/**
 * Determine if the post is a Facebook video post by looking at the sharing link.
 *
 * @return bool True if the sharing url contains 'videos'. False otherwise.
 */
function juicer_is_video() : bool {
	if ( false !== strpos( juicer_get_sharing_link(), 'videos' ) ) {
		return true;
	}

	return false;
}

/**
 * Output a class based on the results of juicer_is_video.
 *
 * @return string 'juicer-video-post' if the sharing url contains 'videos'. '' otherwise.
 */
function juicer_get_video_class() : string {

	if ( juicer_is_video() ) {
		return 'juicer-video-post';
	}

	return '';
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

	if ( ! juicer_in_the_loop() ) {
		_doing_it_wrong( 'juicer_get_date()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return date( $date_format, $juicer_post->post_date );
}

/**
 * Display the Juicer post date of the current Juicer post.
 *
 * Must be used inside the Juicer Loop.
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

	if ( ! juicer_in_the_loop() ) {
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

	if ( ! juicer_in_the_loop() ) {
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

	if ( ! juicer_in_the_loop() ) {
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

	if ( ! juicer_in_the_loop() ) {
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
 * Return the source url for the posted social media object.
 * Ex. For Facebook, this is the Facebook post.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return string The social media source url.
 */
function juicer_get_source_url() : string {
	global $juicer_post;

	if ( ! juicer_in_the_loop() ) {
		_doing_it_wrong( 'juicer_get_source_url()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return $juicer_post->source_url;
}

/**
 * Display the source url for the posted social media object.
 *
 * Must be used inside the Juicer Loop.
 */
function juicer_the_source_url() {
	echo juicer_get_source_url();
}

/**
 * Return the sharing link for the posted social media object.
 * This is the original post URL or content that was shared on the social media platform.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return string The social media sharing link.
 */
function juicer_get_sharing_link() : string {
	global $juicer_post;

	if ( ! juicer_in_the_loop() ) {
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
 * Return the like count for the current social media post.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return int The post like count.
 */
function juicer_get_like_count() : int {
	global $juicer_post;

	if ( ! juicer_in_the_loop() ) {
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
 * Return the comment count for the social media post.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return int The post comment count.
 */
function juicer_get_comment_count() : int {
	global $juicer_post;

	if ( ! juicer_in_the_loop() ) {
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
 * Return the social media account display name or account name.
 * If display name exists, display name is used, otherwise the account name
 * is used.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return string The social media author/account name.
 */
function juicer_get_author_name() : string {
	global $juicer_post;

	if ( ! juicer_in_the_loop() ) {
		_doing_it_wrong( 'juicer_get_author_name()', __( 'The function was called outside the Juicer Loop.', 'hm-juicer' ), '0.1.0' );
		return '';
	}

	return $juicer_post->author_name;
}

/**
 * Display the social media account display name or account name.
 * If display name exists, display name is used, otherwise the account name
 * is used.
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

	if ( ! juicer_in_the_loop() ) {
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

/**
 * Return the social media account avatar URL.
 *
 * Must be used inside the Juicer Loop.
 *
 * @return string The URL to the social media account avatar.
 */
function juicer_get_author_image() : string {
	global $juicer_post;

	if ( ! juicer_in_the_loop() ) {
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

/**
 * Load the Juicer template file. By default, will load the requested
 * template file from the templates directory in the plugin with a prefix
 * of 'part-juicer', but both the path to the template directory and the
 * prefix can be filtered.
 *
 * @param string $template  (Required) The template to load (e.g. 'feed' or
 * 'post'), not including the prefix ('part-juicer').
 * @param int    $count     The number of posts to load.
 * @param bool   $load_more Whether to display a load more button.
 */
function juicer_get_template( string $template, int $count = 10, bool $load_more = false ) {
	/**
	 * Allow the template directory path to be filtered. Defaults to the templates directory in the plugin.
	 *
	 * E.g. add_filter( 'juicer_filter_template_dir_path', function() {
	 *      return get_template_directory() . 'template-parts';
	 * } );
	 *
	 * @var string The full path to the template directory.
	 */
	$template_path = apply_filters( 'juicer_filter_template_dir_path', plugin_dir_path( dirname( __FILE__ ) ) . 'templates/' );

	/**
	 * Allow the template prefix to be filtered, e.g. if you wanted to use something other than `part-juicer`.
	 *
	 * E.g. add_filter( 'juicer_filter_template_prefix', function() {
	 *      return 'section-social';
	 * } );
	 *
	 * @var string The prefix for the template part.
	 */
	$template_prefix = apply_filters( 'juicer_filter_template_prefix', 'part-juicer' );

	// Build the path to the template file.
	$template_file = "$template_path/$template_prefix-$template.php";

	// Make sure the file exists. If not (e.g. it's been filtered and the path is incorrect), return an error.
	if ( ! file_exists( $template_file ) ) {
		return new WP_Error( 'juicer_file_missing', sprintf(
			// Translators: %s is the path to the template file.
			esc_html__( 'No template file exists at the following path: %s. Please check the path again or contact your administrator.', 'hm-juicer' ),
			$template_file
		) );
	}

	// Set $count and $load_more as query variables to use in the template.
	set_query_var( 'post_count', $count );
	set_query_var( 'load_more', $load_more );

	// Load the template!
	load_template( $template_file, false );
}

/**
 * Reset (empty) the $juicer_posts global.
 *
 * @return array The empty $juicer_posts array.
 */
function juicer_unset_posts() : array {
	global $juicer_posts;

	$juicer_posts = [];

	return $juicer_posts;
}


/**
 * HM Juicer 'Load More' button.
 *
 * @param array $args Array of arguments.
 * @param int   $page The page of posts to display.
 */
function juicer_load_more_button( array $args = [] ) {

	$default_args = [
		'aria_label'       => __( 'Load more', 'hm-juicer' ),
		'button_text'      => __( 'Load more', 'hm-juicer' ),
		'button_class'     => 'btn-load-more btn btn-large',
		'container_class'  => '',
		'list_class'       => '.juicer-feed',
		'page'             => 1,
		'post_count'       => 10,
		'template'         => [
			'name'            => '',
			'vars'            => [],
		],
	];

	$args = wp_parse_args( $args, $default_args );

	// Enqueue 'load more' script.
	wp_enqueue_script( 'hm-juicer-load-more' );

	wp_localize_script(
		'hm-juicer-load-more', 'hmJuicerLoadMore', [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'args'    => $args,
		]
	);

	// Print the button.
	printf(
		/* translators: %1$2 becomes the container class. %2$s becomes the button class.  %3$s becomes the Aria label. %4$s becomes the button text. */
		'<div class="centered-load-more-wrapper %1$s">
			<button class="juicer-feed__load-more %2$s" aria-label="%3$s">%4$s</button>
		</div>',
		esc_attr( $args['container_class'] ),
		esc_attr( $args['button_class'] ),
		esc_attr( $args['aria_label'] ),
		esc_html( $args['button_text'] )
	);
}

function juicer_get_wrapper_classes( array $classes = [] ) : string {
	$classes = apply_filters( 'juicer_filter_wrapper_classes', array_merge( $classes, [
		'juicer-feed',
		'juicer-grid',
	] ) );

	return implode( ' ', $classes );
}
