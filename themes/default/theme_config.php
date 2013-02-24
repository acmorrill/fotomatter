<?php

// config for theme: default

$theme_config = array(
	'theme_include_helpers' => array(),
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
				'absolute_path' => 	'',
				'web_path' => '',
				'padding' => array(
					'left' => 0,
					'top' => 0,
					'right' => 0,
					'bottom' => 0
				)
			),
			'default_space' => array( // the bounding size of the logo if no size has been specified (should be smaller than available space
				'width' => 300,
				'height' => 150
			)
		),
		'theme_background_config' => array(
			'theme_has_dynamic_background' => true,
			'overlay_image' => array(
				'absolute_path' => 	'',
				'web_path' => ''
			),
			'default_bg_image' => array(
				'absolute_path' => 	'', // max 1600 width and max 1200 height
				'web_path' => ''
			)
		),
		'theme_gallery_listing_config' => array(
			'default_images_per_page' => 8
		),
		'theme_avail_custom_settings' => array(
			'valid_setting_types' => array('on_off', 'dropdown', 'numeric_dropdown', 'radio', 'color_radio', 'checkboxes', 'text_input', 'textarea'),
			'default_setting_values' => array(
				'type' => 'on_off',
				'display_name' => 'Display Name',
				'description' => 'A theme setting',
				'help_message' => 'A theme setting',
				'possible_values' => array(
					'on' => array( 'display' => 'On' ),
					'off' => array( 'display' => 'Off' ),
				),
				'default_value' => 'off',
			),
			// this is currently not used
//			'reusable_settings' => array(
//				'setting_one' => array(
//					
//				),
//				'setting_two' => array(
//					
//				)
//			),
			'settings' => array(
				// example theme settings
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