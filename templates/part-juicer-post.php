<?php
/**
 * The Juicer single post template..
 *
 * @package HM\Juicer
 */

?>
<li class="juicer-post juicer-grid__item">
	<div class="juicer-post__inner">
		<div class="juicer-post__header">
			<div class="juicer-post__author">
				<?php
				echo sprintf(
					'<a href="%1$s"><img src="%2$s" alt="%3$s %4$s %5$s" />%5$s</a>',
					juicer_get_author_url(),
					juicer_get_author_image(),
					juicer_get_source(),
					esc_html__( 'profile image for', 'hm-juicer' ),
					juicer_get_author_name()
				);
				?>
			</div>
			<div class="juicer-post__date">
				<?php juicer_the_humanized_time(); ?>
			</div>
		</div>
		<div class="juicer-post__image">
			<img src="<?php juicer_the_image_url(); ?>" />
		</div>
		
		<div class="juicer-post__content">
		<?php juicer_the_content(); ?>
		</div>
		<div class="juicer-post__source">
		<?php juicer_the_source(); ?>
		</div>
		<div class="juicer-post__sharing">
		<?php juicer_the_sharing_link(); ?>
		</div>
		<div class="juicer-post__likes">
		<?php juicer_the_like_count(); ?>
		</div>
		<div class="juicer-post__comments">
		<?php juicer_the_comment_count(); ?>
		</div>
	</div>
</li>
