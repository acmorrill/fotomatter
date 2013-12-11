<?php
$icon_sizes = $this->Photo->get_admin_photo_icon_size($not_in_gallery_icon_size);
$height = $icon_sizes['height'];
$width = $icon_sizes['width'];

/*******************************************************
	REMEMBER IF YOU CHANGE THIS YOU ALSO HAVE TO
	TAKE INTO ACCOUNT HOW THIS IS USED IN JAVASCRIPT
*******************************************************/
?>
<?php foreach ($connected_photos as $the_photo): ?>
<div class="connect_photo_container" photo_id="<?php if (!isset($hide_data)) echo $the_photo['Photo']['id']; ?>" style="height: <?php echo $height; ?>px;width: <?php echo $width; ?>px;">
	<div class="table">
		<div class="tr">
			<img class="abs_image_tr remove_from_gallery_button" src="/img/admin/icons/bw_simple_close_icon.png" />
			<img class="abs_image_tl order_in_gallery_button" src="/img/admin/icons/white_arrange.png" />
			<div class="image_content_cont td">
				<img src="<?php if (!isset($hide_data)) echo $this->Photo->get_photo_path($the_photo['Photo']['id'], $height, $width); ?>" alt="<?php __('click to remove'); ?>" />
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>