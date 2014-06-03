<?php for ($index = 0; $index < count($photos); $index++): ?>
	<?php $curr_photo = $photos[$index]; ?>
	<div class="photos">
		<?php $photoUrl = "/photo_galleries/view_gallery/gallery/{$curr_photo['Photo']['id']}/gid:$gallery_id/"; ?>
		<a href="<?php echo $photoUrl; ?>">
			<?php $imgSrc = $this->Photo->get_photo_path($curr_photo['Photo']['id'], $image_max_size, $image_max_size, .5, true); ?>

			<img src="<?php echo $imgSrc['url']; ?>" <?php echo $imgSrc['tag_attributes']; ?> alt="<?php echo $curr_photo['Photo']['alt_text']; ?>" />
			<br />
			New Release      </a>
	</div>
<?php endfor; ?>
<?php if (count($photos) > 0): ?>
<?php else: ?>
	<h4 style="font-weight: bold; font-style: italic; margin: 10px;"><?php __('This gallery does not have any images yet'); ?></h4><?php // DREW TODO - make this seccion look good    ?>
<?php endif; ?>