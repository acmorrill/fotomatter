<!DOCTYPE html>
<?php 
//	if(!isset($_SESSION)) { 
//		session_start();	
//	} 
?>
<html>
	<head>
		<title>Picture Gallery -- Celestial Light Photography</title>
		<meta name="keywords" content="Andrew Morrill, online gallery, fine art, utah photography, utah photography, National Park, Utah, California, LDS temples, temple photography">
		<meta name="description" content="The online gallery of Utah based photographer Andrew Morrill.">
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/css/stylesheet.css" />
		<link rel="stylesheet" type="text/css" href="/stylesheets/contentReadableBackground.css" />
	<script type="text/javascript">
		function setImage( id, path ) {
			var image = document.getElementById(id);
			image.src=src=path;
		}
	</script>
	</head>
	<body>
		<?php echo $this->Element('nameTitle'); ?>
		<?php echo $this->Element('newsLetter'); ?>
		<div class="galleryContent">
			<br /><br />
			
			<div class="portfolioLinks">
				<h2><b><?php __('Choose a Gallery'); ?></b></h2>
				<img id="portfolioThumb" src="/photos/portfolioThumbs/A-Tangerine-Blue.jpg" />
				<?php $all_galleries = $this->Gallery->get_all_galleries(); ?>
				<?php foreach ($all_galleries as $curr_gallery): ?>
					<?php 
						$curr_gallery_href = $this->Html->url(array(    
							'controller' => 'photo_galleries',    
							'action' => 'view_gallery',    
							$curr_gallery['PhotoGallery']['id']
						));
					?>
					<a onmouseover="setImage('portfolioThumb', '/photos/portfolioThumbs/A-Tangerine-Blue.jpg');" href="<?php echo $curr_gallery_href; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></a><br />
				<?php endforeach; ?>
				<?php /*<a onmouseover="setImage('portfolioThumb', '/photos/portfolioThumbs/Celestial-ArrayBW.jpg');" href="/photo_galleries/view_gallery?gallery=largeFormatBW">Black &amp; White Landscapes</a><br />
				<a onmouseover="setImage('portfolioThumb', '/photos/portfolioThumbs/Solar-Migrations.jpg');" href="/photo_galleries/view_gallery?gallery=panoramics">Panoramic Landscapes</a><br />
				<a onmouseover="setImage('portfolioThumb', '/photos/portfolioThumbs/Provo-Temple-Winter.jpg');" href="/photo_galleries/view_gallery?gallery=temples">LDS Temple Pictures</a><br />*/ ?>
				<br/><br/><br/>
			</div>
			<br />
			<img src="/images/misc/horiz_gradientline.png">
			<?php echo $this->Element('footer'); ?>
		</div>
		
		<p id="sideBlurb"><b>To purchase a print, navigate to an image and add to cart.</b><br /><br/>Before viewing images, consider checking out the <a href="viewingTips.php">viewing tips page</a>.<br />
		</p>
		
		
		
		
		<?php 
			// DREW TODO - change this to use cakephp html helper getCrumbs
			echo $this->Element('nav_chain', array( 
				'avail_pages' => array(
					array(
						'text' => 'image galleries',
						'url' => '/photo_galleries/choose_gallery'
					)
				)
			)); 
		?>
		
		
		<?php echo $this->Element('menu/navBar', array( 'page' => 'gallery' )); ?>
<?php
//			include("php/googleAnalytics.php");
?>		
	</body>
</html>