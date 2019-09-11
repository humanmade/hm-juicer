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

		<img src="<?php juicer_the_image_url(); ?>" class="juicer-post__image" alt="" />

		<div class="juicer-post__content">
			<?php juicer_the_content(); ?>
		</div>

		<div class="juicer-post__sharing">
			<div class="juicer-post__comments">
				<i class="fas fa-comments"></i>
				<?php juicer_the_comment_count(); ?>
			</div>
			<div class="juicer-post__likes">
				<i class="fas fa-thumbs-up"></i>
				<?php juicer_the_like_count(); ?>
			</div>
			<div class="juicer-post__source">
				<?php
				echo sprintf(
					'<a href="%1$s" class="juicer-icon juicer-icon--facebook">
						<i class="fab fa-facebook-f fa-2x"></i>
						<span class="juicer-icon__tooltip screen-reader-text" role="tooltip">%2$s %3$s</span>
					</a>',
					juicer_get_sharing_link(),
					esc_html__( 'Link to original post on', 'hm-juicer' ),
					juicer_get_source()
				);
				?>
			</div>
		</div>
	</div>
</li>
