<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<?php echo $this->Element('theme_global_includes'); ?>
	
	<script src="/js/scrollto/jquery.scrollTo.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400italic,400' rel='stylesheet' type='text/css' />
	
</head>

<body>
	<?php if (count($photos) > 0): ?>
		<div id="white_slider_listing_actual_container"><img class="blank" src="/images/large_blank.png" width="1600" height="500" /><!--
			--><?php foreach($photos as $photo): ?><!--
				--><?php $img_src = $this->Photo->get_photo_path($photo['Photo']['id'], 500, 2000, .4, true); ?><!--
				--><img src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> /><!--
			--><?php endforeach; ?><img class="blank" src="/images/large_blank.png" width="1600" height="500" /></div>
		<div id="white_slider_scroll_hide" class=""></div>
		<div id="left_arrow" class="navigation_arrow">

		</div>
		<div id="right_arrow" class="navigation_arrow">

		</div>
		<div id="entire_slider_hider"></div>
	<?php else: ?>
		<h4 style="font-weight: bold; font-style: italic; margin: 10px;"><?php __('This gallery does not have any images yet'); ?></h4><?php // DREW TODO - make this seccion look good ?>
	<?php endif; ?>
		
	<div class="container">
		<div id="image_slider_progressbar"></div>
		
		
		<?php echo $this->Element('nameTitle'); ?>

		<?php //echo $this->Element('temp_menu'); ?>
		<?php echo $this->Element('menu/two_level_navbar'); ?>


		<style type="text/css">

		</style>
		
		
		<div style="clear: both"></div>
		<div id="white_slider_listing_container"></div>
		
		<script type="text/javascript">
			function move_to_next_prev_image(direction) {
				if (direction != 'left' && direction != 'right') {
					direction = 'right';
				}
				
				// find the next image
				var middle_of_screen = jQuery(document).width() / 2;
				var current_middle_image = undefined;
				var next_image = undefined;
				jQuery('#white_slider_listing_actual_container img:not(.blank)').each(function() {
					var current_middle = jQuery(this).offset().left + (jQuery(this).width() / 2);
					var current_left = jQuery(this).offset().left;
					var current_right = jQuery(this).offset().left + jQuery(this).width();
					
					if (current_left <= middle_of_screen && current_right >= middle_of_screen) {
						current_middle_image = jQuery(this);
						
						var current_distance = Math.abs(middle_of_screen - current_middle);
						var tenth_of_image_width = (current_middle_image.width() * .1);

						// for debugging
//						console.log ("===============================");
//						console.log ('current_distance: '+current_distance);
//						console.log ('tenth_of_image_width: '+tenth_of_image_width);
//						console.log ('current_middle: '+current_middle);
//						console.log ('middle_of_screen: '+middle_of_screen);
//						console.log ("===============================");


						if (direction == 'right') {
							if (current_middle > middle_of_screen && current_distance > tenth_of_image_width ) {
								next_image = current_middle_image;
							} else {
								next_image = current_middle_image.next();
							}
						} else {
							if (current_middle < middle_of_screen && current_distance > tenth_of_image_width) {
								next_image = current_middle_image;
							} else {
								next_image = current_middle_image.prev();
							}
						}
						return;
					}
				});
				
				// means there was no image that was in the center (aka on the edges)
				if (next_image == undefined) {
					if (direction == 'right') {
						next_image = jQuery('#white_slider_listing_actual_container img:not(.blank)').first();
					} else {
						next_image = jQuery('#white_slider_listing_actual_container img:not(.blank)').last();
					}
				}

				
				// center the next image
				if (next_image != undefined && next_image.length > 0 && !next_image.hasClass('blank')) {
					var center_of_next_image = jQuery('#white_slider_listing_actual_container').scrollLeft() + next_image.offset().left + (next_image.width() / 2);
					var new_scroll_left = Math.round(center_of_next_image - middle_of_screen);
					jQuery('#white_slider_listing_actual_container').scrollLeft(new_scroll_left);
					calculate_scroll_control_div_width_and_pos();
					calculate_control_container_scroll();
				}
			}
			
			function calculate_scroll_control_div_width() {
				var window_width = jQuery(window).width();
				var control_width = window_width/10;
				
				jQuery('#white_slider_scroll_control_inner .scroll_control_div').width(control_width);
			}
			
			function calculate_scroll_control_div_pos() {
				var slider = jQuery('#white_slider_listing_actual_container');
				var slider_left = slider.scrollLeft();
				var slider_width = slider[0].scrollWidth;
				
				scrollPercent = (slider_left / slider_width) * 100;

//				console.log("Current scroll percent: " + scrollPercent);
				
				var control = jQuery('#white_slider_scroll_control_inner');
				var control_left = (control.width() * (scrollPercent / 100));
				
				
//				console.log ("control_width: " + control.width());
//				console.log ("control_left: " + control_left);
				
				var actual_control = jQuery('#white_slider_scroll_control_inner .scroll_control_div');
				actual_control.css('left', control_left);
			}
			
			function calculate_scroll_control_div_width_and_pos() {
				calculate_scroll_control_div_pos();
				calculate_scroll_control_div_width();
			}
			
			function calculate_slider_scroll_position() {
				var control = jQuery('#white_slider_scroll_control_inner .scroll_control_div');
				
//				console.log (left);
//				console.log (width);
//				console.log (control.position().left);
//				console.log (control.width());

				var left = control.position().left;
//				var width = control.width();
				var parent_width = control.parent().width();
//				var percentage = Math.ceil((left/parent_width)*100); // DREW TODO - maybe use this as without it the scroll will sometimes not go to the end, but without the ceil the scoll is more smooth
				var percentage = (left/parent_width)*100;
//				if (percentage > 78) { // this is kindof a hack - DREW TODO
//					percentage = Math.ceil(percentage);
//				}
				
				var slider = jQuery('#white_slider_listing_actual_container');
				var slider_width = slider[0].scrollWidth;
				

//				console.log (percentage);
				
				var slider_scroll = Math.round(slider_width * (percentage/100) );
				
				slider.scrollLeft(slider_scroll);
			}
			
			var total_images = 0;
			var loaded_images = 0;
			function update_progress_bar() {
				var total_progress = Math.round((loaded_images / total_images) * 100);
				jQuery("#image_slider_progressbar").progressbar('value', total_progress);
			}
			
			function calculate_control_container_scroll() {
				var scroll_control_cont = jQuery('#white_slider_scroll_control');
				var scroll_control = jQuery('#white_slider_scroll_control_inner .scroll_control_div');
				scroll_control_cont.stop(true);
				
				// figure out the current middle
				var middle = scroll_control.position().left + (scroll_control.width() / 2) - (scroll_control_cont.width() / 2);
				if (middle < 0) {
					middle = 0;
				}
				if (middle > scroll_control_cont[0].scrollWidth - scroll_control_cont.width()) {
					middle = scroll_control_cont[0].scrollWidth - scroll_control_cont.width();
				}
				
				scroll_control_cont.scrollTo(middle+'px', {
					axis: 'x',
					duration: 500
				});
			}
			
