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
		<div class="juicer-post__author-name">
		<?php juicer_the_author_name(); ?>
		</div>
		<div class="juicer-post__author-url">
		<?php juicer_the_author_url(); ?>
		</div>
		<div class="juicer-post__author-image">
		<?php juicer_the_author_image(); ?>
		</div>
	</div>
</li>
