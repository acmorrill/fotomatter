<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<?php echo $this->Element('theme_global_includes'); ?>


	<link rel='stylesheet' type='text/css' href='/css/quickmenu_styles.css'/>
	<script type='text/javascript' src='/js/quickmenu2.js'></script>
	<title>Photographs by Joe Smo</title>
	<script type="text/javascript"></script>
	<link href="/css/index1.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
	<!--

	-->
	</style>
	
	
	
</head>

<body>
<div class="container">
	<?php echo $this->Element('nameTitle'); ?>
  
	<?php echo $this->Element('temp_menu'); ?>

	
	<style type="text/css">
		#grey_textured_gallery_listing_container {
			
		}
	</style>
	<div style="clear: both"></div>
	<div id="grey_textured_gallery_listing_container">
		<?php if (count($photos) > 0): ?>
			<?php echo $this->Element('gallery/pagination_links', array('extra_css' => 'margin-top: 10px; margin-bottom: 10px; float: right;')); ?>
			<?php echo $this->Element('gallery/gallery_image_lists/4_column', array('gallery_id' => $curr_gallery['PhotoGallery']['id'], 'photos' => $photos, 'image_max_size' => 185)); ?>
			<?php echo $this->Element('gallery/pagination_links', array('extra_css' => 'margin-top: 10px; margin-bottom: 10px; float: right;')); ?>
		<?php else: ?>
			<h4 style="font-weight: bold; font-style: italic; margin: 10px;"><?php __('This gallery does not have any images yet'); ?></h4><?php // DREW TODO - make this seccion look good ?>
		<?php endif; ?>
	</div>

</div>

<p>&nbsp; </p>

</body>
</html>