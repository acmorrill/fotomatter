<?php 
	$css = '';
	if (isset($config['para_image_header_image_pos'])) {
		$css .= 'float: '.$config['para_image_header_image_pos'].';';
	}
	if (!isset($config['para_image_header_image_size'])) {
		$config['para_image_header_image_size'] = 'medium';
	}
	$para_image_size = 100;
	switch ($config['para_image_header_image_size']) {
		case 'small':
			$para_image_size = 100;
			break;
		case 'medium':
			$para_image_size = 150;
			break;
		case 'large':
			$para_image_size = 300;
			break;
	}
?>
<div class="para_header_image_page_element_cont">
	<h2><b><?php echo $config['para_image_header_text']; ?></b></h2>
	<img src="<?php echo $this->Photo->get_photo_path($config['para_header_image_photo_id'], $para_image_size, $para_image_size); ?>" style="<?php echo $css; ?>" />
	<?php echo $config['para_image_paragraph_text']; ?>
	<div style="clear: both"></div>
</div>
<div style="clear: both"></div>
