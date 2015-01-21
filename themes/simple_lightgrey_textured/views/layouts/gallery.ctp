<!DOCTYPE html>
<html>
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
			<div style="clear: both"></div>
			<div id="grey_textured_gallery_listing_container">
				<?php if (count($photos) > 0): ?>
					<?php echo $this->Element('gallery/pagination_links', array('extra_css' => 'margin-top: 10px; margin-bottom: 10px; float: right;')); ?>
					<?php echo $this->Element('gallery/gallery_image_lists/4_column', array('gallery_id' => $curr_gallery['PhotoGallery']['id'], 'photos' => $photos, 'image_max_size' => 179)); ?>
					<?php echo $this->Element('gallery/pagination_links', array('extra_css' => 'margin-top: 10px; margin-bottom: 10px; float: right;')); ?>
				<?php endif; ?>
			</div>
		</div>
	</body>
</html>