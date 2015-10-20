<table id="gallery_list_table" class="two_column_gallery_list">
	<?php if (isset($top_message)): ?>
		<tr class="gallery_list_top_message"><td colspan="2"><h4><?php echo $top_message; ?></h4></td></tr>
	<?php endif; ?>
	<?php for($index = 0; $index < count($photos); $index++): ?>
		<?php if ($index % 4 == 0): ?><tr><?php endif; ?>
			<?php $curr_photo = $photos[$index]; ?>
			<td>
				<div class="galleries">
					<?php
						if ($curr_photo['Photo']['PhotoFormat']['ref_name'] === 'square') {
							$using_max_size = $this->Theme->reduce_gallery_list_square_size($image_max_size);
						} else {
							$using_max_size = $image_max_size;
						}
						$photoUrl = "/photos/view_photo/{$curr_photo['Photo']['id']}/gid:$gallery_id"; 
						$imgSrc = $this->Photo->get_photo_path($curr_photo['Photo']['id'], $using_max_size, $using_max_size, .5, true);
					?>
					<span class="gallery_image_outer_cont" style="<?php echo $imgSrc['style_attributes']; ?>">
						<a class="gallery_image_a_link" href="<?php echo $photoUrl; ?>" style="<?php echo $imgSrc['style_attributes']; ?>">
							<img class="gallery_image_actual_image" src="<?php echo $imgSrc['url']; ?>" <?php echo $imgSrc['tag_attributes']; ?> <?php echo $imgSrc['alt_title_str']; ?> style="<?php echo $imgSrc['style_attributes']; ?>">
						</a>
					</span>
					<div class="galleriesLink">
						<a href="<?php echo $photoUrl; ?>">"<?php echo $curr_photo['Photo']['display_title']; ?>"</a><br/>
						<?php echo $curr_photo['Photo']['display_subtitle']; ?>
					</div>
				</div>
			</td>
		<?php if (($index+1) % 4 == 0): ?></tr><?php endif; ?>
	<?php endfor; ?>
</table>





















