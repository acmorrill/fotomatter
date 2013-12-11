<?php

// config for theme: white_slider

$theme_config = array(
	'theme_controller_action_layouts' => array(
		'SitePages' => array(
			'landing_page' => array(
				'layout' => 'gallery',
				'view' => false,
			),
			'custom_page' => array(
				'layout' => 'gallery',
				'view' => false,
			),
		),
		'PhotoGalleries' => array(
			'choose_gallery' => array(
				'layout' => 'gallery',
				'view' => false,
			),
			'view_gallery' => array(
				'layout' => 'gallery',
				'view' => false,
			),
		),
		'Photos' => array(
			'view_photo' => array(
				'layout' => 'gallery',
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
				'width' => 398,
				'height' => 99
			),
			'available_space_screenshot' => array(
				'absolute_path' => 	PATH_TO_THEMES.DS.'white_slider/webroot/white_slider_logo_screenshot.jpg',
				'web_path' => '/white_slider_logo_screenshot.jpg',
				'padding' => array(
					'left' => 138,
					'top' => 0,
					'right' => 194,
					'bottom' => 111
				)
			),
			'default_space' => array( // the bounding size of the logo if no size has been specified (should be smaller than available space
				'width' => 300,
				'height' => 90
			)
		),
		'theme_background_config' => array(
			'theme_has_dynamic_background' => false,
		),
		'theme_gallery_listing_config' => array(
			'default_images_per_page' => 15
		)
	)
);