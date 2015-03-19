<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $curr_gallery['PhotoGallery']['display_name']; ?> &mdash; <?php echo $this->Theme->get_frontend_html_title(); ?></title>
	<?php echo $this->Element('theme_global_includes'); ?>
	<link rel="stylesheet" type="text/css" href="/css/white_slider_subone.css" />
	<link href='//fonts.googleapis.com/css?family=PT+Sans:400italic,400' rel='stylesheet' type='text/css' />
	
	<script src="/js/php_closure/white_slider_subone.min.js"></script>
</head>
<body>
	<?php $max_gallery_images = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'max_gallery_images'); ?>
	<?php 
		if (!isset($photos)) {
			$photos = array();
		}
	?>
	<div id="white_slider_listing_actual_container" data-gallery_id="<?php echo $gallery_id; ?>" data-max_gallery_images="<?php echo $max_gallery_images; ?>"><img class="blank" src="/images/large_blank.png" width="1600" height="500" alt="" /><!--
	--><?php echo $this->Element('gallery/gallery_image_lists/simple_list', array(
		'photos' => $photos,
		'height' => '500',
		'width' => '2000',
		'sharpness' => '.4'
	)); ?><img class="blank" src="/images/large_blank.png" width="1600" height="500" alt="" /></div>
	<div id="white_slider_scroll_hide" class=""></div>
	
	
	<!--<div id="left_arrow" class="navigation_arrow">

	</div>
	<div id="right_arrow" class="navigation_arrow">

	</div>-->
	
	<div id="entire_slider_hider"></div>
		
	<div id="header_background"></div>
	<div id="below_header_line"></div>
	
	<div class="container">
		<div id="image_slider_progressbar_container"><div id="image_slider_progressbar"></div></div>
		
		
		<?php echo $this->Element('nameTitle'); ?>

		<?php echo $this->Element('menu/two_level_navbar'); ?>

		
		<div style="clear: both"></div>
		<div id="white_slider_listing_container"></div>
		
		
		<div id="white_slider_ecommerce_container">
			<?php echo $this->Element('gallery/image_data/image_data_list', compact('photos')); ?>
		</div>
		
		<?php echo $this->Element('global_theme_footer_copyright'); ?>
	</div>
</body>
</html>