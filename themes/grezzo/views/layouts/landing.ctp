<!DOCTYPE html>
<html>
	<head>
		<title>Photography by Andrew Morrill</title>
		<meta name="keywords" content="Andrew Morrill, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/css/grezzo.css" />
		<link href='http://fonts.googleapis.com/css?family=Signika+Negative:300' rel='stylesheet' type='text/css'>
		
		<script type='text/javascript' src='/jquery/jcarousel-core.min.js'></script>
		<script type='text/javascript' src='/jquery/jcarousel-control.min.js'></script>
	</head>
	<body>
		<?php 
			if (!isset($photos)) {
				// treat the landing page as the first gallery
				$curr_gallery = $this->Gallery->get_first_gallery(); 
				if (isset($curr_gallery['PhotoGallery']['id'])) {
					$gallery_id = $curr_gallery['PhotoGallery']['id'];
				} else {
					$gallery_id = 0;
				}
				$photos = $this->Gallery->get_gallery_photos($gallery_id, 15);
			}
		?>
		
		<div id='outer_nav'>
			<?php echo $this->Element('nameTitle'); ?>
			<?php echo $this->Element('menu/two_level_navbar'); ?>
		</div><br style='clear:both' />
	
		<div id='gallary-container'>
			<div class='jcarousel-wrapper'>
				<div class='jcarousel'>
					<?php echo $this->Element('gallery/gallery_image_lists/background_ul_list', array(
							'photos' => $photos,
							'height' => '500',
							'width' => '2000',
							'sharpness' => '.4'
					)); ?>
				</div>
			</div>
		</div>
		
		<a href="#" class="jcarousel-control-prev">&lsaquo;</a>
                <a href="#" class="jcarousel-control-next">&rsaquo;</a>

		<script type='text/javascript'>
			$(document).ready(function() {
				var photos_on_page = $("#gallary-container ul li").length;
				var middle_photo = Math.floor(photos_on_page / 2) - 1;
				
				$('.jcarousel').jcarousel({
					center: true,
					transitions: true
				}).
				jcarousel('scroll', middle_photo);
				
				$('.jcarousel-control-next')
					.on('jcarouselcontrol:active', function() {
						$(this).removeClass('inactive');
					})
					.on('jcarouselcontrol:inactive', function() {
						$(this).addClass('inactive');
					})
					.jcarouselControl({
						target: '+=1'
					});
					
				$('.jcarousel-control-prev')
					.on('jcarouselcontrol:active', function() {
						$(this).removeClass('inactive');
					})
					.on('jcarouselcontrol:inactive', function() {
						$(this).addClass('inactive');
					})
					.jcarouselControl({
						target: '-=1'
					});
					
				
			});
		</script>
</body>
</html>