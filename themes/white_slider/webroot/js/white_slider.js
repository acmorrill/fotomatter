	var in_callback = false;
	var cease_fire = false;
	var last_photo_id = undefined;
	var first_endless_load = true;
	function endless_scroll_callback() {
		if (in_callback == true) {
			return;
		}

		in_callback = true;
		last_photo_id = jQuery('#white_slider_listing_actual_container img:not(.blank):last').attr('photo_id');
		if (last_photo_id == 'undefined') { 
			last_photo_id = 0;
		}

		
		var gallery_id = jQuery('#white_slider_listing_actual_container').attr('data-gallery_id');
		var max_gallery_images = jQuery('#white_slider_listing_actual_container').attr('data-max_gallery_images');
		jQuery.ajax({
			type : 'post',
			url : '/photo_galleries/ajax_get_gallery_photos_after/' + gallery_id + '/' + last_photo_id + '/' + max_gallery_images,
			data : {},
			success : function (image_list) {
				jQuery(document).unbind('images_loaded');
				jQuery(document).unbind('image_load_progress');
				if (first_endless_load) {
					first_endless_load = false;
				} else {
					jQuery("#image_slider_progressbar_container").show();
					jQuery("#image_slider_progressbar").progressbar('value', 5);
				}
				
				
				var image_list_large_html = jQuery(image_list.large_html);
				var image_list_small_html = jQuery(image_list.small_html);
				setup_image_clicks(image_list_small_html);
				setup_large_image_clicks(image_list_large_html.filter('img'));


				var all_images = jQuery(image_list_small_html).add(image_list_large_html).filter('img');
				if (all_images.size() > 0) {
					// progress bar is finished loading
					jQuery(document).bind('images_loaded', function(e) {
						jQuery("#image_slider_progressbar_container").stop().fadeOut(1000);
					});

					// update progress as images load
					jQuery(document).bind('image_load_progress', function(e, total_progress) {
						jQuery("#image_slider_progressbar").progressbar('value', total_progress);
					});
					
					
					var last_large_image = jQuery('#white_slider_listing_actual_container img.blank:last');
					last_large_image.before(image_list_large_html);

					var last_small_image = jQuery('#white_slider_scroll_control_inner img.blank:last');
					last_small_image.before(image_list_small_html);
					
					jQuery(document).trigger('preload_images_for_progress', [ all_images ]);
					
					
					// add in the photo data including add to cart buttons
					var photo_data_html = jQuery(image_list.photo_data_html);
					jQuery('#white_slider_ecommerce_container').append(photo_data_html);
				} else {
					jQuery("#image_slider_progressbar_container").stop().fadeOut(1000);
				}
				

				if (image_list.has_more == false) {
					cease_fire = true;
				}
			},
			complete: function(jqXHR, textStatus) { in_callback = false; },
			error: function () { jQuery("#image_slider_progressbar_container").stop().hide(); },
			dataType: "json"
		}); 
	}
	
	function get_current_middle_image() {
		var middle_of_screen = jQuery(document).width() / 2;
		var current_middle_image = undefined;
		var return_data = undefined;
		jQuery('#white_slider_listing_actual_container img:not(.blank)').each(function() {
			var current_middle = jQuery(this).offset().left + (jQuery(this).width() / 2);
			var current_left = jQuery(this).offset().left;
			var current_right = jQuery(this).offset().left + jQuery(this).width();

			if (current_left <= middle_of_screen && current_right >= middle_of_screen) {
				current_middle_image = jQuery(this);
				
				return_data = {};
				return_data.current_middle_image = current_middle_image;
				return_data.current_middle = current_middle;
				
				return false;
			}
		});
		
		return return_data;
	}
	
	function move_to_next_prev_image(direction) {
		if (direction != 'left' && direction != 'right') {
			direction = 'right';
		}


		////////////////////////////////////////////////////////////////////////////////
		// find the next image
		var middle_of_screen = jQuery(document).width() / 2;
		var middle_image_data = get_current_middle_image();
		var next_image = undefined;
		var current_distance = Math.abs(middle_of_screen - middle_image_data.current_middle);
		var tenth_of_image_width = (middle_image_data.current_middle_image.width() * .1);
		if (direction == 'right') {
			if (middle_image_data.current_middle > middle_of_screen && current_distance > tenth_of_image_width ) {
				next_image = middle_image_data.current_middle_image;
			} else {
				next_image = middle_image_data.current_middle_image.next();
			}
		} else {
			if (middle_image_data.current_middle < middle_of_screen && current_distance > tenth_of_image_width) {
				next_image = middle_image_data.current_middle_image;
			} else {
				next_image = middle_image_data.current_middle_image.prev();
			}
		}



		// means there was no image that was in the center (aka images are on the edges)
		if (typeof next_image == 'undefined' || next_image.attr('photo_id') == 'undefined') {
			if (direction == 'right') {
				next_image = jQuery('#white_slider_listing_actual_container img:not(.blank)').last();
			} else {
				next_image = jQuery('#white_slider_listing_actual_container img:not(.blank)').first();
			}
		}
	
		// center the next image
		if (next_image != 'undefined' && next_image.length > 0 && !next_image.hasClass('blank')) {
			var center_of_next_image = jQuery('#white_slider_listing_actual_container').scrollLeft() + next_image.offset().left + (next_image.width() / 2);
	//					console.log (next_image);
	//					console.log (center_of_next_image);
	//					console.log (middle_of_screen);
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
		calculate_ecommerce_display_and_opacity();
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

	//				console.log (slider_scroll);

		slider.scrollLeft(slider_scroll);
		calculate_ecommerce_display_and_opacity();
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


	function setup_image_clicks(selector) {
		jQuery(selector).click(function(){
			var control = jQuery('#white_slider_scroll_control_inner .scroll_control_div');
			var image_center = jQuery(this).position().left + ( jQuery(this).width() / 2);
			var new_left = image_center - (control.width() / 2);
			control.css('left', new_left);

			calculate_slider_scroll_position();
			calculate_control_container_scroll();
		});
	}
	
	function setup_large_image_clicks(selector) {
		jQuery(selector).click(function() {
			var photo_id = jQuery(this).attr('photo_id');
			go_to_this_image(photo_id);
		});
	}
	
	function go_to_this_image(photo_id) {
		var element = jQuery('#white_slider_listing_actual_container img[photo_id=' + photo_id + ']');
		if (element === undefined) {
			return;
		}
		go_to_this_image_element(element);
//		show_image_data(element);
	}

	function go_to_this_image_element(element) {
		var main_listing = jQuery('#white_slider_listing_actual_container');
		var image_center = element.position().left + (element.width() / 2);
		var page_center = (main_listing.width() / 2);
		var new_left = page_center - image_center;
		main_listing.scrollLeft(main_listing.scrollLeft() - new_left);
		calculate_scroll_control_div_width_and_pos();
	}
	
	function calculate_ecommerce_display_and_opacity() {
		// get the middle image
		var middle_of_screen = jQuery(document).width() / 2;
		var middle_image_data = get_current_middle_image();
		if (middle_image_data != undefined) {
			// figure out the distance
			var middle_image_width = middle_image_data.current_middle_image.width();
			var curr_photo_id = middle_image_data.current_middle_image.attr('photo_id');
			var middle_image_center = middle_image_data.current_middle_image.position().left + (middle_image_width / 2);
			var distance_from_middle = Math.abs(middle_of_screen - middle_image_center);
			var percent = distance_from_middle / (middle_image_width / 2);
			
			
			var show_image_ecommerce = false;
			var image_ecommerce_opacity = 0;
			var opaque_cutoff_percent = .4;
			var hide_cutoff_percent = .9;
			if (percent > hide_cutoff_percent) { // hide the dang thing
				show_image_ecommerce = false;
				image_ecommerce_opacity = 0;
			} else if (percent > opaque_cutoff_percent) { // a partial opacity
				show_image_ecommerce = true;
				image_ecommerce_opacity = 1 - ((percent - (1 - hide_cutoff_percent)) / (1 - opaque_cutoff_percent));
			} else { // show 100%
				show_image_ecommerce = true;
				image_ecommerce_opacity = 100;
			}
			
			
//			console.log('==========================================');
//			console.log("percent: " + percent);
//			console.log("image_ecommerce_opacity: " + image_ecommerce_opacity);
//			console.log('==========================================');
			
			
			
			if (show_image_ecommerce == true) {
				var curr_image_ecommerce_el = '#image_data_container_' + curr_photo_id + ', #current_image_linepointer_container';
				jQuery('.image_data_container').not(curr_image_ecommerce_el).hide();
				jQuery(curr_image_ecommerce_el).show().fadeTo(0, image_ecommerce_opacity);
			} else {
				jQuery('.image_data_container, #current_image_linepointer_container').hide();
			}
		}
		
		
		
		
	}
	

	jQuery(document).ready(function() {
		// setup the loading progressbar
		jQuery("#image_slider_progressbar").progressbar({ value: 5 });
		
		
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


		// progress bar is finished loading
		jQuery(document).bind('images_loaded', function(e) {
			setup_image_clicks('#white_slider_scroll_control_inner img:not(.blank)');
			setup_large_image_clicks("#white_slider_listing_actual_container img:not(.blank)");


			// hide the progress bar
			jQuery("#image_slider_progressbar_container").hide();
			jQuery('#entire_slider_hider').fadeTo(1000, 0, function() {
				jQuery(this).hide();
			});
			
			
			// setup the endless scroll
			jQuery('#white_slider_listing_actual_container').endlessScroll_horizontal({
				bottomPixels: 1200,
				loader: '',
				insertAfter: '',
				callback: function (i) {
					endless_scroll_callback();
				},
				ceaseFire: function() {
					return cease_fire;
				}
			});
		});

		// update progress as images load
		jQuery(document).bind('image_load_progress', function(e, total_progress) {
			jQuery("#image_slider_progressbar").progressbar('value', total_progress);
		});
		// start the preload progress
		jQuery(document).trigger('preload_images_for_progress');
		
	});

	jQuery(window).load(function() {
		console.log('=======================================');
		console.log(jQuery('#white_slider_listing_actual_container').length);
		console.log('=======================================');
		if (jQuery('#white_slider_listing_actual_container').length > 0) {
			// move to the image just to the right of center
			jQuery('#white_slider_listing_actual_container').scrollLeft(Math.round(jQuery('#white_slider_listing_actual_container')[0].scrollWidth * .40));
			move_to_next_prev_image('right');
		}
		
		
//		setup the autoscrolling
//		autoscrolling = true;
//		var duration = 100;
//		setInterval(function() {
//		autoscroll(duration);
//		}, duration);
	}).resize(function() {
		calculate_scroll_control_div_width_and_pos();
	});