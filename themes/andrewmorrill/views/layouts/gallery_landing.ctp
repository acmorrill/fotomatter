<!DOCTYPE html>
<html>
	<head>
		<title>Choose Gallery &mdash; <?php echo $this->Theme->get_frontend_html_title(); ?></title>
<!--		<meta name="keywords" content="Andrew Morrill, online gallery, fine art, utah photography, utah photography, National Park, Utah, California, LDS temples, temple photography">
		<meta name="description" content="The online gallery of Utah based photographer Andrew Morrill.">-->
		<?php echo $this->Element('theme_global_includes'); ?>
		<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/andrewmorrill_style.css" />
		<link rel="stylesheet" type="text/css" href="/stylesheets/contentReadableBackground.css" />
		<?php echo $this->Theme->get_theme_dynamic_background_style($theme_config); ?>
	</head>
	<body>
		<div id="side_menu_bg"></div>
		<?php echo $this->Element('nameTitle'); ?>
		<?php //echo $this->Element('newsLetter'); ?>
		<div class="galleryContent">
			<div class="galleryContentInner">
				<br /><br />

				<div class="portfolioLinks">
					<h2><b><?php __('Choose a Gallery'); ?></b></h2>
					<?php $all_galleries = $this->Gallery->get_all_galleries(); ?>
					<?php foreach ($all_galleries as $curr_gallery): ?>
						<?php 
							$curr_gallery_href = $this->Html->url(array(    
								'controller' => 'photo_galleries',    
								'action' => 'view_gallery',    
								$curr_gallery['PhotoGallery']['id']
							));
						?>
						<a href="<?php echo $curr_gallery_href; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></a><br />
					<?php endforeach; ?>
					<br/><br/><br/>
				</div>
				<br />
				<img src="/images/misc/horiz_gradientline.png" alt="" />
				<?php echo $this->Element('global_theme_footer_copyright'); ?>
			</div>
		</div>
		
		<p id="sideBlurb"><b>To purchase a print, navigate to an image and add to cart.</b><br /><br/></p>
		
		
		
		
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