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
			<div class="td">
				<div class="image_content_cont">
					<?php /*<img class="abs_image_tr remove_from_gallery_button" src="/img/admin/icons/bw_simple_close_icon.png" />*/ ?>
					<?php /*<img class="abs_image_tl order_in_gallery_button" src="/img/admin/icons/white_arrange.png" />*/ ?>
					<div class="remove_from_gallery_button gallery_image_circle_button top_right">
						<div class="inner_button">X</div>
					</div>
					<div class="remove_from_gallery_button gallery_image_circle_button bottom_left">
						<div class="reorder_icon"></div>
					</div>
					<img src="<?php if (!isset($hide_data)) echo $this->Photo->get_photo_path($the_photo['Photo']['id'], $height, $width); ?>" alt="<?php echo __('click to remove', true); ?>" />
				</div>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>