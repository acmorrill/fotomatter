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
		<title><?php echo $curr_gallery['PhotoGallery']['display_name']; ?> &mdash; <?php echo $this->Theme->get_frontend_html_title(); ?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/css/white_angular_style.css" />
		<script src="/js/php_closure/white_angular.min.js"></script>
	</head>
	<body>
<!--		<div style="width: 650px; height: 100px; z-index: 3000; position: fixed; outline: 1px solid orange;"></div>-->
		
		<?php echo $this->Element('nameTitle'); ?>
		
		<?php echo $this->Element('menu/navBar'); ?>
		
		<script type="text/javascript">
			var scroll_to_height = 159;
			
			jQuery(document).ready(function() {
				// reveal the images when they are loaded
				jQuery(document).bind('images_loaded', function(e) {
					setTimeout(function() {
						var first_image = jQuery('#image_slider_container .float_image_cont.first');
						var second_to_last_image = first_image.prev();
						open_image(second_to_last_image, 700);
						
						jQuery('#image_slider_container .float_image_cont.actual_image').click(function() {
							if (jQuery(this).hasClass('open_image')) {
								return false;
							}
							
							// fail on any animation
							if ($(':animated').length) {
								return false;
							}

							scroll_to_image(jQuery(this), 0);
							
							if (jQuery(this).data('jscroll_init') == undefined) {
								jQuery(this).data('jscroll_init', true);
								var photo_description = jQuery('.img_outer_cont .curr_image_info_cont .photo_description', this);
								if (photo_description.length > 0) {
									var jscrollpane = photo_description.data('jsp');
									jscrollpane.reinitialise();
								}
							}
						});

						jQuery('#image_slider_container .float_image_cont.fake_image').click(function() {
							if (jQuery(this).hasClass('open_image')) {
								return false;
							}
							
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
							
							if (jQuery(this).data('jscroll_init') == undefined) {
								jQuery(this).data('jscroll_init', true);
								var photo_description = jQuery('.img_outer_cont .curr_image_info_cont .photo_description', this);
								if (photo_description.length > 0) {
									var jscrollpane = photo_description.data('jsp');
									jscrollpane.reinitialise();
								}
							}
						});
						
					}, 20);
				});
				
				
				jQuery('#image_slider_outer_container #image_slider_container .float_image_cont .img_outer_cont .curr_image_info_cont .photo_description').jScrollPane();
			});
			
			
		</script>
		
		<div id="image_slider_outer_container">
			<div id="image_slider_container">
				<?php $this->WhiteAngular->process_photos_for_angular_slide($photos); ?>
				<?php $count = 0; foreach ($photos as &$photo): ?>
					<?php  
						extract($this->WhiteAngular->process_angular_photo_data($photo));
						//compact('blank', 'img_src', 'alt_img_src', 'total_width', 'total_height', 'alt_total_width', 'alt_total_height', 'distance_to_close', 'cover_width_left', 'cover_width_right', 'left');
					?>
					<div count="<?php echo $count; ?>" photo_id="<?php if (isset($photo['Photo']['id'])) { echo $photo['Photo']['id']; } ?>" class="float_image_cont <?php echo implode(' ', $photo['classes']); ?>" style="width: 720px; height: 310px; left: <?php echo $left; ?>px;" start_left="<?php echo $left; ?>" img_width="<?php echo $total_width; ?>" img_height="<?php echo $total_height; ?>">
						<div class="img_outer_cont <?php if (isset($alt_img_src)): ?>when_open<?php endif; ?>">
							<div class="curr_image_info_cont">
								<?php if (!empty($photo['Photo'])): ?>
									<img class="left_arrow" src="/img/left_arrow.png" alt="" />
									<h2><?php echo $photo['Photo']['display_title']; ?></h2>
									<?php if (!empty($photo['Photo']['display_subtitle'])): ?>
										<h3><?php echo $photo['Photo']['display_subtitle']; ?></h3>
									<?php endif; ?>
									<?php if (!empty($photo['Photo']['date_taken'])): ?>
										<?php $phpdate = strtotime($photo['Photo']['date_taken']); ?>
										<h4><?php echo date("F Y", $phpdate); ?></h4>
									<?php endif; ?>

									<div class="thick_line"></div>
									<?php if (!empty($photo['Photo']['description'])): ?>
										<div class="photo_description"><p><?php echo $photo['Photo']['description']; ?></p></div>
									<?php endif; ?>
									<div class="line"></div>
									<?php echo $this->Element('cart_checkout/compact_image_add_to_cart_form_simple', array(
										'photo_id' => $photo['Photo']['id'],
									)); ?>
									<?php /* <img style="margin-top: 20px;" src="/img/fake_buttons.png" alt="" /> // DREW TODO - style the buttons as in this image */ ?>
								<?php endif; ?>
							</div>
							<div class="img_cont" style="width: <?php echo $total_width; ?>px; height: <?php echo $total_height; ?>px; margin-left: <?php echo -floor($total_width/2); ?>px; margin-top: <?php echo -floor($total_height/2); ?>px;">
								<div class="img_inner_wrap">
									<img class="preload_for_progress" src="<?php echo $img_src['url']; ?>" style="display: block; width: <?php echo $img_src['width']; ?>px; height: <?php echo $img_src['height']; ?>px;" <?php echo $img_src['tag_attributes']; ?> alt="" />
								</div>
							</div>
						</div>
						<?php if (isset($alt_img_src)): ?>
							<div class="img_outer_cont when_closed">
								<div class="img_cont" style="width: <?php echo $alt_total_width; ?>px; height: <?php echo $alt_total_height; ?>px; margin-left: <?php echo -floor($alt_total_width/2); ?>px; margin-top: <?php echo -floor($alt_total_height/2); ?>px;">
									<div class="img_inner_wrap">
										<img class="preload_for_progress" src="<?php echo $alt_img_src['url']; ?>" style="display: block; width: <?php echo $alt_img_src['width']; ?>px; height: <?php echo $alt_img_src['height']; ?>px;" <?php echo $alt_img_src['tag_attributes']; ?> alt="" />
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
				<?php $count++; endforeach; ?>
			</div>
			<div id="images_loading_tab">
				<div id="progress_bar">
					<div class="percent_text"><span>0</span>%</div>
					<div class="ui-progressbar-value">
					</div>
				</div>
			</div>
		</div>
		<?php echo $this->Element('global_theme_footer_copyright', array(
			'classes' => array( 'fixed_position' )
		)); ?>
	</body>
</html>