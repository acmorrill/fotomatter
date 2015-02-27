<div class="site_element image_page_element_cont <?php echo $classes; ?>" style="text-align: center;">
	<?php 
		if (!isset($config['image_element_image_photo_id'])) {
			$config['image_element_image_photo_id'] = -1;
		}
	?>
	<?php $img_data = $this->Photo->get_photo_path($config['image_element_image_photo_id'], 600, 480, .4, true); ?>
	<img src="<?php echo $img_data['url']; ?>" <?php echo $img_data['tag_attributes']; ?> style="float: none;" alt="" />
	<div style="clear: both"></div>
</div>
<div style="clear: both"></div>
