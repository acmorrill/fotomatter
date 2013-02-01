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
		
		<script type="text/javascript">
			var scroll_to_height = 159;
			var image_slider_container = undefined;
			function scroll_to_image(image, speed, force, no_open) {
				if (force == undefined) {
					force = false;
				}
				
				if (no_open == undefined) {
					no_open = false;
				}
				
				
				if (image.hasClass('actual_image') || force == true) {
					if (image_slider_container === undefined) {
						image_slider_container = jQuery('#image_slider_container');
					}
					// stop any current animation
					image_slider_container.stop();

					// set as the current image
					close_image(jQuery('.current_image', image_slider_container), 150);
					jQuery('.float_image_cont', image_slider_container).removeClass('current_image');				
					image.addClass('current_image');
					if (no_open == false) {
						open_image(image, 300);
					}

					// scroll to the current image
					var image_pos = jQuery(image).position();
					var this_image_top = image_pos.top;
					var container_top = image_slider_container.position().top;

					var top_increase = -(this_image_top - Math.abs(container_top)) + scroll_to_height;
					//var left_increase = (153 * top_increase) / -190;
//					var left_increase = (3734.394736842 * top_increase) / -4635;
					var left_increase = (8749.78674 * top_increase) / -10850;

					var top_str = (top_increase > 0) ? '+='+Math.abs(top_increase) : '-='+Math.abs(top_increase) ;
					var left_str = (left_increase > 0) ? '+='+Math.abs(left_increase) : '-='+Math.abs(left_increase) ;


					image_slider_container.stop();
					if (speed == 0) {
						image_slider_container.css({
							top: top_str,
							left: left_str
						});
					} else {
						image_slider_container.animate({
							top: top_str,
							left: left_str
						}, {queue: false, duration: speed, easing: 'swing', complete: function() {
							console.log ("came complete");
						}});
					}
				}
				
			}
			
			function open_image(image, animation_time) {
				jQuery(image).prev().addClass('before_open_image');
				jQuery(image).addClass('open_image');

				var img_height = parseInt(jQuery(image).attr('img_height'));
				if (animation_time == undefined) {
					animation_time = 700;
				}
				var slide_animation_time = animation_time;
				var start_left_cover = -696;
				var start_right_cover = 428;
				var cover_move_distance = 400;
				var left_cover = start_left_cover - cover_move_distance;
				var right_cover = start_right_cover + cover_move_distance;
				var top_increase = Math.abs(img_height - 300);
				var left_increase = (8749.78674 * top_increase) / -10850;
				
				
//				if (!image.hasClass('vertical_panoramic')) {
//					jQuery('.left_cover_image', image).stop().animate({
//						left: left_cover
//					}, {queue: false, duration: animation_time});
//
//					jQuery('.right_cover_image', image).stop().animate({
//						left: right_cover
//					}, {queue: false, duration: animation_time});
//				} else {
					jQuery('.left_cover_image', image).stop().css({
						left: left_cover
					});

					jQuery('.right_cover_image', image).stop().css({
						left: right_cover
					});
//				}
					


				jQuery(image).css({
					height: img_height,
					width: '+=20'
				});
				
				jQuery('.img_outer_cont', image).css({
					height: img_height,
					width: '+=20'
				});

				jQuery(image).css({
					marginLeft: Math.round(left_increase/2)+'px'
				});

				jQuery(image).nextAll().css({
					marginLeft: left_increase+'px'
				});
//				jQuery('.left_cover_image', image).stop().animate({
//					left: left_cover
//				}, {queue: false, duration: slide_animation_time});
//
//				jQuery('.right_cover_image', image).stop().animate({
//					left: right_cover
//				}, {queue: false, duration: slide_animation_time});
//
//				jQuery(image).animate({
//					height: img_height,
//					width: '+=20'
//				}, {queue: false, duration: animation_time});
//				
//				jQuery('.img_outer_cont', image).animate({
//					height: img_height,
//					width: '+=20'
//				}, {queue: false, duration: animation_time});
//
//				jQuery(image).nextAll().animate({
//					marginLeft: '-171'
//				}, {queue: false, duration: animation_time});
			}
			
			function close_image(image, animation_time) {
				jQuery('#image_slider_container .before_open_image').removeClass('before_open_image');
				jQuery(image).removeClass('open_image');
				
				var img_height = parseInt(jQuery(image).attr('img_height'));
				if (animation_time == undefined) {
					animation_time = 700;
				}
				var slide_animation_time = animation_time + 300;
				var start_left_cover = -696;
				var start_right_cover = 428;
				var cover_move_distance = 400;
				var left_cover = start_left_cover - cover_move_distance;
				var right_cover = start_right_cover + cover_move_distance;
				
				
				jQuery('.left_cover_image', image).stop().css({
					left: start_left_cover
				});

				jQuery('.right_cover_image', image).stop().css({
					left: start_right_cover
				});

				jQuery(image).css({
					height: 310,
					width: '-=20'
				});
				
				jQuery('.img_outer_cont', image).css({
					height: 300,
					width: '-=20'
				});
				
				jQuery(image).css({
					marginLeft: 0
				});

				jQuery(image).nextAll().css({
					marginLeft: 0
				});
//				jQuery('.left_cover_image', image).stop().animate({
//					left: start_left_cover
//				}, {queue: false, duration: slide_animation_time});
//
//				jQuery('.right_cover_image', image).stop().animate({
//					left: start_right_cover
//				}, {queue: false, duration: slide_animation_time});
//
//				jQuery(image).animate({
//					height: 310,
//					width: '-=20'
//				}, {queue: false, duration: animation_time});
//				
//				jQuery('.img_outer_cont', image).animate({
//					height: 300,
//					width: '-=20'
//				}, {queue: false, duration: animation_time});
//
//				jQuery(image).nextAll().animate({
//					marginLeft: 0
//				}, {queue: false, duration: animation_time});
			}
			
			function init_open_image(image) {
				jQuery(image).prev().addClass('before_open_image');
				jQuery(image).addClass('open_image');

				var img_height = parseInt(jQuery(image).attr('img_height'));
				var animation_time = 700;
				var slide_animation_time = animation_time + 300;
				var start_left_cover = -696;
				var start_right_cover = 428;
				var cover_move_distance = 400;
				var left_cover = start_left_cover - cover_move_distance;
				var right_cover = start_right_cover + cover_move_distance;
				
				var top_increase = Math.abs(img_height - 300);
				var left_increase = (8749.78674 * top_increase) / -10850;
				
				jQuery('.left_cover_image', image).stop().animate({
					left: left_cover
				}, {queue: false, duration: slide_animation_time});

				jQuery('.right_cover_image', image).stop().animate({
					left: right_cover
				}, {queue: false, duration: slide_animation_time});

				jQuery(image).animate({
					height: img_height,
					width: '+=20'
				}, {queue: false, duration: animation_time});
				
				jQuery('.img_outer_cont', image).animate({
					height: img_height,
					width: '+=20'
				}, {queue: false, duration: animation_time});

				jQuery(image).animate({
					marginLeft: Math.round(left_increase/2)
				}, {queue: false, duration: animation_time});

				jQuery(image).nextAll().animate({
					marginLeft: left_increase
				}, {queue: false, duration: animation_time});
				
				
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
			}
			
			function scroll_to_next_image() {
				var current_image = jQuery('#image_slider_container .float_image_cont.current_image');
				var next_image = current_image.prev();
				if (next_image.hasClass('actual_image')) {
					scroll_to_image(next_image, 300);
				} else {
					var first_image = jQuery('#image_slider_container .float_image_cont.first');
					var before_first_image = first_image.next();
					scroll_to_image(before_first_image, 0, true);
					scroll_to_image(first_image, 300);
				}
			}
			
			function scroll_to_prev_image() {
				var current_image = jQuery('#image_slider_container .float_image_cont.current_image');
				var prev_image = current_image.next();
				if (prev_image.hasClass('actual_image')) {
					scroll_to_image(prev_image, 300);
				} else {
					var last_image = jQuery('#image_slider_container .float_image_cont.last');
					var after_last_image = last_image.prev();
					scroll_to_image(after_last_image, 0, true);
					scroll_to_image(last_image, 300);
				}
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
				jQuery('#image_slider_container').css({
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
				jQuery('#image_slider_container').css({
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
					
					setTimeout(function() {
						var second_to_last_image = jQuery('#image_slider_container .float_image_cont.first').prev();
						init_open_image(second_to_last_image);
					}, 20);
					
				});
				
				// find the second to last image and scroll to it at the beginning
				var second_to_last_image = jQuery('#image_slider_container .float_image_cont.first').prev();
				scroll_to_image(second_to_last_image, 0, true, true);
				
				var bootstrap_count = 0;
				$('<img/>').attr('src', '/img/left_block_image.png').load(function() {
					bootstrap_count++;
					if (bootstrap_count >= 3) {
						bootstrap();
					}
				});
				$('<img/>').attr('src', '/img/right_block_image.png').load(function() {
					bootstrap_count++;
					if (bootstrap_count >= 3) {
						bootstrap();
					}
				});
				$('<img/>').attr('src', '/img/progress_bg.png').load(function() {
					bootstrap_count++;
					if (bootstrap_count >= 3) {
						bootstrap();
					}
				});
				
				
				jQuery('.scroll_up_right').click(function() {
					scroll_to_prev_image();
				});
				
				jQuery('.scroll_down_left').click(function() {
					scroll_to_next_image();
				});
				
				jQuery('#image_slider_container .float_image_cont.actual_image').click(function() {
					scroll_to_image(jQuery(this), 300);
				});
				
				jQuery('#image_slider_container .float_image_cont.fake_image').click(function() {
					var photo_id = jQuery(this).attr('photo_id');
					var real_image = jQuery('#image_slider_container .float_image_cont.actual_image[photo_id='+photo_id+']');
					var scroll_from = undefined;
					if (jQuery(this).hasClass('before')) {
						scroll_from = real_image.prev();
					} else {
						scroll_from = real_image.next();
					}
					scroll_to_image(scroll_from, 0, true);
					scroll_to_image(real_image, 300);
				});
				
				
					
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
					
					
					/////////////////////////////////////////////////////////
					// mark start photos as real photos
					foreach ($photos as $key => $photo) {
						$photos[$key]['classes'][] = 'actual_image';
					}
					
					/////////////////////////////////////////////////////////
					// mark first and last real photos for convenience in js
					reset($photos);
					$first_key = key($photos);
					$photos[$first_key]['classes'][] = 'first';
					end($photos);
					$last_key = key($photos);
					$photos[$last_key]['classes'][] = 'last';

					
					/////////////////////////////////////////////////////////
					// add photos for endless circle illusion
					$num_to_pad = 0;
					$total_real_photos = count($photos);
					if ($total_real_photos >= 7) {
						$num_to_pad = 3;
					} else if ($total_real_photos >= 6) {
						$num_to_pad = 2;
					} else if ($total_real_photos >= 5) {
						$num_to_pad = 1;
					}
					$first_n_pad_photos = array();
					$last_n_pad_photos = array();
					if ($num_to_pad > 0) {
						// grab nub from beginning and end
						$first_n_pad_photos = array_slice($photos, 0, $num_to_pad);
						$last_n_pad_photos = array_slice($photos, -$num_to_pad);
					}
					foreach ($last_n_pad_photos as &$last_n_pad_photo) {
						unset($last_n_pad_photo['classes']);
						$last_n_pad_photo['classes'][] = 'fake_image';
						$last_n_pad_photo['classes'][] = 'before';
					}
					for ($i = count($last_n_pad_photos) - 1; $i >= 0; $i--) {
						array_unshift($photos, $last_n_pad_photos[$i]);
					}
					foreach ($first_n_pad_photos as &$first_n_pad_photo) {
						unset($first_n_pad_photo['classes']);
						$first_n_pad_photo['classes'][] = 'fake_image';
						$first_n_pad_photo['classes'][] = 'after';
					}
					foreach ($first_n_pad_photos as $first_n_pad_photo) {
						array_push($photos, $first_n_pad_photo);
					}
					
					
					
					//////////////////////////////////////////////////////////////
					// add 4 blank onto beginning and end
					array_unshift($photos, array('blank_photo' => true, 'classes' => array()));
					array_unshift($photos, array('blank_photo' => true, 'classes' => array()));
					array_unshift($photos, array('blank_photo' => true, 'classes' => array()));
					array_unshift($photos, array('blank_photo' => true, 'classes' => array()));
					array_push($photos, array('blank_photo' => true, 'classes' => array()));
					array_push($photos, array('blank_photo' => true, 'classes' => array()));
					array_push($photos, array('blank_photo' => true, 'classes' => array()));
					array_push($photos, array('blank_photo' => true, 'classes' => array()));
					
					
					//////////////////////////////////////////////////////////////
					// variables
					$cover_width = 988;
//					$blank_cont_left_add = -121;
//					$cont_left_add = -128;
					$cont_left_add = -250;
					$prev_left = null;
					
					
					
					// reverse photos just for this theme (because of the way the js animations are done)
					$photos = array_reverse($photos);
				?>
				<?php foreach ($photos as $photo): ?>
					<?php 
						$blank = false;
						if (isset($photo['blank_photo'])) {
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
								case 'square':
								case 'portrait':
									$width = 630;
									$height = 630;
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
									$height = 720;
									break;
							}
							$photo['classes'][] = $photo['Photo']['PhotoFormat']['ref_name'];
							$img_src = $this->Photo->get_photo_path($photo['Photo']['id'], $height, $width, .4, true); 
						}

					?>
						<?php 
							$total_width = $img_src['width'] + 20;
							$total_height = $img_src['height'] + 20;

							// figure out the position of the left cover
							$distance_from_middle = 68;
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
						<div photo_id="<?php if (isset($photo['Photo']['id'])) { echo $photo['Photo']['id']; } ?>" class="float_image_cont <?php echo implode(' ', $photo['classes']); ?>" style="width: 720px; height: 310px; left: <?php echo $left; ?>px;" start_left="<?php echo $left; ?>" img_width="<?php echo $total_width; ?>" img_height="<?php echo $total_height; ?>">
							<div class="img_outer_cont">
								<div class="img_cont" style="width: <?php echo $total_width; ?>px; height: <?php echo $total_height; ?>px; margin-left: <?php echo -floor($total_width/2); ?>px; margin-top: <?php echo -floor($total_height/2); ?>px;">
									<div class="img_inner_wrap">
										<img src="<?php echo $img_src['url']; ?>" style="display: block; width: <?php echo $img_src['width']; ?>px; height: <?php echo $img_src['height']; ?>px;" <?php echo $img_src['tag_attributes']; ?> />
									</div>
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
//							$distance_from_middle = 74;
//							$cover_width_left = 360 - $distance_from_middle - $cover_width;
//							$cover_width_right = 360 + $distance_from_middle;
//
//							$left = $prev_left + $blank_cont_left_add;
//							$prev_left = $left;
						?>
						<?php /*<div class="float_blank_cont" style="left: <?php echo $left; ?>px;">
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
						</div> */ ?>
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