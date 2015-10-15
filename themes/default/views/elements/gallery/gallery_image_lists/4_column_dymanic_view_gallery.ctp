<?php for ($index = 0; $index < count($photos); $index++): ?>
	<?php $curr_photo = $photos[$index]; ?>
	<div class="gallery_photos">
            <?php $photoUrl = "/photos/view_photo/{$curr_photo['Photo']['id']}/gid:$gallery_id/"; ?>
			<?php
				$do_crop = false;
				if (isset($crop)) {
					$do_crop = $crop;
				}
			?>
            <a href="<?php echo $photoUrl; ?>">
            <?php $imgSrc = $this->Photo->get_photo_path($curr_photo['Photo']['id'], $image_max_size, $image_max_size, .5, true, $do_crop); ?>
            <div class="gallery_photo_size">
                <table>
                    <tr>
                        <td>
                            <img src="<?php echo $imgSrc['url']; ?>" <?php echo $imgSrc['tag_attributes']; ?> alt="" title="<?php echo $curr_photo['Photo']['alt_text']; ?>" />
                        </td>
                    </tr> 
                </table>
            </div>       
	</div>
<?php endfor; ?>
<?php if (count($photos) > 0): ?>
<?php else: ?>
	<h4 style="font-weight: bold; font-style: italic; margin: 10px;"><?php __('This gallery does not have any images yet'); ?></h4><?php // DREW TODO - make this seccion look good    ?>
<?php endif; ?>
    <div class="clear"></div>
     