<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this->Theme->get_frontend_html_title(); ?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/css/andrewmorrill_style.css" />
		<link href='//fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<?php echo $this->Theme->get_theme_dynamic_background_style($theme_config); ?>
</script>
	</head>
	<body>
		<?php echo $this->Element('nameTitle'); ?>
		<div id="slideShowDiv" style="width: 556px; height: 453px;">
			<?php echo $this->Element('landing_slideshows/basic', array(
				'width' => 556,
				'height' => 453,
				'background_color' => '#efefef',
			)); ?>

			<div id="imageActions">
				<span class="actionIcons">
					<a href="#" title="Report Image"><span class="icon-warning"></span></a>
					<a href="#" title="Comments"><span class="icon-bubbles4"></span></a>
					<!-- <span class="icon-cart"></span> -->
					<a href="#" title="See Fullscreen" onClick="makeFullScreen(); return false;"><span class="icon-enlarge"></span></a>
				</span>
			</div>
			
			<?php echo $this->Element('global_theme_footer_copyright'); ?>
		</div>
		
		<?php $intro_text = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_into_text'); ?>
		<p id="introBlurb"><?php echo strip_tags($intro_text); ?></p>
	
		<div id="side_menu_bg"></div>
		<?php echo $this->Element('menu/navBar', array( 'page' => 'home' )); ?>

		<div id="fullScreen" style="width:100%; height:100%; position:absolute; background:#000; display:none;text-align:center;vertical-align:middle;">
			<?php 
				$max_num_images = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_slideshow_max_images');
				$gallery_to_use_id = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_gallery', null);
				$slide_show_photo_ids = $this->Theme->get_landing_page_slideshow_images($max_num_images, $gallery_to_use_id);
				$total_images = count($slide_show_photo_ids); 
					
				$count = 1; 
				
				foreach ($slide_show_photo_ids as $slide_show_photo_id):
					$img_src = $this->Photo->get_photo_path($slide_show_photo_id,2000,2000, .4, true, false); 
					echo '<img class="hiddenFull" style="display:none;margin:auto !important;width:100% !important; height:auto !important;" src="'.$img_src['url'].'" rel="'.$slide_show_photo_id.'" />';
					$count++; 
				endforeach; 
			?>

			<a href="#" title="Close Fullscreen" onClick="closeFullScreen(); return false;" style="position:absolute;bottom:0;right:0;font-size:44px;color:#fff; padding: 20px; background:rgba(0,0,0,.25);"><span class="icon-shrink"></span></a>
		</div>
	</body>
</html>