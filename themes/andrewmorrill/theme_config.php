<?php

// config for theme: andrewmorrill

$theme_config = array(
	'theme_controller_action_layouts' => array(
		'SitePages' => array(
			'landing_page' => 'landing',
			'custom_page' => 'custom_page'
		),
		'PhotoGalleries' => array(
			'choose_gallery' => 'gallery_landing',
			'view_gallery' => 'gallery'
		),
		'Photos' => array(
			'view_photo' => 'view_photo'
		)
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
					'left' => 89,
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
				'absolute_path' => 	PATH_TO_THEMES.DS.'andrewmorrill/webroot/test_bg.png', // this image should be max 1390 x 953 (DREW TODO - not sure about this)
				'web_path' => '/test_bg.png'
			),
			'default_bg_image' => array(
				'absolute_path' => 	PATH_TO_THEMES.DS.'andrewmorrill/webroot/Passing-Rain.jpg', // max 1600 width and max 1200 height
				'web_path' => '/Passing-Rain.jpg'
			)
		),
		'theme_avail_custom_settings' => array(
			'settings' => array(
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
