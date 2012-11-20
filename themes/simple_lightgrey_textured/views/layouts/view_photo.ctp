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
		/* DREW TODO - move this code into the css for the theme */
		#largePhotoPos {
			width: 892px;
			margin-top: 20px;
		}
		#largePhotoPos .image_navigation {
			float: right;
			margin-bottom: 15px;
			margin-right: 73px;
		}
		#largePhotoPos .image_navigation a {
			text-decoration: none;
			margin-right: 8px;
			opacity:0.7;
			filter:alpha(opacity=70); /* For IE8 and earlier */
		}
		#largePhotoPos .image_navigation a:hover {
			opacity:1;
			filter:alpha(opacity=100); /* For IE8 and earlier */
		}
		#largePhotoPos #mainImage {
			border: 18px solid #FFF;
			display: inline-block;
			background-color: #E6E6E6;
			vertical-align: middle;
			-moz-box-shadow: 5px 5px 10px rgba(51,51,51,0.5);
			-webkit-box-shadow: 5px 5px 10px rgba(51,51,51,0.5);
			box-shadow: 5px 5px 10px rgba(51,51,51,0.5);
			/* For IE 8 */
			-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=2, Direction=135, Color='#333333')"; /* DREW TODO - test this in IE8 */
			/* For IE 5.5 - 7 */
			filter: progid:DXImageTransform.Microsoft.Shadow(Strength=2, Direction=135, Color='#333333'); /* DREW TODO - test this in IE 6 */
		}
		#largePhotoPos #mainImage img {
			display: inline-block;
			vertical-align: middle;
		}
	</style>
	
	<div style="clear: both;"></div>
	<?php $is_pano = $curr_photo['PhotoFormat']['ref_name'] == "panoramic"; ?>
	<div id="largePhotoPos">
		<?php $img_src = $this->Photo->get_photo_path($curr_photo['Photo']['id'], 700, 700, .4, true); ?>

		<div class="image_navigation">
			<?php $prev_image_web_path = $this->Photo->get_prev_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
			<a class="photo_page_nav prev_image arrow <?php if ($is_pano): ?> is_pano<?php endif; ?>" href="<?php echo $prev_image_web_path; ?>">
				<img src="/img/arrowLeft.png" />
			</a>
			<?php $next_image_web_path = $this->Photo->get_next_image_web_path($curr_photo['Photo']['id'], $curr_gallery['PhotoGallery']['id']); ?>
			<a class="photo_page_nav next_image arrow <?php if ($is_pano): ?> is_pano<?php endif; ?>" href="<?php echo $next_image_web_path; ?>">
				<img src="/img/arrowRight.png" />
			</a>
		</div>
		<div style="clear: both;"></div>
		<div id="mainImage">
			<img src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> alt="<?php echo $curr_photo['Photo']['alt_text']; ?>" />
		</div>
	</div>

</div>

<p>&nbsp; </p>

</body>
</html>