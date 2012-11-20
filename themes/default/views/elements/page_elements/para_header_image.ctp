<?php 
	$css = '';
	if (isset($config['para_image_header_image_pos'])) {
		$css .= 'float: '.$config['para_image_header_image_pos'].';';
		if ($config['para_image_header_image_pos'] == 'left') {
			$css .= 'margin-right: 10px; margin-bottom: 7px; margin-left: 2px;';
		}
	}
	if (!isset($config['para_image_header_image_size'])) {
		$config['para_image_header_image_size'] = 'medium';
	}
	$para_image_size = 100;
	switch ($config['para_image_header_image_size']) {
		case 'small':
			$para_image_size = 170;
			break;
		case 'medium':
			$para_image_size = 234;
			break;
		case 'large':
			$para_image_size = 300;
			break;
	}
?>
<div class="para_header_image_page_element_cont">
	<?php if (!empty($config['para_image_header_text'])): ?>
		<h2><b><?php echo $config['para_image_header_text']; ?></b></h2>
	<?php endif; ?>
	<?php if (isset($config['para_header_image_photo_id']) && $config['para_header_image_photo_id'] != -1): ?>
		<img src="<?php echo $this->Photo->get_photo_path($config['para_header_image_photo_id'], $para_image_size, $para_image_size); ?>" style="<?php echo $css; ?>" />
	<?php endif; ?>
	<?php if (isset($config['para_image_paragraph_text'])): ?>
		<?php echo $config['para_image_paragraph_text']; ?>
	<?php endif; ?>
	<div style="clear: both"></div>
</div>
<div style="clear: both"></div>