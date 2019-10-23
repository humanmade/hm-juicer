<?php
/**
 * The Juicer single post template..
 *
 * @package HM\Juicer
 */

?>
<li class="juicer-post juicer-grid__item hide <?php echo juicer_get_video_class(); // Class has already been sanitized. ?>">
	<div class="juicer-post__header">
		<?php
		echo sprintf(
			'<a href="%1$s" class="juicer-post__author" aria-label="%3$s"><img src="%2$s" alt="" class="juicer-post__author__img" /><span class="juicer-post__author__name">%4$s</span></a>',
			juicer_get_author_url(), // Author URL has already been sanitized.
			juicer_get_author_image(), // Author image URL has already been sanitized.
			// translators: 1: The author name, 2: the item source.
			esc_html( sprintf( __( 'Visit %1$s on %2$s', 'hm-juicer' ), juicer_get_author_name(), juicer_get_source() ) ),
			juicer_get_author_name() // Author name has already been sanitized.
		);

		// Only link the date if the read more link doesn't go to the social network post.
		if ( strpos( juicer_get_sharing_link(), strtolower( juicer_get_source() ) ) ) {
			echo sprintf(
				'<span class="juicer-post__date">%s</span>',
				juicer_get_humanized_time() // Humanized time has already been sanitized.
			);
		} else {
			echo sprintf(
				'<a href="%1$s" class="juicer-post__date" aria-label="%3$s">
					%2$s
				</a>',
				juicer_get_source_url(), // Source URL has already been sanitized.
				juicer_get_humanized_time(), // Humanized time has already been sanitized.
				// Translators: 1: The original source, 2: The date/time posted.
				esc_html(
					sprintf( __( '%1$s post from %2$s' ),
						juicer_get_source(),
						juicer_get_humanized_time()
					)
				)
			);
		}
		?>
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
				<i class="fas fa-comments" aria-hidden="true"></i>
				<span class="comments-count" aria-hidden="true">%1$s</span>
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
				<i class="fas fa-thumbs-up" aria-hidden="true"></i>
				<span class="likes-count" aria-hidden="true">%1$s</span>
				<span class="juicer-icon__tooltip screen-reader-text" role="tooltip">' .
					// Translators: %1$s is a localized number for likes count. %2$s is the social media platform source of the post.
					esc_html( _n( '%1$s like on %2$s', '%1$s likes on %2$s', $likes_count, 'hm-juicer' ) ) .
				'</span>
			</span>',
			number_format_i18n( $likes_count ),
			juicer_get_source() // Source is already sanitized.
		);

		// Social network source icon.
		echo sprintf(
			'<span class="juicer-post__source juicer-icon">
				<i class="fab fa-facebook-f fa-2x" aria-hidden="true"></i>
				<span class="screen-reader-text">%s</span>
			</span>',
			// Translators: %s is the original source.
			esc_html( sprintf( __( 'Originally shared on %s', 'hm-juicer' ), juicer_get_source() ) )
		);
		?>
	</div>
</li>
