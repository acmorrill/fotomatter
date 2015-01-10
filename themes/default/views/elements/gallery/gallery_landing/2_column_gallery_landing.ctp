<table id="gallery_list_table" class="two_column_gallery_list">
    <tr class="gallery_list_top_message"><td colspan="2"></td></tr>
	<?php $all_galleries = $this->Gallery->get_all_galleries(); ?>
	<?php for ($index = 0; $index < count($all_galleries); $index++): ?>
		<?php $curr_gallery = $all_galleries[$index]; ?>
			<?php if ($index % 2 == 0): ?><tr><?php endif; ?>
			<td>
				<div class="galleries">
					<?php
					$curr_gallery_href = $this->Html->url(array(
						'controller' => 'photo_galleries',
						'action' => 'view_gallery',
						$curr_gallery['PhotoGallery']['id']
					));
					$photo_id = $this->Gallery->get_gallery_photo_id($curr_gallery['PhotoGallery']['id']);
					?>
					<?php $img_src = $this->Photo->get_photo_path($photo_id, 250, 250, .4, true); ?>                      
					<span class="gallery_image_outer_cont">
						<a class="gallery_image_a_link" href="<?php echo $curr_gallery_href; ?>">
							<img class="gallery_image_actual_image" src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> alt="" />
						</a>
					</span>
					<div class="galleriesLink">
						<a href="<?php echo $curr_gallery_href; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></a>
					</div>
				</div>
				</div>
			</td>
			<?php if (($index + 1) % 2 == 0): ?></tr><?php endif; ?>
	<?php endfor; ?>
</table>



















