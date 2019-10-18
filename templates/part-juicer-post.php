<?php
/**
 * The Juicer single post template..
 *
 * @package HM\Juicer
 */

?>
<li class="juicer-post juicer-grid__item hide <?php echo juicer_get_video_class(); // Class has already been sanitized. ?>">
	<div class="juicer-post__header">
		<div class="juicer-post__author">
			<?php
			echo sprintf(
				'<img src="%s" alt="%s" class="juicer-post__author__img" />',
				juicer_get_author_image(), // Author image URL has already been sanitized.
				// translators: 1: The author name, 2: the item source.
				esc_html( sprintf( __( '%1$s profile image on %2$s', 'hm-juicer' ), juicer_get_author_name(), juicer_get_source() ) )
			);
			?>
			<span class="juicer-post__author__name"><?php juicer_the_author_name(); // Author name has already been sanitized. ?></span>
		</div>
		<div class="juicer-post__date">
			<?php juicer_the_humanized_time(); // Humanized time has already been sanitized. ?>
		</div>
	</div>

	<img src="<?php juicer_the_image_url(); // Image URL has already been sanitized. ?>" class="juicer-post__image" alt="" />

	<div class="juicer-post__content">
		<?php juicer_the_content(); // Content has already been sanitized. ?>
	</div>

	<div class="juicer-post__sharing">
		<?php
		$comments_count = juicer_get_comment_count();
		$likes_count    = juicer_get_like_count();

		// Comments icon with comments count and tooltip for accessibility.
		echo sprintf(
			'<span class="juicer-post__comments juicer-icon">
				<i class="fas fa-comments"></i>
				<span class="comments-count">%1$s</span>
				<span class="juicer-icon__tooltip screen-reader-text" role="tooltip">' .
					// Translators: %1$s is a localized number for comments count. %2$s is the social media platform source of the post.
					esc_html( _n( '%1$s comment on %2$s', '%1$s comments on %2$s', $comments_count, 'hm-juicer' ) ) .
				'</span>
			</span>',
			number_format_i18n( $comments_count ),
			juicer_get_source() // Source is already sanitized.
		);

		// Thumbs up icon with likes count and tooltip for accessibility.
		echo sprintf(
			'<span class="juicer-post__likes juicer-icon">
				<i class="fas fa-thumbs-up"></i>
				<span class="likes-count">%1$s</span>
				<span class="juicer-icon__tooltip screen-reader-text" role="tooltip">' .
					// Translators: %1$s is a localized number for likes count. %2$s is the social media platform source of the post.
					esc_html( _n( '%1$s like on %2$s', '%1$s likes on %2$s', $likes_count, 'hm-juicer' ) ) .
				'</span>
			</span>',
			number_format_i18n( $likes_count ),
			juicer_get_source() // Source is already sanitized.
		);

		// Source icon with link to post on social network + tooltip for accessibility.
		echo sprintf(
			'<a href="%1$s" class="juicer-post__source__link juicer-post__source juicer-icon">
				<i class="fab fa-facebook-f fa-2x"></i>
				<span class="juicer-icon__tooltip screen-reader-text" role="tooltip">%2$s</span>
			</a>',
			juicer_get_source_url(), // Source URL has already been sanitized.
			// Translators: %s is the original source.
			esc_html( sprintf( __( 'View original post on %s', 'hm-juicer' ), juicer_get_source() ) )
		);
		?>
	</div>
</li>
