function load_gallery(photo_gallery_id) {
	var animation_complete = false;
	jQuery('#image_slider_container .left_cover_image, #image_slider_container .right_cover_image').each(function() {
		var left = jQuery(this).attr('closed_left');

		jQuery(this).animate({
			left: left
		}, {queue: false, duration: 500, complete: function() {
			if (animation_complete == false) {
				animation_complete = true;
				window.location.href = '/photo_galleries/view_gallery/'+photo_gallery_id+'/';
			}
		}});
	});
}

function show_gallery_info(photo_gallery_id) {
	jQuery('#slider_info_container .curr_gallery_info').hide();
	jQuery('#slider_info_container .curr_gallery_info[photo_gallery_id='+photo_gallery_id+']').show();
}


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

			if (no_open == false) {
				open_image(image, 0);
			}
		} else {
			image_slider_container.animate({
				top: top_str,
				left: left_str
			}, {queue: false, duration: speed, easing: 'swing', complete: function() {
				if (no_open == false) {
					open_image(image, 0);
				}
			}});
		
		}
		
		if ( typeof show_gallery_info == 'function' && image.attr('photo_gallery_id') !== undefined ) { 
			show_gallery_info( image.attr('photo_gallery_id') );
		}
	}

}

function open_image(image, animation_time) {
	jQuery('.curr_image_info_cont', image).hide();

	jQuery(image).prev().addClass('before_open_image');
	jQuery(image).addClass('open_image');

	var img_height = parseInt(jQuery(image).attr('img_height'));
	var img_width = parseInt(jQuery(image).attr('img_width'));
	if (animation_time == undefined) {
		animation_time = 700;
	}
	var start_left_cover = -696;
	var start_right_cover = 428;
	var cover_move_distance = 700;
	var left_cover = start_left_cover - cover_move_distance;
	var right_cover = start_right_cover + cover_move_distance;
	var top_increase = Math.abs(img_height - 300);
	var left_increase = (8749.78674 * top_increase) / -10850;

	var left_min_position = 190; // distance from left screen edge that image can't go beyond
	var right_min_position = 350; // distance from right screen edge that image can't go beyond
	var image_offset = image.offset();
	var image_left = image_offset.left;
	var left_margin = -Math.abs(image_left - left_min_position);


	var screen_width = null;
	var image_width = null;
	var image_center = null;
	var center_position = null;
	var alt_left_margin = null;

	var max_margin = 0;

	if (animation_time == 0) {
		jQuery('.left_cover_image', image).stop().css({
			left: left_cover
		});

		jQuery('.right_cover_image', image).stop().css({
			left: right_cover
		});


		jQuery(image).css({
			height: img_height,
			width: img_width + 20
		});

		jQuery('.img_outer_cont', image).css({
			height: img_height,
			width: img_width + 20
		});


		screen_width = jQuery(window).width();
		image_width = img_width + 20;
		image_center = image_left + (image_width / 2);
		center_position = left_min_position + (((screen_width - right_min_position) - left_min_position) / 2);
//		console.log ("===============================================");
//		console.log ('image_center: '+image_center);
//		console.log ('center_position: '+center_position);
		alt_left_margin = center_position - image_center;
//		console.log ('left_margin: '+left_margin);
//		console.log ('alt_left_margin: '+alt_left_margin);
//		console.log ("===============================================");
		if (alt_left_margin > left_margin) {
			left_margin = alt_left_margin;
		}
		if (left_margin > max_margin) {
			left_margin = max_margin;
		}

		jQuery(image).css({
			height: img_height,
			width: 3000
		});

		jQuery(image).css({
			marginLeft: left_margin+'px'
		});

		jQuery(image).nextAll().css({
			marginLeft: left_increase+'px'
		});

		jQuery('.curr_image_info_cont', image).show();
	} else {
		jQuery('.left_cover_image', image).stop().animate({
			left: left_cover
		}, {queue: false, duration: animation_time});

		jQuery('.right_cover_image', image).stop().animate({
			left: right_cover
		}, {queue: false, duration: animation_time});

		jQuery(image).animate({
			height: img_height,
			width: img_width + 20
		}, {queue: false, duration: animation_time, complete: function() {
			jQuery(image).css({
				width: 3000
			});
			jQuery('.curr_image_info_cont', image).fadeIn('fast');
		}});


		jQuery('.img_outer_cont', image).animate({
			height: img_height,
			width: img_width + 20
		}, {queue: false, duration: animation_time, complete: function() {
				jQuery(this).css('overflow', '');
		}});


		screen_width = jQuery(window).width();
		image_width = img_width + 20;
		image_center = image_left + (image_width / 2);
		center_position = left_min_position + (((screen_width - right_min_position) - left_min_position) / 2);
		alt_left_margin = center_position - image_center;
		if (alt_left_margin > left_margin) {
			left_margin = alt_left_margin;
		}
		if (left_margin > max_margin) {
			left_margin = max_margin;
		}


		jQuery(image).animate({
			marginLeft: left_margin
		}, {queue: false, duration: animation_time});

		jQuery(image).nextAll().animate({
			marginLeft: left_increase
		}, {queue: false, duration: animation_time});
	}

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
	var cover_move_distance = 700;
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
		width: 720
	});

	jQuery('.img_outer_cont', image).css({
		height: 300,
		width: 720
	});

	jQuery(image).css({
		marginLeft: 0
	});

	jQuery(image).nextAll().css({
		marginLeft: 0
	});
}


