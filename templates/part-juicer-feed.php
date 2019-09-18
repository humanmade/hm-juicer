<?php
/**
 * The main Juicer feed template that holds the Juicer Loop.
 *
 * @package HM\Juicer
 */

?>

<?php
// Start the Juicer Loop.
if ( juicer_have_posts() ) :

	echo '<ul class="' . juicer_get_wrapper_classes() . '">';

	while ( juicer_have_posts() ) :
		juicer_the_post();

		// Load the single Juicer post template.
		juicer_get_template( 'post' );

	endwhile;

	echo '</ul>';

	if ( $load_more && $post_count ) {
		juicer_load_more_button(
			[
				'button_text' => __( 'Load more social media posts', 'encompass' ),
				'aria_label'  => __( 'Load more social media posts', 'encompass' ),
				'post_count'  => $post_count,
			]
		);
	}

endif;
