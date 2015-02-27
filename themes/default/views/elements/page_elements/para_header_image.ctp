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
<div class="site_element para_header_image_page_element_cont <?php echo $config['para_image_header_image_pos']; ?> <?php echo $classes; ?>">
	<?php if (!empty($config['para_image_header_text'])): ?>
		<h2><b><?php echo $config['para_image_header_text']; ?></b></h2>
	<?php endif; ?>
	<?php if (isset($config['para_header_image_photo_id']) && $config['para_header_image_photo_id'] != -1): ?>
		<?php $img_data = $this->Photo->get_photo_path($config['para_header_image_photo_id'], $para_image_size, $para_image_size, .4, true); ?>
		<img class='<?php echo $config['para_image_header_image_pos']; ?>' src="<?php echo $img_data['url']; ?>" <?php echo $img_data['tag_attributes']; ?> style="<?php echo $css; ?>" alt="" />
	<?php endif; ?>
	<?php if (isset($config['para_image_paragraph_text'])): ?>
		<?php echo $config['para_image_paragraph_text']; ?>
	<?php endif; ?>
	<div style="clear: both"></div>
</div>
<div style="clear: both"></div>
