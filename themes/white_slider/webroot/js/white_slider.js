	var in_callback = false;
	var cease_fire = false;
	var last_photo_id = undefined;
	function endless_scroll_callback() {
		if (in_callback == true) {
			return;
		}
		jQuery('#white_slider_listing_actual_container_loading').stop().fadeIn();

		in_callback = true;
		if (last_photo_id == undefined) {
			last_photo_id = jQuery('#white_slider_listing_actual_container img:not(.blank):last').attr('photo_id');
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


				var last_large_image = jQuery('#white_slider_listing_actual_container img.blank:last');
				last_large_image.before(image_list_large_html);

				var last_small_image = jQuery('#white_slider_scroll_control_inner img.blank:last');
				last_small_image.before(image_list_small_html);


				if (image_list.has_more == false) {
					cease_fire = true;
				}
			},
			complete: function(jqXHR, textStatus) {
//						console.log ("came into complete");
				jQuery('#white_slider_listing_actual_container_loading').stop().fadeOut(2000);
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

	jQuery(document).ready(function() {
		jQuery('#white_slider_listing_actual_container').scrollLeft(Math.round(jQuery('#white_slider_listing_actual_container')[0].scrollWidth * .40));
		move_to_next_prev_image('right');
		
		jQuery('#white_slider_listing_actual_container').endlessScroll_horizontal({
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

//				jQuery('#white_slider_listing_actual_container, #white_slider_scroll_control_inner').show();
				jQuery('#entire_slider_hider').fadeTo(1000, 0, function() {
					jQuery(this).hide();
				});
			}
		});


		// progress bar is finished loading
		jQuery(document).bind('images_loaded', function(e) {
			setup_image_clicks('#white_slider_scroll_control_inner img:not(.blank)');
		});

		// update progress as images load
		jQuery(document).bind('image_load_progress', function(e, total_progress) {
			jQuery("#image_slider_progressbar").progressbar('value', total_progress);
		});
		// start the preload progress
		jQuery(document).trigger('preload_images_for_progress');
		
	});

	jQuery(window).load(function() {
//		setup the autoscrolling
//		autoscrolling = true;
//		var duration = 100;
//		setInterval(function() {
//		autoscroll(duration);
//		}, duration);
	}).resize(function() {
		calculate_scroll_control_div_width_and_pos();
	});