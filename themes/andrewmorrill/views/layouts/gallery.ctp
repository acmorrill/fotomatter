<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $curr_gallery['PhotoGallery']['display_name']; ?> &mdash; <?php echo $this->Theme->get_frontend_html_title(); ?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<link href='//fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/andrewmorrill_style.css" />
		<?php echo $this->Theme->get_theme_dynamic_background_style($theme_config); ?>
	</head>
	<body>		
		<div id="side_menu_bg"></div>
		<?php echo $this->Element('nameTitle'); ?>
		<?php //echo $this->Element('newsLetter'); ?>
		<div class="galleryContent">
			<div class="galleryContentInner">
				<br />
				<h1><?php echo "<b>",$curr_gallery['PhotoGallery']['display_name'],"</b>"; ?></h1>
				<p><?php echo $curr_gallery['PhotoGallery']['description']; ?><br /></p>
				<img src="/images/misc/horiz_gradientline.png" alt="" />
					<?php if (count($photos) > 0): ?>
						<?php echo $this->Element('gallery/pagination_links', array('extra_css' => 'margin-top: 10px; margin-bottom: 10px;')); ?>
						<?php echo $this->Element('gallery/gallery_image_lists/2_column', array('gallery_id' => $curr_gallery['PhotoGallery']['id'], 'photos' => $photos, 'top_message' => __('click on a thumbnail image to enlarge . . .', true), 'image_max_size' => 185)); ?>
						<?php echo $this->Element('gallery/pagination_links', array('extra_css' => 'margin-top: 10px; margin-bottom: 10px;')); ?>
					<?php else: ?>
						<h4 style="font-weight: bold; font-style: italic; margin: 10px;"><?php __('This gallery does not have any images yet'); ?></h4><?php // DREW TODO - make this seccion look good ?>
					<?php endif; ?>
				<img src="/images/misc/horiz_gradientline.png" alt="" />
				<?php echo $this->Element('global_theme_footer_copyright'); ?>
			</div>
		</div>
		
		<div id="navChain" class="lowercase">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/site_pages/landing_page">home</a>&nbsp;>&nbsp;<a href="/photo_galleries/choose_gallery">image galleries</a>&nbsp;>&nbsp;<?php print ("{$curr_gallery['PhotoGallery']['display_name']}\n"); ?>
			<img style="padding-top: 8px;" src="/images/misc/horiz_gradientline.png" alt="" />
		</div>
		
		<p id="sideBlurb"><b>To purchase a print, navigate to an image and add to cart.</b><br /><br/>
		</p>
		
		<?php echo $this->Element('menu/navBar', array( 'page' => 'gallery' )); ?>
	</body>
</html>
