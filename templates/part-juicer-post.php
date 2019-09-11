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
			<?php
			// Comments icon with comments count and tooltip for accessibility.
			echo sprintf(
				'<span class="juicer-post__comments juicer-icon">
					<i class="fas fa-comments"></i>
					<span class="comments-count">%1$s</span>
					<span class="juicer-icon__tooltip screen-reader-text" role="tooltip">%1$s %2$s %3$s</span>
				</span>',
				juicer_get_comment_count(),
				esc_html__( 'comment(s) on', 'hm-juicer' ),
				juicer_get_source()
			);
			
			// Thumbs up icon with likes count and tooltip for accessibility.
			echo sprintf(
				'<span class="juicer-post__likes juicer-icon">
					<i class="fas fa-thumbs-up"></i>
					<span class="likes-count">%1$s</span>
					<span class="juicer-icon__tooltip screen-reader-text" role="tooltip">%1$s %2$s %3$s</span>
				</span>',
				juicer_get_like_count(),
				esc_html__( 'like(s) on', 'hm-juicer' ),
				juicer_get_source()
			);

			// Source icon with link to post on social network + tooltip for accessibility.
			echo sprintf(
				'<a href="%1$s" class="juicer-post__source juicer-icon">
					<i class="fab fa-facebook-f fa-2x"></i>
					<span class="juicer-icon__tooltip screen-reader-text" role="tooltip">%2$s %3$s</span>
				</a>',
				juicer_get_sharing_link(),
				esc_html__( 'View original post on', 'hm-juicer' ),
				juicer_get_source()
			);
			?>
		</div>
	</div>
</li>
