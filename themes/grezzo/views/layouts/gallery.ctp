<!DOCTYPE html>
<html>
	<head>
		<title>Photography by Andrew Morrill</title>
		<meta name="keywords" content="Andrew Morrill, photography, fine art, utah photography, utah photographer, National Park, Utah, California">
		<meta name="description" content="Large format landscape photography by Utah based photographer Andrew Morrill.">
		<?php echo $this->Element('theme_global_includes'); ?>
		<?php echo $this->Element('grezzo_includes'); ?>
		<link href='http://fonts.googleapis.com/css?family=Signika+Negative:300' rel='stylesheet' type='text/css'>
		
		<script src="/js/scrollto/jquery.scrollTo.min.js"></script>
		<script src="/js/jquery.endless-scroll_horizontal.js"></script>
	</head>
	<body>
		
		<?php 
			
			$is_landing_page = false;
			
			if (!isset($photos)) {
				// treat the landing page as the first gallery
				$is_landing_page = true;
				$curr_gallery = $this->Gallery->get_first_gallery(); 
				if (isset($curr_gallery['PhotoGallery']['id'])) {
					$gallery_id = $curr_gallery['PhotoGallery']['id'];
				} else {
					$gallery_id = 0;
				}
				$photos = $this->Gallery->get_gallery_photos($gallery_id, 15);
			}
			
			$photo_tmp = array();
			foreach($photos as $photo) {
				$photo['Photo']['tag_attributes'] = addslashes($photo['Photo']['tag_attributes']);
				$photo_tmp[$photo['Photo']['id']] = $photo;
			}
			$photos = $photo_tmp;
		?>
		<div id='outer_nav'>
			<div id="logo_nav_cont">
			<?php echo $this->Element('nameTitle'); ?>
			<?php echo $this->Element('menu/two_level_navbar'); ?>
			</div>	
		</div>
		<div id='page_content'>
			<div id="image_slider_progressbar"></div>
			<div id='gallery_outer_cont'>
				<div id="grezzo_listing_actual_container_loading"><?php echo nl2br(str_replace(' ', "\n", __('L O A D I N G', true))); ?></div>
				<div id="grezzo_listing_actual_container">
					<img class="blank" src="/images/large_blank.png" width="1600" height="500" /><!--
					--><?php echo $this->Element('gallery/gallery_image_lists/simple_list', array(
						'photos' => $photos,
						'height' => '500',
						'width' => '2000',
						'sharpness' => '.4'
					)); ?>
					<img class="blank" src="/images/large_blank.png" width="1600" height="500" /></div>

				<div id="grezzo_scroll_hide" class=""></div>
				<div id="right_arrow" class="navigation_arrow">

				</div>
				<div id="left_arrow" class="navigation_arrow"></div>
			</div>
			<div id='image_info_container'>
				<div id='gallery_name'>
					<h2><?php echo $curr_gallery['PhotoGallery']['display_name']; ?></h2>
				</div>
				<?php foreach ($photos as $photo): ?>
					<div image_info_id='<?php echo $photo['Photo']['id']; ?>' class='image_info'>
						<div class='photo_details'>
							<h4><?php echo $photo['Photo']['display_title'];  ?></h4>
							<p><?php echo $photo['Photo']['description']; ?></p>
						</div>
						<?php /* Adam Todo: if ecommerce is not turned on then these need to be hidden */ ?>
						<div class='side_control_options'>
							<?php echo $this->Element('cart_elements/add_to_cart', array(
								'photo_id'=>$photo['Photo']['id'],
							)); ?>
							
						</div>
					</div>
				<?php endforeach; ?>
			</div>			
		</div>
		
		<script type="text/javascript">
			var in_callback = false;
			var cease_fire = false;
			var last_photo_id = undefined;
			
			var is_landing_page = <?php echo empty($is_landing_page) ? 'false':'true'; ?>;
			function endless_scroll_callback() {
				if (in_callback == true) {
					return;
				}
				
				jQuery('#grezzo_listing_actual_container_loading').stop().fadeIn();

				in_callback = true;
				if (last_photo_id == undefined) {
					last_photo_id = jQuery('#grezzo_listing_actual_container img:not(.blank):last').attr('photo_id');
				} 
				if (last_photo_id == undefined) { 
					last_photo_id = 0;
				}
				
				jQuery.ajax({
					type : 'post',
					url : '/photo_galleries/ajax_get_gallery_photos_after/<?php echo $gallery_id; ?>/'+last_photo_id+'/',
					data : {},
					success : function (image_list) {
//						console.log (image_list);
						var image_list_large_html = jQuery(image_list.large_html);
						var image_list_small_html = jQuery(image_list.small_html);
						setup_image_clicks(image_list_small_html);

//							console.log (image_list_large_html);
//							console.log (image_list_small_html);


						var last_large_image = jQuery('#grezzo_listing_actual_container img.blank:last');
						last_large_image.before(image_list_large_html);

						var last_small_image = jQuery('#grezzo_scroll_control_inner img.blank:last');
						last_small_image.before(image_list_small_html);

						
						if (image_list.has_more == false) {
							cease_fire = true;
						}
					},
					complete: function(jqXHR, textStatus) {
//						console.log ("came into complete");
						jQuery('#grezzo_listing_actual_container_loading').stop().fadeOut(2000);
					},
					error: function () {
//						console.log ("came into the error");
					},
					dataType: "json"
				}); 
			}
			
			function move_to_next_prev_image(direction) {
				if (direction != 'left' && direction != 'right') {
					direction = 'right';
				}
				
				// find the next image
				var middle_of_screen = jQuery(document).width() / 2;
				var current_middle_image = undefined;
				var next_image = undefined;
				jQuery('#grezzo_listing_actual_container img:not(.blank)').each(function() {
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
						next_image = jQuery('#grezzo_listing_actual_container img:not(.blank)').first();
					} else {
						next_image = jQuery('#grezzo_listing_actual_container img:not(.blank)').last();
					}
				}

				
				// center the next image
				if (next_image != undefined && next_image.length > 0 && !next_image.hasClass('blank')) {
					var center_of_next_image = jQuery('#grezzo_listing_actual_container').scrollLeft() + next_image.offset().left + (next_image.width() / 2);
//					console.log (next_image);
//					console.log (center_of_next_image);
//					console.log (middle_of_screen);
					var new_scroll_left = Math.round(center_of_next_image - middle_of_screen);
					jQuery('#grezzo_listing_actual_container').scrollTo(new_scroll_left + 'px', 400, {easing: 'linear'});
					calculate_scroll_control_div_width_and_pos();
					calculate_control_container_scroll();
					show_image_data(next_image);
				}
			}
			
			function show_image_data(next_image) {
				if (is_landing_page == false) {
					jQuery('#gallery_name').show();
					jQuery('#image_info_container .image_info').hide();
					jQuery('#image_info_container .image_info[image_info_id=' + next_image.attr('photo_id') + ']').show();
					window.location.hash = next_image.attr('photo_id');
				}
			}
			
			function calculate_scroll_control_div_width() {
				var window_width = jQuery(window).width();
				var control_width = window_width/10;
				
				jQuery('#grezzo_scroll_control_inner .scroll_control_div').width(control_width);
			}
			
			function calculate_scroll_control_div_pos() {
				var slider = jQuery('#grezzo_listing_actual_container');
				var slider_left = slider.scrollLeft();
				var slider_width = slider[0].scrollWidth;
				
				scrollPercent = (slider_left / slider_width) * 100;

//				console.log("Current scroll percent: " + scrollPercent);
				
				var control = jQuery('#grezzo_scroll_control_inner');
				var control_left = (control.width() * (scrollPercent / 100));
				
				
//				console.log ("control_width: " + control.width());
//				console.log ("control_left: " + control_left);
				
				var actual_control = jQuery('#grezzo_scroll_control_inner .scroll_control_div');
				actual_control.css('left', control_left);
			}
			
			function calculate_scroll_control_div_width_and_pos() {
				calculate_scroll_control_div_pos();
				calculate_scroll_control_div_width();
			}
			
			function calculate_slider_scroll_position() {
				var control = jQuery('#grezzo_scroll_control_inner .scroll_control_div');
				
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
				
				var slider = jQuery('#grezzo_listing_actual_container');
				var slider_width = slider[0].scrollWidth;
				

//				console.log (percentage);
				
				var slider_scroll = Math.round(slider_width * (percentage/100) );
				
//				console.log (slider_scroll);
				
				slider.scrollLeft(slider_scroll);
			}
			
			var total_images = 0;
			var loaded_images = 0;
			function update_progress_bar() {
				var total_progress = Math.round((loaded_images / total_images) * 100);
				console.log(total_progress);
				jQuery("#image_slider_progressbar").progressbar('value', total_progress);
			}
			
			function calculate_control_container_scroll() {
				return;
				var scroll_control_cont = jQuery('#grezzo_scroll_control');
				var scroll_control = jQuery('#grezzo_scroll_control_inner .scroll_control_div');
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
//				var scroll_control_cont = jQuery('#grezzo_scroll_control');
//				var scroll_control = jQuery('#grezzo_scroll_control_inner .scroll_control_div');
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
//			
//			function autoscroll(duration) {
//				if (autoscrolling == true) {
//					calculate_autoscroll_speed();
//					duration = Math.round(duration * .85);
//					var scroll_control_cont = jQuery('#grezzo_scroll_control');
//					var scroll_control = jQuery('#grezzo_scroll_control_inner .scroll_control_div');
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
			
			function go_to_this_image(image_id) {
				image_id = image_id.replace('#', '');
				var element = $('img[photo_id='+image_id+']');
				if (element === undefined) {
					return;
				}
				go_to_this_image_element(element);
				show_image_data(element);
			}	

			function go_to_this_image_element(element) {
				
				var control = jQuery('#grezzo_listing_actual_container');
				var image_center = element.position().left + ( element.width() / 2);
				var new_left = image_center - (control.width() / 2);
				control.scrollLeft(new_left);
			}
			
			function setup_image_clicks(selector) {
				jQuery(selector).click(function(){
					go_to_this_image_element($(this));
				});
			}
			
			jQuery(document).ready(function() {		
				$("#add_to_cart_buttons_cont select.sizes_avail_for_print_type").chosen({
					width:'170px',
					disable_search: true
				}); 
				jQuery('#grezzo_listing_actual_container').endlessScroll_horizontal({
					bottomPixels: 2000,
					loader: '',
					insertAfter: '',
					callback: function (i) {
						
						endless_scroll_callback();
					}
				});
				
				
				jQuery('#left_arrow').click(function() {
					move_to_next_prev_image('left');
				});
				jQuery('#right_arrow').click(function() {
					move_to_next_prev_image('right');
				});
				
				
				jQuery('#grezzo_scroll_control_inner .scroll_control_div').draggable({
					axis: "x",
					containment: 'parent',
					scroll: false,
					start: function(event, ui) {
						autoscrolling = true;
					},
					drag: function( event, ui ) {
						var scroll_control_cont = jQuery('#grezzo_scroll_control');
						var scroll_control = jQuery('#grezzo_scroll_control_inner .scroll_control_div');

						scroll_control_cont.stop(true);
						scroll_control.stop(true);
						
						calculate_slider_scroll_position();
						
//						console.log (jQuery('#grezzo_listing_actual_container').scrollLeft());
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
						
//						jQuery('#grezzo_listing_actual_container, #grezzo_scroll_control_inner').show();
						jQuery('#entire_slider_hider').fadeTo(1000, 0, function() {
							jQuery(this).hide();
						});
						
						if (parent.location.hash == '') {
							jQuery('#grezzo_listing_actual_container').scrollLeft(Math.round(jQuery('#grezzo_listing_actual_container')[0].scrollWidth * .40));
							move_to_next_prev_image('right');
						} else {
							go_to_this_image(parent.location.hash);
						}
					}
				});
				
				// count how many of the images have loaded
				jQuery('#grezzo_listing_actual_container img:not(.blank)').each(function() {
					total_images++;
					var tmpImg = new Image() ;
					tmpImg.src = $(this).attr('src') ;
					tmpImg.onload = function() {
						loaded_images++;
						update_progress_bar();
					};
					tmpImg.error = function() {
					//	console.log('image loaded');
						loaded_images++;
						update_progress_bar();
					};
				});
				
				setup_image_clicks('#grezzo_scroll_control_inner img:not(.blank)');
			});
			
			jQuery(window).load(function() {
				// start the scrolling out in the middle - DREW TODO - decide if we want to remember the position of the scroll in a cookie
				
				

				
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
		
		
		
		
		
		
	</body>
</html>