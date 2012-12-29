<?php

// config for theme: default

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
			),
			'reusable_settings' => array(
				'setting_one' => array(
					
				),
				'setting_two' => array(
					
				)
			),
			'settings' => array(
//				example settings
//				'setting_one' => array(
//					'type' => 'on_off',
//					'display_name' => 'On Off Test',
//					'description' => 'On Off Test',
//					'help_message' => 'On Off Test',
//					'possible_values' => array(
//						'on' => array( 'display' => 'On' ),
//						'off' => array( 'display' => 'Off' ),
//					),
//				),
//				'setting_two' => array(
//					'type' => 'dropdown',
//					'display_name' => 'Drop Down Test',
//					'description' => 'Drop Down Test',
//					'help_message' => 'Drop Down Test',
//					'possible_values' => array(
//						'option1' => array( 'display' => 'Option 1' ),
//						'option2' => array( 'display' => 'Option 2' ),
//						'option3' => array( 'display' => 'Option 3' ),
//						'option4' => array( 'display' => 'Option 4' ),
//					),
//				),
//				'setting_three' => array(
//					'type' => 'numeric_dropdown',
//					'display_name' => 'Numeric Dropdown Test',
//					'description' => 'Numeric Dropdown Test',
//					'help_message' => 'Numeric Dropdown Test',
//					'possible_values' => array(
//						'min' => 20,
//						'max' => 100,
//					),
//				),
//				'setting_four' => array(
//					'type' => 'radio',
//					'display_name' => 'Radio Test',
//					'description' => 'Radio Test',
//					'help_message' => 'Radio Test',
//					'possible_values' => array(
//						'option1' => array( 'display' => 'Option 1' ),
//						'option2' => array( 'display' => 'Option 2' ),
//						'option3' => array( 'display' => 'Option 3' ),
//						'option4' => array( 'display' => 'Option 4' ),
//					),
//				),
//				'setting_five' => array(
//					'type' => 'color_radio',
//					'display_name' => 'Color Radio Test',
//					'description' => 'Color Radio Test',
//					'help_message' => 'Color Radio Test',
//					'possible_values' => array(
//						'#53B97D',
//						'#FFF400',
//						'#FF9F00',
//						'#B7001C',
//						'#B70086',
//					),
//				),
//				'setting_six' => array(
//					'type' => 'checkboxes',
//					'display_name' => 'Checkboxes Test',
//					'description' => 'Checkboxes Test',
//					'help_message' => 'Checkboxes Test',
//					'possible_values' => array(
//						'option1' => array( 'display' => 'Option 1' ),
//						'option2' => array( 'display' => 'Option 2' ),
//						'option3' => array( 'display' => 'Option 3' ),
//						'option4' => array( 'display' => 'Option 4' ),
//						'option5' => array( 'display' => 'Option 5' ),
//					),
//				),
//				'setting_seven' => array(
//					'type' => 'text_input',
//					'display_name' => 'Text Input Test',
//					'description' => 'Text Input Test',
//					'help_message' => 'Text Input Test',
//					'possible_values' => '/[\s\S]/', // can be regex
//				),
//				'setting_eight' => array(
//					'type' => 'textarea',
//					'display_name' => 'Text Area Test',
//					'description' => 'Text Area Test',
//					'help_message' => 'Text Area Test',
//					'possible_values' => '/[\s\S]/', // can be regex
//				),
			)
		)
	)
);