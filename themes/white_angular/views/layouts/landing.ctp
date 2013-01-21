<!DOCTYPE html>
<?php
	/*if(!isset($_SESSION)) { 
		session_start();	
	} 
	$_SESSION['cart'];*/
?>

<html>
	<head>
		<title>Photography by Andrew Morrill</title>
		<meta name="keywords" content="Andrew Morrill, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<?php echo $this->Element('theme_global_includes'); ?>
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
		<link href='http://fonts.googleapis.com/css?family=Signika+Negative:300' rel='stylesheet' type='text/css'>
	</head>
	<body>
<!--		<div style="width: 650px; height: 100px; z-index: 3000; position: fixed; outline: 1px solid orange;"></div>-->
		
		<?php echo $this->Element('nameTitle'); ?>
		
		<?php echo $this->Element('menu/navBar'); ?>
		
		<?php 
			if (!isset($photos)) {
				// treat the landing page as the first gallery
				$curr_gallery = $this->Gallery->get_first_gallery(); 
				if (isset($curr_gallery['PhotoGallery']['id'])) {
					$gallery_id = $curr_gallery['PhotoGallery']['id'];
				} else {
					$gallery_id = 0;
				}
				$photos = array_reverse($this->Gallery->get_gallery_photos($gallery_id, 200));
			}
		?>
		
		<script type="text/javascript">
			var scroll_to_height = 257;
			function scroll_to_image(image, speed) {
				if (image.hasClass('actual_image')) {
					// stop any current animation
					jQuery('#image_slider_container').stop();

					// set as the current image
					jQuery('#image_slider_container .float_image_cont').removeClass('current_image');				
					image.addClass('current_image');

					// scroll to the current image
					var image_pos = jQuery(image).position();
					var this_image_top = image_pos.top;
					var container_top = jQuery('#image_slider_container').position().top;

					var top_increase = -(this_image_top - Math.abs(container_top)) + scroll_to_height;
					var left_increase = Math.round((153 * top_increase) / -190);

					var top_str = (top_increase > 0) ? '+='+Math.abs(top_increase) : '-='+Math.abs(top_increase) ;
					var left_str = (left_increase > 0) ? '+='+Math.abs(left_increase) : '-='+Math.abs(left_increase) ;

					jQuery('#image_slider_container').stop().animate({
						top: top_str,
						left: left_str
					}, {queue: false, duration: speed});
				}
				
			}
			
			function scroll_to_next_image() {
				var current_image = jQuery('#image_slider_container .float_image_cont.current_image');
				var next_image = current_image.prev().prev();
				scroll_to_image(next_image, 300);
			}
			
			function scroll_to_prev_image() {
				var current_image = jQuery('#image_slider_container .float_image_cont.current_image');
				var prev_image = current_image.next().next();
				scroll_to_image(prev_image, 300);
			}
			
			var total_images = 0;
			var loaded_images = 0;
			function update_progress_bar() {
				var total_progress = Math.round((loaded_images / total_images) * 100);
				jQuery("#progress_bar .ui-progressbar-value").height(total_progress+'%');
				jQuery("#progress_bar .percent_text span").text(total_progress);
				if (total_progress == 100) {
					jQuery(document).trigger('images_loaded');
				}
			}
			
			function bootstrap() { 
				jQuery('#image_slider_outer_container').css({
					opacity: 100
				});
				
				// count how many of the images have loaded
				jQuery('#image_slider_container .img_cont img').each(function() {
					total_images++;
					var tmpImg = new Image() ;
					tmpImg.src = $(this).attr('src') ;
					tmpImg.onload = function() {
						loaded_images++;
						update_progress_bar();
					};
					tmpImg.error = function() {
						console.log ("error loading image");
						loaded_images++;
						update_progress_bar();
					};
				});
			}
			
			jQuery(document).ready(function() {
				jQuery('#image_slider_outer_container').css({
					opacity: 0
				});
			
				// reveal the images when they are loaded
				jQuery(document).bind('images_loaded', function() {
					jQuery('#images_loading_tab').hide();
					
					jQuery('#image_slider_container .left_cover_image, #image_slider_container .right_cover_image').each(function() {
						var left = jQuery(this).attr('open_left');

						jQuery(this).animate({
							left: left
						}, {queue: false, duration: 500});
					});
				});
				
				
				var bootstrap_count = 0;
				$('<img/>').attr('src', '/img/left_block_image.png').load(function() {
					bootstrap_count++;
					if (bootstrap_count >= 2) {
						bootstrap();
					}
				});
				$('<img/>').attr('src', '/img/right_block_image.png').load(function() {
					bootstrap_count++;
					if (bootstrap_count >= 2) {
						bootstrap();
					}
				});
				
				
				// find the second to last image and scroll to it at the beginning
				var second_to_last_image = jQuery('#image_slider_container .float_image_cont.actual_image:last').prev().prev();
				scroll_to_image(second_to_last_image, 0);
				
				jQuery('.scroll_up_right').click(function() {
					scroll_to_prev_image();
				});
				
				jQuery('.scroll_down_left').click(function() {
					scroll_to_next_image();
				});
				
				jQuery('#image_slider_container .float_image_cont').click(function() {
					scroll_to_image(jQuery(this), 300);
				});
				
				
				setTimeout(function() {
					
					
					// animate all images position
//					jQuery('#image_slider_container').animate({
//						top: '+=150',
//						left: '-=360'
//					}, {queue: false, duration: 1000});


//					jQuery('#image_slider_container').animate({
//						top: '-=190',
//						left: '+=153'
//					}, {queue: false, duration: 1000});
					
					
					// grow an image
//					jQuery('#image_slider_container .float_image_cont:eq(1)').each(function() {
//						jQuery(this).prev().find('.cover_image').hide();
//						jQuery(this).next().find('.cover_image').hide();
//						
//						var img_height = parseInt(jQuery(this).attr('img_height'));
//						var animation_time = 700;
//						
//						jQuery('.left_cover_image', this).animate({
//							left: '-=400'
//						}, {queue: false, duration: animation_time});
//						
//						jQuery('.right_cover_image', this).animate({
//							left: '+=400'
//						}, {queue: false, duration: animation_time});
//						
//						jQuery(this).animate({
//							height: img_height,
//							width: '+=20'
//						}, {queue: false, duration: animation_time});
//						
//						jQuery(this).nextAll().animate({
//							left: '-=150'
//						}, {queue: false, duration: animation_time});
//					});


				}, 2000);
			});
		</script>
		
		<div id="image_slider_outer_container">
			<div id="slider_info_container">
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
			</div>
			<div id="image_slider_container">
				<?php 
					$cover_width = 988; 
					$blank_cont_left_add = -121;
					$cont_left_add = -128;
					$prev_left = null;
					
					
					// add 4 three blank onto beginning and end
					array_unshift($photos, '');
					array_unshift($photos, '');
					array_unshift($photos, '');
					array_unshift($photos, '');
					array_push($photos, '');
					array_push($photos, '');
					array_push($photos, '');
					array_push($photos, '');
					
