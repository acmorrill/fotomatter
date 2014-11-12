<?php //debug($config); ?>
<div class="image_page_element_cont" style="text-align: center;">
	<?php 
		if (!isset($config['image_element_image_photo_id'])) {
			$config['image_element_image_photo_id'] = -1;
		}
	?>
	<img src="<?php echo $this->Photo->get_photo_path($config['image_element_image_photo_id'], 600, 480); ?>" style="float: none;" alt="" />
	<div style="clear: both"></div>
</div>
<div style="clear: both"></div>
