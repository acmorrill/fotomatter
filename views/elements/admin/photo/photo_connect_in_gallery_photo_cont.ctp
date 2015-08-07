<?php
$icon_sizes = $this->Photo->get_admin_photo_icon_size($not_in_gallery_icon_size);
$height = $icon_sizes['height'];
$width = $icon_sizes['width'];
$class = $icon_sizes['class'];

/*******************************************************
	REMEMBER IF YOU CHANGE THIS YOU ALSO HAVE TO
	TAKE INTO ACCOUNT HOW THIS IS USED IN JAVASCRIPT
*******************************************************/
?>
<?php foreach ($connected_photos as $the_photo): ?>
<div class="<?php echo $class; ?> connect_photo_container" photo_id="<?php if (!isset($hide_data) && isset($the_photo['Photo']['id'])) echo $the_photo['Photo']['id']; ?>">
	<div class="image_cover"></div>
	<div class="remove_from_gallery_button gallery_image_circle_button top_right">
		<div class="icon-close-01"></div>
	</div>
	<div class="order_in_gallery_button gallery_image_circle_button bottom_left">
		<div class="reorder_icon icon-position-01"></div>
	</div>
	<div class="table">
		<div class="tr">
			<div class="td">
				<div class="image_content_cont">
					<?php if (!isset($hide_data)): ?>
						<?php if (isset($the_photo['Photo']['id'])): ?>
							<img src="<?php echo $this->Photo->get_photo_path($the_photo['Photo']['id'], $height, $width); ?>" alt="" />
						<?php else: ?>
							<img src="<?php echo $this->Photo->get_photo_path(0, $height, $width); ?>" alt="" />
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>