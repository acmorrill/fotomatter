<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $this->Theme->get_frontend_html_title(); ?></title>
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/css/white_angular_style.css" />
		<?php $this->Util->replace_php_closure_includes('themes/white_angular/php_closure/white_angular.php'); ?>
		<?php
			$landing_page_slideshow_interval_time = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_slideshow_interval_time'); 
			$landing_page_slideshow_transition_time = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_slideshow_transition_time'); 
			$landing_page_slideshow_max_images = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_slideshow_max_images'); 
		?>
	</head>
	<body>
		<?php echo $this->Element('nameTitle'); ?>
		<?php echo $this->Element('menu/navBar'); ?>
		<script type="text/javascript">
			var scroll_to_height = 257;
			var autoscroll_interval;
			
			jQuery(window).load(function() {
				jQuery('#image_slider_container .float_image_cont.actual_image').click(function() {
					clearInterval(autoscroll_interval);
					scroll_to_image(jQuery(this), 300, false, true);
				});
				
				jQuery('#image_slider_container .float_image_cont.fake_image').click(function() {
					clearInterval(autoscroll_interval);
					var photo_id = jQuery(this).attr('photo_id');
					var real_image = jQuery('#image_slider_container .float_image_cont.actual_image[photo_id='+photo_id+']');
					var scroll_from = undefined;
					if (jQuery(this).hasClass('before')) {
						scroll_from = real_image.prev();
					} else {
						scroll_from = real_image.next();
					}
					scroll_to_image(scroll_from, 0, true, true);
					scroll_to_image(real_image, 300, false, true);
				});
				
				var scrolling_time = <?php echo $landing_page_slideshow_transition_time; ?>;
				var delay_between_scrolls = <?php echo $landing_page_slideshow_interval_time; ?>;
				
				setTimeout(function() {
					scroll_to_next_image(true, scrolling_time);
					autoscroll_interval = setInterval(function () {
						scroll_to_next_image(true, scrolling_time);
					}, scrolling_time + delay_between_scrolls);
				}, 1000);
			});
		</script>
		
		<div id="image_slider_outer_container">
			<div id="slider_info_container">
				<?php //<img class="scroll_up_right" src="/img/scroll_up_right.png" alt="" /> ?>
				<div class="top_info_line">&nbsp;</div>
				<div class="welcome_info_line">
					<div class="content">
						<h2><?php __('WELCOME'); ?></h2>
						<div class="thick_line"></div>
					</div>
					<div class="line"></div>
					<?php $intro_text = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_into_text'); ?>
					<?php if (!empty($intro_text)): ?>
						<div id="intro_text"><p><?php echo strip_tags($intro_text); ?></p></div>
					<?php endif; ?>
				</div>
				<?php //<img class="scroll_down_left" src="/img/scroll_down_left.png" alt="" /> ?>
			</div>
			<div id="image_slider_container">
				<?php 
					$gallery_to_use_id = $this->Util->get_not_empty_theme_setting_or($theme_custom_settings, 'landing_page_gallery', null);
					$photos = $this->Theme->get_landing_page_slideshow_images($landing_page_slideshow_max_images, $gallery_to_use_id, true);
					
					$this->WhiteAngular->process_photos_for_angular_slide($photos);
				?>
				<?php foreach ($photos as &$photo): ?>
					<?php  
						extract($this->WhiteAngular->process_angular_photo_data($photo));
						//compact('blank', 'img_src', 'alt_img_src', 'total_width', 'total_height', 'alt_total_width', 'alt_total_height', 'distance_to_close', 'cover_width_left', 'cover_width_right', 'left');
					?>
					<div photo_id="<?php if (isset($photo['Photo']['id'])) { echo $photo['Photo']['id']; } ?>" class="float_image_cont <?php echo implode(' ', $photo['classes']); ?>" style="width: 720px; height: 310px; left: <?php echo $left; ?>px;" start_left="<?php echo $left; ?>" img_width="<?php echo $total_width; ?>" img_height="<?php echo $total_height; ?>">
						<?php if (isset($alt_img_src)): ?>
							<div class="img_outer_cont when_closed">
								<div class="img_cont" style="width: <?php echo $alt_total_width; ?>px; height: <?php echo $alt_total_height; ?>px; margin-left: <?php echo -floor($alt_total_width/2); ?>px; margin-top: <?php echo -floor($alt_total_height/2); ?>px;">
									<div class="img_inner_wrap">
										<img class="preload_for_progress" actual_src="<?php echo $alt_img_src['url']; ?>" src="<?php echo $alt_img_src['url']; ?>" style="display: block; width: <?php echo $alt_img_src['width']; ?>px; height: <?php echo $alt_img_src['height']; ?>px;" <?php echo $alt_img_src['tag_attributes']; ?> alt="" />
									</div>
								</div>
							</div>
						<?php else: ?>
							<div class="img_outer_cont">
								<div class="img_cont" style="width: <?php echo $total_width; ?>px; height: <?php echo $total_height; ?>px; margin-left: <?php echo -round($total_width/2); ?>px; margin-top: <?php echo -round($total_height/2); ?>px;">
									<div class="img_inner_wrap">
										<img class="preload_for_progress" actual_src="<?php echo $img_src['url']; ?>" src="<?php echo $img_src['url']; ?>" style="display: block; width: <?php echo $img_src['width']; ?>px; height: <?php echo $img_src['height']; ?>px;" <?php echo $img_src['tag_attributes']; ?> alt="" />
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
		<?php echo $this->Element('global_theme_footer_copyright', array(
			'classes' => array( 'fixed_position' )
		)); ?>
	</body>
</html>