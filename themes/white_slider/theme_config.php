<?php

// config for theme: default

$theme_config = array(
	'theme_controller_action_layouts' => array(
		'SitePages' => array(
			'landing_page' => 'gallery',
			'custom_page' => 'gallery'
		),
		'PhotoGalleries' => array(
			'choose_gallery' => 'gallery',
			'view_gallery' => 'gallery'
		),
		'Photos' => array(
			'view_photo' => 'gallery'
		)
	),
	'admin_config' => array(
		'main_menu' => array(
			'levels' => 2
		),
		'logo_config' => array(
			'available_space' => array(
				'width' => 400,
				'height' => 100
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
			'theme_has_dynamic_background' => false,
		),
	)
);