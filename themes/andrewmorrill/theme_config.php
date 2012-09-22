<?php


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
				'absolute_path' => 	PATH_TO_THEMES.DS.'andrewmorrill/webroot/andrew_morrill_theme_logo_space.jpg',
				'web_path' => '/andrew_morrill_theme_logo_space.jpg'
			),
			'default_space' => array(
				'width' => 300,
				'height' => 150
			)
		)
	)
);
