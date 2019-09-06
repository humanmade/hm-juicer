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
	while ( juicer_have_posts() ) :
		juicer_the_post();

		// Load the single Juicer post template.
		juicer_get_template( 'post' );

	endwhile;
endif;
