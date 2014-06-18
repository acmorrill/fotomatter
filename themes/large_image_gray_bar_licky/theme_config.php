<?php

// config for theme: large_image_gray_bar_licky "Your Face"

$theme_config = array(
	'theme_controller_action_layouts' => array(
		'Default' => array(
			'layout' => 'custom_page',
		),
		'SitePages' => array(
			'landing_page' => array(
				'layout' => 'landing',
				'view' => false,
			),
			'custom_page' => array(
				'layout' => 'custom_page',
				'view' => 'custom_page',
			),
		),
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
				'layout' => 'view_photo',
				'view' => false,
			),
		),
		'Ecommerces' => array(
			'view_cart' => array(
				'layout' => 'custom_page',
				'view' => 'view_cart',
			),
			'checkout_login_or_guest' => array(
				'layout' => 'custom_page',
				'view' => 'checkout_login_or_guest',
			),
			'checkout_get_address' => array(
				'layout' => 'custom_page',
				'view' => 'checkout_get_address',
			),
			'checkout_finalize_payment' => array(
				'layout' => 'custom_page',
				'view' => 'checkout_finalize_payment',
			),
			'checkout_thankyou' => array(
				'layout' => 'custom_page',
				'view' => 'checkout_thankyou',
			),
		),
	),
	'admin_config' => array(
		'main_menu' => array(
			'levels' => 2
		),
		'logo_config' => array(
			'available_space' => array(
				'width' => 400,
				'height' => 200
			),
			'available_space_screenshot' => array(
				'absolute_path' => 	PATH_TO_THEMES.DS.'large_image_gray_bar_licky/webroot/img/kent_test_theme_logo_space.jpg', // this image should be max 735 pixels width
				'web_path' => '/img/kent_test_theme_logo_space.jpg',
				'padding' => array(
					'left' => 0,
					'top' => 0,
					'right' => 137,
					'bottom' => 54
				)
			),
			'default_space' => array(
				'width' => 300,
				'height' => 150
			)
		),
		'theme_background_config' => array(
			'theme_has_dynamic_background' => true,
			'overlay_image' => array(
				'absolute_path' => 	PATH_TO_THEMES.DS.'large_image_gray_bar_licky/webroot/img/kent_test_logo.png', // this image should be max 1390 x 953 (DREW TODO - not sure about this)
				'web_path' => '/img/kent_test_logo.png',
				'custom_overlay_transparency_fade' => array(
					'header' => array(
						'tl' => array(
							'x' => 0,
							'y' => 0,
						),
						'br' => array(
							'x' => '*', // NOTE - * means full length or width - also, NOTICE that max width is width minus 1 because the calc starts at 0
							'y' => 90,
						),
						'label' => 'Header',
					),
					'body' => array(
						'tl' => array(
							'x' => 0,
							'y' => 91,
						),
						'br' => array(
							'x' => '*', // NOTE - * means full length or width - also, NOTICE that max width is width minus 1 because the calc starts at 0
							'y' => '*',
						),
						'label' => 'Body',
					),
				),
			),
			'default_bg_image' => array(
				'absolute_path' => 	PATH_TO_THEMES.DS.'andrewmorrill/webroot/Passing-Rain.jpg', // max 1600 width and max 1200 height
				'web_path' => '/Passing-Rain.jpg'
			)
		),
		'theme_avail_custom_settings' => array(
			'settings' => array(
				'landing_page_settings_group' => array(
					'type' => 'group_name',
					'display_name' => 'Landing Page Settings',
				),
				'landing_page_gallery' => array(
					'type' => 'gallery_chooser',
					'display_name' => 'Slideshow Gallery',
					'description' => "Choose which gallery to use for the theme landing page.",
					'help_message' => 'Drop Down Test',
				),
				'landing_page_slideshow_interval_time' => array(
					'type' => 'dropdown',
					'display_name' => 'Slideshow Interval Time',
					'description' => "The amount of time each image is shown.",
					'help_message' => 'Drop Down Test',
					'possible_values' => array(
						'1000' => array( 'display' => '1 second' ),
						'2000' => array( 'display' => '2 seconds' ),
						'4000' => array( 'display' => '4 seconds' ),
						'8000' => array( 'display' => '8 seconds' ),
						'12000' => array( 'display' => '12 seconds' ),
						'16000' => array( 'display' => '16 seconds' ),
						'20000' => array( 'display' => '20 seconds' ),
					),
					'default_value' => '8000',
				),
				'landing_page_slideshow_transition_time' => array(
					'type' => 'dropdown',
					'display_name' => 'Slideshow Transition Time',
					'description' => "The amount of time it takes to transition to the next image.",
					'help_message' => 'Drop Down Test',
					'possible_values' => array(
						'333' => array( 'display' => '1/3 second' ),
						'500' => array( 'display' => '1/2 second' ),
						'1000' => array( 'display' => '1 second' ),
						'2000' => array( 'display' => '2 seconds' ),
						'3000' => array( 'display' => '3 seconds' ),
						'4000' => array( 'display' => '4 seconds' ),
						'5000' => array( 'display' => '5 seconds' ),
					),
					'default_value' => '2000',
				),
				'landing_page_slideshow_max_images' => array(
					'type' => 'numeric_dropdown',
					'display_name' => 'Slideshow Images',
					'description' => "The maximum number of images to show in the slideshow.",
					'help_message' => 'Numeric Dropdown Test',
					'possible_values' => array(
						'min' => 1,
						'max' => 30,
					),
					'default_value' => '8',
				),
				'setting_zero' => array(
					'type' => 'group_name',
					'display_name' => 'Group 1',
				),
				'setting_one' => array(
					'type' => 'on_off',
					'display_name' => 'On Off Test',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
					'help_message' => 'On Off Test',
					'possible_values' => array(
						'on' => array( 'display' => 'On' ),
						'off' => array( 'display' => 'Off' ),
					),
					'default_value' => 'off',
				),
				'setting_two' => array(
					'type' => 'dropdown',
					'display_name' => 'Drop Down Test',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
					'help_message' => 'Drop Down Test',
					'possible_values' => array(
						'option1' => array( 'display' => 'Option 1' ),
						'option2' => array( 'display' => 'Option 2' ),
						'option3' => array( 'display' => 'Option 3' ),
						'option4' => array( 'display' => 'Option 4' ),
					),
					'default_value' => 'option2',
				),
				'setting_twopoint5' => array(
					'type' => 'group_name',
					'display_name' => 'Group 2',
				),
				'setting_three' => array(
					'type' => 'numeric_dropdown',
					'display_name' => 'Numeric Dropdown Test',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
					'help_message' => 'Numeric Dropdown Test',
					'possible_values' => array(
						'min' => 20,
						'max' => 100,
					),
					'default_value' => '45',
				),
				'setting_four' => array(
					'type' => 'radio',
					'display_name' => 'Radio Test',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
					'help_message' => 'Radio Test',
					'possible_values' => array(
						'option1' => array( 'display' => 'Option 1' ),
						'option2' => array( 'display' => 'Option 2' ),
						'option3' => array( 'display' => 'Option 3' ),
						'option4' => array( 'display' => 'Option 4' ),
					),
					'default_value' => 'option3',
				),
				'setting_five' => array(
					'type' => 'color_radio',
					'display_name' => 'Color Radio Test',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
					'help_message' => 'Color Radio Test',
					'possible_values' => array(
						'#53B97D',
						'#FFF400',
						'#FF9F00',
						'#B7001C',
						'#B70086',
					),
					'default_value' => '#B7001C',
				),
				'setting_fivepoint5' => array(
					'type' => 'group_name',
					'display_name' => 'Group 3',
				),
				'setting_six' => array(
					'type' => 'checkboxes',
					'display_name' => 'Checkboxes Test',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
					'help_message' => 'Checkboxes Test',
					'possible_values' => array(
						'option1' => array( 'display' => 'Option 1' ),
						'option2' => array( 'display' => 'Option 2' ),
						'option3' => array( 'display' => 'Option 3' ),
						'option4' => array( 'display' => 'Option 4' ),
						'option5' => array( 'display' => 'Option 5' ),
					),
					'default_value' => 'option5|option4|option2',
				),
				'setting_seven' => array(
					'type' => 'text_input',
					'display_name' => 'Text Input Test',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
					'help_message' => 'Text Input Test',
					'possible_values' => ".*\\\S+.*", // can be regex
					'default_value' => 'default',
				),
				'setting_eight' => array(
					'type' => 'textarea',
					'display_name' => 'Text Area Test',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
					'help_message' => 'Text Area Test',
					'possible_values' => '.*\\\S+.*', // can be regex
					'default_value' => 'default',
				),
			)
		)
	)
	
);
