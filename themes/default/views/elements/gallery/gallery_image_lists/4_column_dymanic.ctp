


	<?php for($index = 0; $index < count($photos); $index++): ?>
			<?php $curr_photo = $photos[$index]; ?>
			<div class="photos">
				<?php $photoUrl = "/photos/view_photo/{$curr_photo['Photo']['id']}/gid:$gallery_id/"; ?>
				<a href="<?php echo $photoUrl; ?>">
					<?php $imgSrc = $this->Photo->get_photo_path($curr_photo['Photo']['id'], $image_max_size, $image_max_size, .5, true); ?>
					
					<img src="<?php echo $imgSrc['url']; ?>" <?php echo $imgSrc['tag_attributes']; ?> alt="<?php echo $curr_photo['Photo']['alt_text']; ?>" />
					<br />
					New Release      </a>
			</div>
	<?php endfor; ?>






