//			var autoscrolling = false;
//			var min_scroll_speed = 1;
//			var max_scroll_speed = 30;
//			var scroll_speed = min_scroll_speed;
//			var autoscroll_dir = 'left';
//			function calculate_autoscroll_speed() {
//				// figure out if control div is right or left of center
//				var scroll_control_cont = jQuery('#white_slider_scroll_control');
//				var scroll_control = jQuery('#white_slider_scroll_control_inner .scroll_control_div');
//				var center = scroll_control_cont.scrollLeft() + (scroll_control_cont.width() / 2);
//				var control_pos = scroll_control.position().left + (scroll_control.width() / 2);
//				if (control_pos > center) { // right of center
//					autoscroll_dir = 'right';
//					var max = scroll_control_cont.scrollLeft() + scroll_control_cont.width();
//					var min = scroll_control_cont.scrollLeft() + (scroll_control_cont.width() / 2);
//					control_pos -= min;
//					max -= min;
//					min = 0;
//					var percentage_of_max = Math.abs(control_pos / max);
//				} else { // left of center
//					autoscroll_dir = 'left';
//					var max = scroll_control_cont.scrollLeft() + (scroll_control_cont.width() / 2);
//					var min = scroll_control_cont.scrollLeft();
//					control_pos -= min;
//					max -= min;
//					min = 0;
//					var percentage_of_max = Math.abs(max - control_pos) / max;
//				}
//				
//				scroll_speed = max_scroll_speed * percentage_of_max;
//				console.log (scroll_speed);
//			}
			
