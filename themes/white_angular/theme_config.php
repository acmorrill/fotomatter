<?php

// config for theme: white angular

$theme_config = array(
	'theme_name' => 'white_angular',
	'theme_include_helpers' => array(
		'WhiteAngular'
	),
	'theme_controller_action_layouts' => array(
		'PhotoGalleries' => array(
			'choose_gallery' => array(
				'layout' => 'gallery_landing',
				'view' => false,
			),
			'view_gallery' => array(
				'layout' => 'gallery',
				'view' => false,
			),
		),
		'Photos' => array(
			'view_photo' => array(
				'layout' => 'landing',
				'view' => false,
			),
		),
	),
	'admin_config' => array(
		'main_menu' => array(
			'levels' => 1
		),
		'theme_background_config' => array(
			'theme_has_dynamic_background' => false,
		),
		'logo_config' => array(
			'available_space' => array(// the max width and height of the logo (overridden by settings below in the available space screenshot)
				'width' => 637,
				'height' => 159
			),
			'available_space_screenshot' => array(
				'absolute_path' => PATH_TO_THEMES . DS . 'white_angular/webroot/img/logo_screenshot.jpg', // this image should be max 735 pixels width
				'web_path' => '/img/logo_screenshot.jpg',
				'padding' => array(
					'left' => 0,
					'top' => 0,
					'right' => 542,
					'bottom' => 281,
				)
			),
			'default_space' => array(// the bounding size of the logo if no size has been specified (should be smaller than available space
				'width' => 300,
				'height' => 150
			)
		),
		'theme_gallery_listing_config' => array(
			'default_images_per_page' => 50,
			'based_on_theme_option' => 'max_gallery_images'
		),
		'theme_avail_custom_settings' => array(
			'settings' => array(
				'landing_page_settings_group' => array(
					'type' => 'group_name',
					'display_name' => 'Home Page Settings',
				),
				'landing_page_gallery' => array(
					'type' => 'gallery_chooser',
					'display_name' => 'Slider Gallery',
					'description' => "Choose which of your galleries you want to display on your site’s home page.",
					'help_message' => 'Drop Down Test',
				),
				'landing_page_slideshow_interval_time' => array(
					'type' => 'dropdown',
					'display_name' => 'Slideshow Interval Time',
					'description' => "The amount of time the home page images are shown before sliding down.",
					'help_message' => 'Drop Down Test',
					'possible_values' => array(
						'1000' => array( 'display' => '1 second' ),
						'2000' => array( 'display' => '2 seconds' ),
						'3000' => array( 'display' => '3 seconds' ),
						'4000' => array( 'display' => '4 seconds' ),
						'8000' => array( 'display' => '8 seconds' ),
						'12000' => array( 'display' => '12 seconds' ),
						'16000' => array( 'display' => '16 seconds' ),
						'20000' => array( 'display' => '20 seconds' ),
					),
					'default_value' => '3000',
				),
				'landing_page_slideshow_transition_time' => array(
					'type' => 'dropdown',
					'display_name' => 'Slideshow Transition Time',
					'description' => "The speed of the transition animation.",
					'help_message' => 'Drop Down Test',
					'possible_values' => array(
						'500' => array( 'display' => '1/2 second' ),
						'1000' => array( 'display' => '1 second' ),
						'2000' => array( 'display' => '2 seconds' ),
						'3000' => array( 'display' => '3 seconds' ),
						'4000' => array( 'display' => '4 seconds' ),
						'5000' => array( 'display' => '5 seconds' ),
						'7000' => array( 'display' => '7 seconds' ),
						'10000' => array( 'display' => '10 seconds' ),
						'15000' => array( 'display' => '15 seconds' ),
					),
					'default_value' => '2000',
				),
				'landing_page_slideshow_max_images' => array(
					'type' => 'numeric_dropdown',
					'display_name' => 'Slideshow Images',
					'description' => "The maximum number of images to show on the home page slideshow.",
					'help_message' => 'Numeric Dropdown Test',
					'possible_values' => array(
						'min' => 1,
						'max' => 30,
					),
					'default_value' => '8',
				),
				'landing_page_into_text' => array(
					'type' => 'textarea',
					'display_name' => 'Landing Page Intro Text',
					'description' => "The text that shows up on the home page next to the slideshow.",
					'help_message' => 'Text Area Test',
					'possible_values' => '.*\\\S+.*', // can be regex
					'default_value' => 'Welcome to my online gallery. To purchase prints, navigate to an image in the image galleries section. Thank you for visting. Enjoy!',
				),
				'gallery_settings_group' => array(
					'type' => 'group_name',
					'display_name' => 'Gallery Settings',
				),
				'max_gallery_images' => array(
					'type' => 'dropdown',
					'display_name' => 'Max Gallery Images',
					'description' => "The maximum number of images that will be shown in each gallery. If a gallery has more images than the selected option the other images will simply not load. We recommend no more than 50 so that the gallery load time won't be too high, but we leave it up to you.",
					'possible_values' => array(
						'50' => array( 'display' => '50' ),
						'75' => array( 'display' => '75' ),
						'100' => array( 'display' => '100' ),
						'150' => array( 'display' => '150' ),
						'200' => array( 'display' => '200' ),
						'250' => array( 'display' => '250' ),
					),
					'default_value' => '50',
				),
			)
		)
	)
);