function scroll_to_next_image(no_open, speed) {
	if (no_open == undefined) {
		no_open = false;
	}
	
	if (speed == undefined) {
		speed = 300;
	}
	
	var current_image = jQuery('#image_slider_container .float_image_cont.current_image');
	var next_image = current_image.prev();
	if (next_image.hasClass('actual_image')) {
		scroll_to_image(next_image, speed, false, no_open);
	} else {
		var first_image = jQuery('#image_slider_container .float_image_cont.first');
		var before_first_image = first_image.next();
		scroll_to_image(before_first_image, 0, true, no_open);
		scroll_to_image(first_image, speed, false, no_open);
	}
}

function scroll_to_prev_image(no_open) {
	if (no_open == undefined) {
		no_open = false;
	}
	
	var current_image = jQuery('#image_slider_container .float_image_cont.current_image');
	var prev_image = current_image.next();
	if (prev_image.hasClass('actual_image')) {
		scroll_to_image(prev_image, 300, false, no_open);
	} else {
		var last_image = jQuery('#image_slider_container .float_image_cont.last');
		var after_last_image = last_image.prev();
		scroll_to_image(after_last_image, 0, true, no_open);
		scroll_to_image(last_image, 300, false, no_open);
	}
}


function bootstrap() { 
	jQuery('#image_slider_container').css({
		opacity: 100
	});

	
	// update progress as images load
	jQuery(document).bind('image_load_progress', function(e, total_progress) {
		jQuery("#progress_bar .ui-progressbar-value").height(total_progress+'%');
		jQuery("#progress_bar .percent_text span").text(total_progress);
		jQuery("#progress_bar").hide();
		jQuery("#progress_bar").show();
	});
	// start the preload progress
	jQuery(document).trigger('preload_images_for_progress');
}

function scroll_to_second_to_second_image(no_open) {
	if (no_open == undefined) {
		no_open = false;
	}
	
	// find the second to last image and scroll to it at the beginning
	var first_image = jQuery('#image_slider_container .float_image_cont.first');
	var second_to_last_image;
	if (no_open == false && first_image.hasClass('last')) {
		second_to_last_image = first_image;
	} else {
		second_to_last_image = first_image.prev();
	}
	scroll_to_image(second_to_last_image, 0, true, no_open);
}


jQuery(document).ready(function() {
	jQuery('#image_slider_container').css({
		opacity: 0
	});

	scroll_to_second_to_second_image(true);
	
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
	
});