//			function autoscroll(duration) {
//				if (autoscrolling == true) {
//					calculate_autoscroll_speed();
//					duration = Math.round(duration * .85);
//					var scroll_control_cont = jQuery('#white_slider_scroll_control');
//					var scroll_control = jQuery('#white_slider_scroll_control_inner .scroll_control_div');
//					
//					scroll_control_cont.stop(true);
//					scroll_control.stop(true);
//					
//
//						if (autoscroll_dir == 'right') {
//							if (scroll_control.position().left < scroll_control_cont[0].scrollWidth - ( scroll_control_cont.width() / 8 ) && scroll_control_cont.scrollLeft() + scroll_control_cont.width() < scroll_control_cont[0].scrollWidth ) {
//								scroll_control_cont.scrollTo('+='+scroll_speed+'px', {
//									axis: 'x',
//									duration: duration,
//									easing: 'linear',
//									onAfter: function() {
//										calculate_slider_scroll_position();
//									}
//								});
//								scroll_control.animate({
//									left: '+='+scroll_speed
//								}, duration, 'linear', function() {
//									calculate_slider_scroll_position();
//								});
//							}
//						} else {
//							if (scroll_control_cont.scrollLeft() > 0) {
//								scroll_control_cont.scrollTo('-='+scroll_speed+'px', {
//									axis: 'x',
//									duration: duration,
//									easing: 'linear',
//									onAfter: function() {
//										calculate_slider_scroll_position();
//									}
//								});
//								scroll_control.animate({
//									left: '-='+scroll_speed
//								}, duration, 'linear', function() {
//									calculate_slider_scroll_position();
//								});
//							}
//						}
//				}
//			}
			
			jQuery(document).ready(function() {
				jQuery('#left_arrow').click(function() {
					move_to_next_prev_image('left');
				});
				jQuery('#right_arrow').click(function() {
					move_to_next_prev_image('right');
				});
				
				
				jQuery('#white_slider_scroll_control_inner .scroll_control_div').draggable({
					axis: "x",
					containment: 'parent',
					scroll: false,
					start: function(event, ui) {
						autoscrolling = true;
					},
					drag: function( event, ui ) {
						var scroll_control_cont = jQuery('#white_slider_scroll_control');
						var scroll_control = jQuery('#white_slider_scroll_control_inner .scroll_control_div');

						scroll_control_cont.stop(true);
						scroll_control.stop(true);
						
						calculate_slider_scroll_position();
						
//						console.log (jQuery('#white_slider_listing_actual_container').scrollLeft());
					},
					stop: function() {
						autoscrolling = false;
						calculate_control_container_scroll();
					}
				});
				
				// setup the loading progressbar
				jQuery("#image_slider_progressbar").progressbar({
					value: 0,
					complete: function( event, ui ) {
						// DREW TODO - put in a failsafe to make sure the complete gets run at some point
						jQuery(this).progressbar('destroy').hide();
						
//						jQuery('#white_slider_listing_actual_container, #white_slider_scroll_control_inner').show();
						jQuery('#entire_slider_hider').fadeTo(1000, 0, function() {
							jQuery(this).hide();
						});
					}
				});
				
				
				// count how many of the images have loaded
				jQuery('#white_slider_listing_actual_container img, #white_slider_scroll_control_inner img').each(function() {
					total_images++;
					var tmpImg = new Image() ;
					tmpImg.src = $(this).attr('src') ;
					tmpImg.onload = function() {
						loaded_images++;
						update_progress_bar();
					};
				});
			});
			
			jQuery(window).load(function() {
				// start the scrolling out in the middle - DREW TODO - decide if we want to remember the position of the scroll in a cookie
				jQuery('#white_slider_listing_actual_container').scrollLeft(Math.round(jQuery('#white_slider_listing_actual_container')[0].scrollWidth * .40));
				move_to_next_prev_image('right');
				
				
				// setup the autoscrolling
//				autoscrolling = true;
//				var duration = 100;
//				setInterval(function() {
//					autoscroll(duration);
//				}, duration);
				
				calculate_scroll_control_div_width_and_pos();
			}).resize(function() {
				calculate_scroll_control_div_width_and_pos();
			});
		</script>
		
		<div id="white_slider_scroll_control_cont">
			<div id="hide_control_scroll_div" class=""></div>
			<div id="white_slider_scroll_control">
				<div id="white_slider_scroll_control_inner">
					<div class="scroll_control_div"></div>
					<img class="blank" src="/images/blank.png" width="160" height="50" />
					<?php foreach($photos as $photo): ?><!--
						--><?php $img_src = $this->Photo->get_photo_path($photo['Photo']['id'], 50, 300, .4, true); ?><!--
						--><img src="<?php echo $img_src['url']; ?>" <?php echo $img_src['tag_attributes']; ?> /><!--
					--><?php endforeach; ?>
					<img class="blank" src="/images/blank.png" width="160" height="50" />
				</div>
			</div>
		</div>
		
		
		
	</div>

</body>
</html>