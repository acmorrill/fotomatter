<?php
	$icon_sizes = $this->Photo->get_admin_photo_icon_size($not_in_gallery_icon_size);
	$height = $icon_sizes['height'];
	$width = $icon_sizes['width'];
?>

<?php foreach ($not_connected_photos as $not_connected_photo): ?>
	<div class="connect_photo_container" photo_id="<?php echo $not_connected_photo['Photo']['id']; ?>" style="height: <?php echo $height; ?>px;width: <?php echo $width; ?>px;">
		<div class="table">
			<div class="tr">
				<div class="td">
					<div class="image_content_cont">
						<!--<img class="abs_image_br add_to_gallery_button" src="/img/admin/icons/green_simple_plus_button.png" />-->
						<div class="add_to_gallery_button gallery_image_circle_button bottom_right">
							<div class="plus_icon">+</div>
						</div>
						<img src="<?php echo $this->Photo->get_photo_path($not_connected_photo['Photo']['id'], $height, $width); ?>" alt="<?php __('click to add'); ?>" />
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; ?>