//					debug($photos);
					
				?>
				<?php foreach ($photos as $photo): ?>
					<?php 
						$blank = false;
						if (empty($photo)) {
							$blank = true;
							$width = '720';
							$height = '500';
							$img_src['url'] = '/img/blank_image.png';
							$img_src['width'] = $width;
							$img_src['height'] = $height;
							$img_src['tag_attributes'] = "width='$width' height='$height'";
						} else {
							$width = null;
							$height = null;

							switch ($photo['Photo']['PhotoFormat']['ref_name']) {
								case 'portrait':
								case 'square':
									$width = 630;
									$height = 3000;
									break;
								case 'landscape':
									$width = 720;
									$height = 3000;
									break;
								case 'panoramic':
									$height = 300;
									$width = 3000;
									break;
								case 'vertical_panoramic':
									$width = 300;
									$height = 3000;
									break;
							}
							$img_src = $this->Photo->get_photo_path($photo['Photo']['id'], $height, $width, .4, true); 
						}

					?>
						<?php 
							$total_width = $img_src['width'] + 20;
							$total_height = $img_src['height'] + 20;

							// figure out the position of the left cover
							$distance_from_middle = 74;
							$distance_to_close = 210;
							$cover_width_left = 360 - $distance_from_middle - $cover_width;
							$cover_width_right = 360 + $distance_from_middle;

							if (!isset($prev_left)) {
								$left = 0;
							} else {
								$left = $prev_left + $cont_left_add;
							}
							$prev_left = $left;

	//						$div_x = $this->WhiteAngular->get_image_center_x($div_y); 
	//						
	//						$using_x = $div_x - 360;
	//						$using_y = $div_y - 150;
	//						debug("x: $div_x, y: $div_y");
						?>
						<div class="float_image_cont <?php if ($blank == false): ?>actual_image<?php endif; ?>" style="width: 720px; height: 300px; left: <?php echo $left; ?>px;" start_left="<?php echo $left; ?>" img_width="<?php echo $total_width; ?>" img_height="<?php echo $total_height; ?>">
							<div class="img_cont" style="width: <?php echo $total_width; ?>px; height: <?php echo $total_height; ?>px; margin-left: <?php echo -round($total_width/2); ?>px; margin-top: <?php echo -round($total_height/2); ?>px;">
								<div class="img_inner_wrap">
									<img src="<?php echo $img_src['url']; ?>" style="display: block; width: <?php echo $img_src['width']; ?>px; height: <?php echo $img_src['height']; ?>px;" <?php echo $img_src['tag_attributes']; ?> />
								</div>
							</div>
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
						<?php
							$distance_from_middle = 74;
							$cover_width_left = 360 - $distance_from_middle - $cover_width;
							$cover_width_right = 360 + $distance_from_middle;

							$left = $prev_left + $blank_cont_left_add;
							$prev_left = $left;
						?>
						<div class="float_blank_cont" style="left: <?php echo $left; ?>px;">
							<div class="float_blank_inner_cont" style="width: 720px; height: 300px;">
								<div class="left_cover_image cover_image" open_left="<?php echo $cover_width_left; ?>" style="left: <?php echo $cover_width_left + $distance_to_close; ?>px;">
									<div class="one">&nbsp;</div>
									<div class="two">&nbsp;</div>
									<div class="three">&nbsp;</div>
									<div class="four">&nbsp;</div>
								</div>
								<div class="right_cover_image cover_image" open_left="<?php echo $cover_width_right; ?>" style="left: <?php echo $cover_width_right - $distance_to_close; ?>px;">
									<div class="one">&nbsp;</div>
									<div class="two">&nbsp;</div>
									<div class="three">&nbsp;</div>
									<div class="four">&nbsp;</div>
								</div>
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