<?php

// config for theme: andrewmorrill

$theme_config = array(
	'theme_controller_action_layouts' => array(
		'SitePages' => array(
			'landing_page' => 'landing'
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
		)
	)
	
);
