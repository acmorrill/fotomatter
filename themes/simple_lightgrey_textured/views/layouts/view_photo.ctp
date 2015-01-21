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
  
	<?php //echo $this->Element('temp_menu'); ?>
	<?php echo $this->Element('menu/two_level_navbar'); ?>
	
	<div style="clear: both;"></div>
	<?php $is_pano = $curr_photo['PhotoFormat']['ref_name'] == "panoramic"; ?>
	<div id="largePhotoPos">
		<?php 
			$dynamic_photo_sizes = $this->Theme->get_dynamic_photo_size(700, 900, 1200, $curr_photo['PhotoFormat']['ref_name']);
			$img_src = $this->Photo->get_photo_path($curr_photo['Photo']['id'], $dynamic_photo_sizes['photo_size'], $dynamic_photo_sizes['photo_size'], .4, true); 
		?>

		<div class="image_navigation">
			<?php $prev_image_web_path = $this->Photo->get_prev_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
			<a class="photo_page_nav prev_image arrow <?php if ($is_pano): ?> is_pano<?php endif; ?>" href="<?php echo $prev_image_web_path; ?>">
				<img src="/img/arrowLeft.png" alt="" />
			</a>
			<?php $next_image_web_path = $this->Photo->get_next_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
			<a class="photo_page_nav next_image arrow <?php if ($is_pano): ?> is_pano<?php endif; ?>" href="<?php echo $next_image_web_path; ?>">
				<img src="/img/arrowRight.png" alt="" />
			</a>
		</div>
		<div style="clear: both;"></div>
		<div id="mainImage">
			<img src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> alt="<?php echo $curr_photo['Photo']['alt_text']; ?>" />
			
		</div>
		
		<div id='image_data_container'>
			<h2 class="photo_title"><?php echo $curr_photo['Photo']['display_title']; ?></h2>
			<?php if (!empty($curr_photo['Photo']['display_subtitle'])): ?>
				<h3 class='photo_subtitle'>
					<?php echo $curr_photo['Photo']['display_subtitle']; ?>
				</h3>
			<?php endif; ?>
			
			<?php if (!empty($curr_photo['Photo']['date_taken'])): ?>
				<h3 class='photo_date'>
					<?php $phpdate = strtotime($curr_photo['Photo']['date_taken']); ?>
					<?php echo date("F Y", $phpdate); ?>
				</h3>
			<?php endif; ?>
			
			<?php if (!empty($curr_photo['Photo']['description'])): ?>
				<p class='photo_description'><?php echo $curr_photo['Photo']['description']; ?></p>
			<?php endif; ?>
			
			<br style='clear: both;' />
				
			<?php echo $this->Element('cart_checkout/image_add_to_cart_form_simple', array(
				'beforeHtml' => '<div class="hr"></div>'
			)); ?>
			
		</div>
	</div>

</div>


</body>
</html>