<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $this->Theme->get_frontend_html_title(); ?></title>
	<?php echo $this->Element('theme_global_includes'); ?>
	<link rel="stylesheet" type="text/css" href="/css/white_slider_style.css" />
	<link href='//fonts.googleapis.com/css?family=PT+Sans:400italic,400' rel='stylesheet' type='text/css' />
	
	<script src="/js/php_closure/white_slider.min.js"></script>
</head>
<body>
	<?php
		$max_gallery_images = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'max_gallery_images'); 
		$gallery_to_use_id = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_gallery', null);
		if (empty($gallery_to_use_id)) {
			$first_gallery = $this->Gallery->get_first_gallery_by_weight();
			if (!empty($first_gallery['PhotoGallery']['id'])) {
				$gallery_to_use_id = $first_gallery['PhotoGallery']['id'];
			}
		}
		$photos = $this->Theme->get_landing_page_slideshow_images($max_gallery_images, $gallery_to_use_id, true);
	?>
	<?php if (count($photos) > 0): ?>
<!--		<div class="endless_loading">Loading</div> maybe use this later-->
		<div id="white_slider_listing_actual_container_loading"><?php echo  __('Loading', true); ?></div>
		<div id="white_slider_listing_actual_container" data-gallery_id="<?php echo $gallery_to_use_id; ?>" data-max_gallery_images="<?php echo $max_gallery_images; ?>"><img class="blank" src="/images/large_blank.png" width="1600" height="500" alt="" /><!--
			--><?php echo $this->Element('gallery/gallery_image_lists/simple_list', array(
				'photos' => $photos,
				'height' => '500',
				'width' => '2000',
				'sharpness' => '.4'
			)); ?><img class="blank" src="/images/large_blank.png" width="1600" height="500" alt="" /></div>
		<div id="white_slider_scroll_hide" class=""></div>
		<div id="left_arrow" class="navigation_arrow">

		</div>
		<div id="right_arrow" class="navigation_arrow">

		</div>
		<!--DREW TODO  make the below div cover the entire content-->
		<div id="entire_slider_hider"></div>
	<?php else: ?>
		<h4 style="font-weight: bold; font-style: italic; margin: 10px;"><?php echo __('This gallery does not have any images yet',true); ?></h4><?php // DREW TODO - make this section look good ?>
	<?php endif; ?>
		
	<div class="container">
		<div id="image_slider_progressbar_container"><div id="image_slider_progressbar"></div></div>
		
		
		<?php echo $this->Element('nameTitle'); ?>

		<?php //echo $this->Element('temp_menu'); ?>
		<?php echo $this->Element('menu/two_level_navbar'); ?>


		
		<div style="clear: both"></div>
		<div id="white_slider_listing_container"></div>
		
		<div id="white_slider_scroll_control_cont">
			<div id="hide_control_scroll_div" class=""></div>
			<div id="white_slider_scroll_control">
				<div id="white_slider_scroll_control_inner">
					<div class="scroll_control_div"></div>
					<img class="blank" src="/images/blank.png" width="160" height="50" alt="" /><?php echo $this->Element('gallery/gallery_image_lists/simple_list', array(
						'photos' => $photos,
						'height' => '50',
						'width' => '200',
						'sharpness' => '.4'
					)); ?><img class="blank" src="/images/blank.png" width="160" height="50" alt="" />
				</div>
			</div>
		</div>
	</div>
</body>
</html>