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
  
	<?php //echo $this->Element('temp_menu'); ?>
	<?php echo $this->Element('two_level_navbar'); ?>

	
	<style type="text/css">
		#grey_textured_gallery_listing_container {
			
		}
		/* DREW TODO - move this into global css somewhere -- also make it used in other listing layouts */
		#gallery_list_table {
		}
		#gallery_list_table tr td {
		}
		#gallery_list_table tr td .galleries .gallery_image_outer_cont {
			border: 1px solid #D0D0D0;
			display: inline-block;
			-moz-box-shadow: 3px 3px 10px rgba(51,51,51,0.5);
			-webkit-box-shadow: 3px 3px 10px rgba(51,51,51,0.5);
			box-shadow: 3px 3px 10px rgba(51,51,51,0.5);
			/* For IE 8 */
			-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=2, Direction=135, Color='#333333')"; /* DREW TODO - test this in IE8 */
			/* For IE 5.5 - 7 */
			filter: progid:DXImageTransform.Microsoft.Shadow(Strength=2, Direction=135, Color='#333333'); /* DREW TODO - test this in IE 6 */
			background-color: #E6E6E6;
		}
		#gallery_list_table tr td .galleries .gallery_image_outer_cont .gallery_image_a_link {
			border: 4px solid #FFF;
			display: inline-block;
		}
		#gallery_list_table tr td .galleries .galleriesLink {
			margin-top: 10px;
			color: #E7E7E7;
			font-weight: bold;
			font-size: 11px;
			font-family: Geneva, Arial, Helvetica, sans-serif;
		}
		#gallery_list_table tr td .galleries .galleriesLink a {
			color: #E7E7E7;
			text-decoration: none;
		}
		#grey_textured_gallery_listing_container .paginationDiv {
			color: #FFF;
			font-size: 12px;
			font-weight: bold;
			font-family: "Times New Roman", Times, serif;
		}
		#grey_textured_gallery_listing_container .paginationDiv a {
			color: #FFF;
		}
	</style>
	
	<div style="clear: both"></div>
	<div id="grey_textured_gallery_listing_container">
		<?php if (count($photos) > 0): ?>
			<?php echo $this->Element('gallery/pagination_links', array('extra_css' => 'margin-top: 10px; margin-bottom: 10px; float: right;')); ?>
			<?php echo $this->Element('gallery/gallery_image_lists/4_column', array('gallery_id' => $curr_gallery['PhotoGallery']['id'], 'photos' => $photos, 'image_max_size' => 179)); ?>
			<?php echo $this->Element('gallery/pagination_links', array('extra_css' => 'margin-top: 10px; margin-bottom: 10px; float: right;')); ?>
		<?php else: ?>
			<h4 style="font-weight: bold; font-style: italic; margin: 10px;"><?php __('This gallery does not have any images yet'); ?></h4><?php // DREW TODO - make this seccion look good ?>
		<?php endif; ?>
	</div>

</div>

<p>&nbsp; </p>

</body>
</html>