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

	echo '<ul class="juicer-feed juicer-grid">';

	while ( juicer_have_posts() ) :
		juicer_the_post();

		// Load the single Juicer post template.
		juicer_get_template( 'post' );

	endwhile;

	echo '</ul>';

endif;
