<!DOCTYPE html>
<?php

	// possible bugs
	// 4) use less space at the top
	// 7) figure out forward and back when viewing images
	// 8) bug for going to second photo when only one gallery
	// 9) sometimes the styled scroll doesn't show because the thing was hidden when the scroll intialized
?>

<html>
	<head>
		<title>Photography by Andrew Morrill</title>
		<meta name="keywords" content="Andrew Morrill, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/css/white_angular_style.css" />
		<link href='http://fonts.googleapis.com/css?family=Signika+Negative:300' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/js/jscrollpane/jquery.jscrollpane.css" />
		<script src="/js/jscrollpane/jquery.mousewheel.js"></script>
		<script src="/js/jscrollpane/jquery.jscrollpane.min.js"></script>
		<script src="/js/angular_functions.js"></script>
	</head>
	<body>
<!--		<div style="width: 650px; height: 100px; z-index: 3000; position: fixed; outline: 1px solid orange;"></div>-->
		
		<?php echo $this->Element('nameTitle'); ?>
		
		<?php echo $this->Element('menu/navBar'); ?>
		
		<script type="text/javascript">
			var scroll_to_height = 159;
			
			jQuery(document).ready(function() {
				// reveal the images when they are loaded
				jQuery(document).bind('images_loaded', function() {
					setTimeout(function() {
						var second_to_last_image = jQuery('#image_slider_container .float_image_cont.first').prev();
						open_image(second_to_last_image, 700);
					}, 20);
					
				});
				
				jQuery('.scroll_up_right').click(function() {
					scroll_to_next_image(false);
				});
				
				jQuery('.scroll_down_left').click(function() {
					scroll_to_prev_image(false);
				});
				
				jQuery('#image_slider_container .float_image_cont.actual_image').click(function() {
					// fail on any animation
					if ($(':animated').length) {
						return false;
					}
					
					scroll_to_image(jQuery(this), 0);
				});
				
				jQuery('#image_slider_container .float_image_cont.fake_image').click(function() {
					// fail on any animation
					if ($(':animated').length) {
						return false;
					}
					
					var photo_id = jQuery(this).attr('photo_id');
					var real_image = jQuery('#image_slider_container .float_image_cont.actual_image[photo_id='+photo_id+']');
					var scroll_from = undefined;
					if (jQuery(this).hasClass('before')) {
						scroll_from = real_image.prev();
					} else {
						scroll_from = real_image.next();
					}
					scroll_to_image(scroll_from, 0, true);
					scroll_to_image(real_image, 0);
				});
				
				jQuery('#image_slider_outer_container #image_slider_container .float_image_cont .img_outer_cont .curr_image_info_cont .photo_description').jScrollPane();
			});
		</script>
		
		<div id="image_slider_outer_container">
<!--			<div id="slider_info_container">
				<img class="scroll_up_right" src="/img/scroll_up_right.png" />
				<div class="top_info_line">&nbsp;</div>
				<div class="welcome_info_line">
					<div class="content">
						<h2><?php __('WELCOME'); ?></h2>
						<div class="thick_line"></div>
					</div>
					<div class="line"></div>
				</div>
				<img class="scroll_down_left" src="/img/scroll_down_left.png" />
			</div>-->
			<div id="image_slider_container">
				<?php 
				
					if (!isset($photos)) {
						// treat the landing page as the first gallery
						$curr_gallery = $this->Gallery->get_first_gallery(); 
						if (isset($curr_gallery['PhotoGallery']['id'])) {
							$gallery_id = $curr_gallery['PhotoGallery']['id'];
						} else {
							$gallery_id = 0;
						}
						$photos = $this->Gallery->get_gallery_photos($gallery_id, 200);
					}
					
					
					$this->WhiteAngular->process_photos_for_angular_slide($photos);
				?>
				<?php foreach ($photos as &$photo): ?>
					<?php  
						extract($this->WhiteAngular->process_angular_photo_data($photo));
						//compact('blank', 'img_src', 'alt_img_src', 'total_width', 'total_height', 'alt_total_width', 'alt_total_height', 'distance_to_close', 'cover_width_left', 'cover_width_right', 'left');
					?>
					<div photo_id="<?php if (isset($photo['Photo']['id'])) { echo $photo['Photo']['id']; } ?>" class="float_image_cont <?php echo implode(' ', $photo['classes']); ?>" style="width: 720px; height: 310px; left: <?php echo $left; ?>px;" start_left="<?php echo $left; ?>" img_width="<?php echo $total_width; ?>" img_height="<?php echo $total_height; ?>">
						<div class="img_outer_cont <?php if (isset($alt_img_src)): ?>when_open<?php endif; ?>">
							<div class="curr_image_info_cont">
								<img class="left_arrow" src="/img/left_arrow.png" alt="" />
								<h2><?php echo $photo['Photo']['display_title']; ?></h2>
								<?php if (!empty($photo['Photo']['display_subtitle'])): ?>
									<h3><?php echo $photo['Photo']['display_subtitle']; ?></h3>
								<?php endif; ?>
								<div class="thick_line"></div>
								<?php if (!empty($photo['Photo']['description'])): ?>
									<div class="photo_description"><p><?php echo $photo['Photo']['description']; ?></p></div>
								<?php endif; ?>
								<div class="line"></div>
								<img style="margin-top: 20px;" src="/img/fake_buttons.png" alt="" />
							</div>
							<div class="img_cont" style="width: <?php echo $total_width; ?>px; height: <?php echo $total_height; ?>px; margin-left: <?php echo -floor($total_width/2); ?>px; margin-top: <?php echo -floor($total_height/2); ?>px;">
								<div class="img_inner_wrap">
									<img src="<?php echo $img_src['url']; ?>" style="display: block; width: <?php echo $img_src['width']; ?>px; height: <?php echo $img_src['height']; ?>px;" <?php echo $img_src['tag_attributes']; ?> alt="" />
								</div>
							</div>
						</div>
						<?php if (isset($alt_img_src)): ?>
							<div class="img_outer_cont when_closed">
								<div class="img_cont" style="width: <?php echo $alt_total_width; ?>px; height: <?php echo $alt_total_height; ?>px; margin-left: <?php echo -floor($alt_total_width/2); ?>px; margin-top: <?php echo -floor($alt_total_height/2); ?>px;">
									<div class="img_inner_wrap">
										<img src="<?php echo $alt_img_src['url']; ?>" style="display: block; width: <?php echo $alt_img_src['width']; ?>px; height: <?php echo $alt_img_src['height']; ?>px;" <?php echo $alt_img_src['tag_attributes']; ?> alt="" />
									</div>
								</div>
							</div>
						<?php endif; ?>
						<div class="left_cover_image" open_left="<?php echo $cover_width_left; ?>" style="left: <?php echo $cover_width_left + $distance_to_close; ?>px;">
							<div class="one">&nbsp;</div>
							<div class="two">&nbsp;</div>
							<div class="three">&nbsp;</div>
							<div class="four">&nbsp;</div>
						</div>
						<div class="right_cover_image" open_left="<?php echo $cover_width_right; ?>" style="left: <?php echo $cover_width_right - $distance_to_close; ?>px;">
							<div class="one">&nbsp;</div>
							<div class="two">&nbsp;</div>
							<div class="three">&nbsp;</div>
							<div class="four">&nbsp;</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<div id="images_loading_tab">
				<div id="progress_bar">
					<div class="percent_text"><span>0</span>%</div>
					<div class="ui-progressbar-value">
					</div>
				</div>
			</div>
		</div>
	</body>
</html>