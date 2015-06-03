<?php

// config for theme: andrewmorrill

$theme_config = array(
	'merge_testing' => array(
		'merge_testing1' => array(
			'merge_testing2_1' => array(
				'merge_testing3' => true
			),
			'merge_testing2_2' => 'just a value',
			'merge_testing2_3' => array(
				'override_able' => true,
				'merge_testing4' => true,
				'merge_testing5' => array(
					'merge_testing8' => true
				),
				'merge_testing7' => true,
				'merge_testing9' => array(
					'merge_testing10' => true,
					'merge_testing11' => true,
					'merge_testing12' => array(
						'merge_testing13' => true,
						'merge_testing14' => true,
					),
				),
				'merge_testing66' => true,
			),
		),
	),
	'theme_name' => 'andrewmorrill',
	'theme_controller_action_layouts' => array(
		'Default' => array(
			'layout' => 'custom_page',
		),
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
				'layout' => 'view_photo',
				'view' => false,
			),
		),
	),
	'admin_config' => array(
		'main_menu' => array(
			'levels' => 1
		),
		'logo_config' => array(
			'available_space' => array(
				'width' => 400,
				'height' => 200
			),
			'available_space_screenshot' => array(
				'absolute_path' => 	PATH_TO_THEMES.DS.'andrewmorrill/webroot/andrew_morrill_theme_logo_space.jpg', // this image should be max 735 pixels width
				'web_path' => '/andrew_morrill_theme_logo_space.jpg',
				'padding' => array(
					'left' => 0,
					'top' => 0,
					'right' => 179,
					'bottom' => 260
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
				'absolute_path' => 	PATH_TO_THEMES.DS.'andrewmorrill/webroot/test_bg.png', // this image should be max 1390 x 953 (DREW TODO - not sure about this)
				'web_path' => '/test_bg.png',
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
				'absolute_path' => 	PATH_TO_THEMES.DS.'andrewmorrill/webroot/Passing-Rain.jpg', // between 2000 and no more than 3000
				'web_path' => '/Passing-Rain.jpg'
			),
			'default_bg_color' => '#e4e8ee',
		),
		'theme_avail_custom_settings' => array(
			'settings' => array(
				'landing_page_settings_group' => array(
					'type' => 'group_name',
					'display_name' => 'Home Page Settings',
				),
				'landing_page_gallery' => array(
					'type' => 'gallery_chooser',
					'display_name' => 'Slideshow Gallery',
					'description' => "This theme includes a slideshow of your chosen images on the homepage. Choose which gallery of photos you want to display. To create a gallery (such as weddings, seascapes, architecture, etc.) select “Galleries” from the top menu.",
					'help_message' => 'Drop Down Test',
				),
				'landing_page_slideshow_interval_time' => array(
					'type' => 'dropdown',
					'display_name' => 'Slideshow Interval Time',
					'description' => "This is the amount of time each image is displayed in the slideshow. Choose a time and click on “Live Site” to see the result.",
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
					'description' => "Choose how long you want it to take to transition to the next image in the slideshow. ",
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
				'landing_page_into_text' => array(
					'type' => 'textarea',
					'display_name' => 'Landing Page Intro Text',
					'description' => "This is the text that appears on the landing page next to the slideshow. Change the text to make it your own.",
					'help_message' => 'Text Area Test',
					'possible_values' => '.*\\\S+.*', // can be regex
					'default_value' => 'Welcome to my online gallery. To purchase prints, navigate to an image in the image galleries section. Thank you for visting. Enjoy!',
				),
//				'setting_zero' => array(
//					'type' => 'group_name',
//					'display_name' => 'Group 1',
//				),
//				'setting_one' => array(
//					'type' => 'on_off',
//					'display_name' => 'On Off Test',
//					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
//					'help_message' => 'On Off Test',
//					'possible_values' => array(
//						'on' => array( 'display' => 'On' ),
//						'off' => array( 'display' => 'Off' ),
//					),
//					'default_value' => 'off',
//				),
//				'setting_two' => array(
//					'type' => 'dropdown',
//					'display_name' => 'Drop Down Test',
//					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
//					'help_message' => 'Drop Down Test',
//					'possible_values' => array(
//						'option1' => array( 'display' => 'Option 1' ),
//						'option2' => array( 'display' => 'Option 2' ),
//						'option3' => array( 'display' => 'Option 3' ),
//						'option4' => array( 'display' => 'Option 4' ),
//					),
//					'default_value' => 'option2',
//				),
//				'setting_twopoint5' => array(
//					'type' => 'group_name',
//					'display_name' => 'Group 2',
//				),
//				'setting_three' => array(
//					'type' => 'numeric_dropdown',
//					'display_name' => 'Numeric Dropdown Test',
//					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
//					'help_message' => 'Numeric Dropdown Test',
//					'possible_values' => array(
//						'min' => 20,
//						'max' => 100,
//					),
//					'default_value' => '45',
//				),
//				'setting_four' => array(
//					'type' => 'radio',
//					'display_name' => 'Radio Test',
//					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
//					'help_message' => 'Radio Test',
//					'possible_values' => array(
//						'option1' => array( 'display' => 'Option 1' ),
//						'option2' => array( 'display' => 'Option 2' ),
//						'option3' => array( 'display' => 'Option 3' ),
//						'option4' => array( 'display' => 'Option 4' ),
//					),
//					'default_value' => 'option3',
//				),
//				'setting_five' => array(
//					'type' => 'color_radio',
//					'display_name' => 'Color Radio Test',
//					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
//					'help_message' => 'Color Radio Test',
//					'possible_values' => array(
//						'#53B97D',
//						'#FFF400',
//						'#FF9F00',
//						'#B7001C',
//						'#B70086',
//					),
//					'default_value' => '#B7001C',
//				),
//				'setting_fivepoint5' => array(
//					'type' => 'group_name',
//					'display_name' => 'Group 3',
//				),
//				'setting_six' => array(
//					'type' => 'checkboxes',
//					'display_name' => 'Checkboxes Test',
//					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
//					'help_message' => 'Checkboxes Test',
//					'possible_values' => array(
//						'option1' => array( 'display' => 'Option 1' ),
//						'option2' => array( 'display' => 'Option 2' ),
//						'option3' => array( 'display' => 'Option 3' ),
//						'option4' => array( 'display' => 'Option 4' ),
//						'option5' => array( 'display' => 'Option 5' ),
//					),
//					'default_value' => 'option5|option4|option2',
//				),
//				'setting_seven' => array(
//					'type' => 'text_input',
//					'display_name' => 'Text Input Test',
//					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
//					'help_message' => 'Text Input Test',
//					'possible_values' => ".*\\\S+.*", // can be regex
//					'default_value' => 'default',
//				),
//				'setting_eight' => array(
//					'type' => 'textarea',
//					'display_name' => 'Text Area Test',
//					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
//					'help_message' => 'Text Area Test',
//					'possible_values' => '.*\\\S+.*', // can be regex
//					'default_value' => 'default',
//				),
			)
		)
	)
	
);
