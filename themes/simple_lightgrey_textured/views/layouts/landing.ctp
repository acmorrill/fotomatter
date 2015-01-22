<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo $this->Theme->get_frontend_html_title(); ?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<!--<script type='text/javascript' src='/js/php_closure/simple_lightgrey_textured.min.js'></script>-->
		<link href="/css/simple_lightgrey_textured_style.css" rel="stylesheet" type="text/css" />
		<?php echo $this->Theme->get_theme_dynamic_background_style($theme_config); ?>
	</head>
	<body>
		<div class="container">
			<?php echo $this->Element('nameTitle'); ?>
			<?php echo $this->Element('menu/two_level_navbar'); ?>

			<?php
				$show_white_border = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'show_white_border', 'off');
				$gallery_to_use_id = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_gallery', null);
				$images = $this->Theme->get_landing_page_slideshow_images(1, $gallery_to_use_id);
				$imgSrc = array();
				if (!empty($images[0])) {
					$imgSrc = $this->Photo->get_photo_path($images[0], 720, 720, .5, true);
				}
			?>
			<?php if (!empty($imgSrc)): ?>
				<div id="landing_image"><div class='landing_image_inner' style="<?php echo $imgSrc['style_attributes']; ?> <?php if ($show_white_border == 'off'):?>border: 0px;<?php endif; ?>"><img src="<?php echo $imgSrc['url']; ?>" <?php echo $imgSrc['tag_attributes']; ?> style="<?php echo $imgSrc['style_attributes']; ?>" /></div></div>
			<?php endif; ?>
		</div>
	</body>
</html>