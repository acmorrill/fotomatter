<?php

// config for theme: simple_lightgrey_textured

$theme_config = array(
	'theme_controller_action_layouts' => array(
		'SitePages' => array(
			'landing_page' => array(
				'layout' => 'landing',
				'view' => false,
			),
		),
		'PhotoGalleries' => array(
			'choose_gallery' => false,
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
		)
	),
	'admin_config' => array(
		'main_menu' => array(
			'levels' => 2
		),
		'logo_config' => array(
			'available_space' => array(
				'width' => 338,
				'height' => 71
			),
			'available_space_screenshot' => array(
				'absolute_path' => 	PATH_TO_THEMES.DS.'simple_lightgrey_textured/webroot/simple_lightgrey_textured_theme_logo_space.jpg', // this image should be max 735 pixels width
				'web_path' => '/simple_lightgrey_textured_theme_logo_space.jpg',
				'padding' => array(
					'left' => 114,
					'top' => 0,
					'right' => 119,
					'bottom' => 96
				)
			),
			'default_space' => array(
				'width' => 300,
				'height' => 80
			)
		),
		'theme_background_config' => array(
			'theme_has_dynamic_background' => false
		),
		'theme_gallery_listing_config' => array(
			'default_images_per_page' => 12
		)
	)
	
);
