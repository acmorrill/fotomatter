<!DOCTYPE html>
<html>
	<head>
		<title>Choose Gallery &mdash; <?php echo $this->Theme->get_frontend_html_title(); ?></title>
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
				<br /><br />

				<div class="portfolioLinks">
					<h2><b><?php __('Choose a Gallery'); ?></b></h2>
					
					
					<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('.portfolioThumb').first().show();
							jQuery('.gallery_name_link').mouseover(function() {
								var gallery_id = jQuery(this).attr('data-gallery_id');
								jQuery('.portfolioThumb').hide();
								jQuery('.portfolioThumb.gallery_id_' + gallery_id).show();
							});
						});
					</script>
					
					
					<?php $all_galleries = $this->Gallery->get_all_galleries(); ?>
					<?php foreach ($all_galleries as $curr_gallery): ?>
						<?php
							$curr_gallery_href = $this->Html->url(array(
								'controller' => 'photo_galleries',
								'action' => 'view_gallery',
								$curr_gallery['PhotoGallery']['id']
							));
							$photo_id = $this->Gallery->get_gallery_photo_id($curr_gallery['PhotoGallery']['id']);
							$img_src = $this->Photo->get_photo_path($photo_id, 140, 300, .4, true);
						?>
						<img class="portfolioThumb gallery_id_<?php echo $curr_gallery['PhotoGallery']['id']; ?>" data-photo_id='<?php echo $photo_id; ?>' src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> />
						<a class='gallery_name_link' data-gallery_id='<?php echo $curr_gallery['PhotoGallery']['id']; ?>' href="<?php echo $curr_gallery_href; ?>"><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></a><br />
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