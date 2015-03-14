<?php

// config for theme: white_slider

$theme_config = array(
	'theme_name' => 'white_slider',
	'theme_controller_action_layouts' => array(
		'PhotoGalleries' => array(
			'choose_gallery' => array(
				'layout' => false,
				'view' => false,
			),
			'view_gallery' => array(
				'layout' => 'gallery',
				'view' => false,
			),
		),
		'Photos' => array(
			'view_photo' => array(
				'layout' => false,
				'view' => false,
			),
		)
	),
	'admin_config' => array(
		'main_menu' => array(
			'levels' => 2
		),
		'logo_config' => array(
			'available_space' => array(
				'width' => 398,
				'height' => 99
			),
			'available_space_screenshot' => array(
				'absolute_path' => 	PATH_TO_THEMES.DS.'white_slider/webroot/white_slider_logo_screenshot.jpg',
				'web_path' => '/white_slider_logo_screenshot.jpg',
				'padding' => array(
					'left' => 138,
					'top' => 0,
					'right' => 194,
					'bottom' => 111
				)
			),
			'default_space' => array( // the bounding size of the logo if no size has been specified (should be smaller than available space
				'width' => 300,
				'height' => 90
			)
		),
		'theme_background_config' => array(
			'theme_has_dynamic_background' => false,
		),
		'theme_gallery_listing_config' => array(
			'default_images_per_page' => 30,
			'based_on_theme_option' => 'max_gallery_images'
		),
		'theme_avail_custom_settings' => array(
			'settings' => array(
				'landing_page_settings_group' => array(
					'type' => 'group_name',
					'display_name' => 'Landing Page Settings',
				),
				'landing_page_gallery' => array(
					'type' => 'gallery_chooser',
					'display_name' => 'Slider Gallery',
					'description' => "Choose which gallery to use for the theme landing page.",
					'help_message' => 'Drop Down Test',
				),
				'max_gallery_images' => array(
					'type' => 'numeric_dropdown',
					'display_name' => 'Slideshow Images',
					'description' => "The maximum number of preloaded images show on the landing page and in galleries. Additional load as you scroll.",
					'help_message' => 'Numeric Dropdown Test',
					'possible_values' => array(
						'min' => 1,
						'max' => 30,
					),
					'default_value' => '15',
				),
			),
		),
	)
);