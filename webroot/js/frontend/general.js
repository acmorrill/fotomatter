if (!window.console) {
	console = {log: function() {}};
}

jQuery(document).ready(function() {
	// code for the menu hover
	jQuery('#main_nav li.main_menu_item').mouseover(function() {
		jQuery('#main_nav li.main_menu_item').removeClass('hover');
		jQuery('#main_nav li.main_menu_item').removeClass('last_hover');
		jQuery(this).addClass('hover');
	}).mouseout(function() {
		jQuery('#main_nav li.main_menu_item').removeClass('hover');
		jQuery(this).addClass('last_hover');
	});

	jQuery('.frontend_form_submit_button').click(function() {
		jQuery(this).closest('form').submit();
	});


	// setup the code for changing the image size on the image page
	jQuery('.sizing_tools .sizing_button').click(function() {
		if (jQuery(this).hasClass('active')) {
			return false;
		}

		var current_size = 'small';
		if (jQuery(this).hasClass('small')) {
			current_size = 'small';
		} else if (jQuery(this).hasClass('medium')) {
			current_size = 'medium';
		} else if (jQuery(this).hasClass('large')) {
			current_size = 'large';
		}


		jQuery.removeCookie("frontend_photo_size");
		jQuery.cookie("frontend_photo_size", current_size, {
			expires : 30,
			path    : '/photos/view_photo'
		});

		var new_location = jQuery(this).attr('data-photo_url');

		document.location.href = new_location;
	});
});
