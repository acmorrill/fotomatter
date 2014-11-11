<?php

// config for theme: white angular

$theme_config = array(
	'theme_include_helpers' => array(
		'WhiteAngular'
	),
	'theme_controller_action_layouts' => array(
		'SitePages' => array(
			'landing_page' => array(
				'layout' => 'landing',
				'view' => false,
			),
			'custom_page' => array(
				'layout' => 'landing',
				'view' => false,
			),
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
				'layout' => 'landing',
				'view' => false,
			),
		),
	),
	'admin_config' => array(
		'main_menu' => array(
			'levels' => 1
		),
		'theme_background_config' => array(
			'theme_has_dynamic_background' => false,
		),
		'logo_config' => array(
			'available_space' => array(// the max width and height of the logo (overridden by settings below in the available space screenshot)
				'width' => 400,
				'height' => 200
			),
			'available_space_screenshot' => array(
				'absolute_path' => PATH_TO_THEMES . DS . 'large_image_gray_bar_licky/webroot/img/kent_test_theme_logo_space.jpg', // this image should be max 735 pixels width
				'web_path' => '/img/kent_test_theme_logo_space.jpg',
				'padding' => array(
					'left' => 0,
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
				)
			),
			'default_space' => array(// the bounding size of the logo if no size has been specified (should be smaller than available space
				'width' => 300,
				'height' => 150
			)
		),
		'theme_background_config' => array(
			'theme_has_dynamic_background' => true,
			'overlay_image' => array(
				'absolute_path' => PATH_TO_THEMES . DS . 'f32_dynamic_background/webroot/img/transparent_bg.png', // this image should be max 1390 x 953 (DREW TODO - not sure about this)
				'web_path' => '/img/transparent_bg.png',
				'custom_overlay_transparency_fade' => array(
					'header' => array(
						'tl' => array(
							'x' => 0,
							'y' => 0,
						),
						'br' => array(
							'x' => '*', // NOTE - * means full length or width - also, NOTICE that max width is width minus 1 because the calc starts at 0
							'y' => 149,
						),
						'label' => 'Header',
					),
					'body' => array(
						'tl' => array(
							'x' => 0,
							'y' => 149,
						),
						'br' => array(
							'x' => '*', // NOTE - * means full length or width - also, NOTICE that max width is width minus 1 because the calc starts at 0
							'y' => 622,
						),
						'label' => 'Body',
					),
				),
			),
			'default_bg_image' => array(
				'absolute_path' => PATH_TO_THEMES . DS . 'f32_dynamic_background/webroot/img/passing-rain.jpg', // max 1600 width and max 1200 height
				'web_path' => '/img/passing-rain.jpg'
			)
		),
		'theme_gallery_listing_config' => array(
			'default_images_per_page' => 8
		),
		'theme_avail_custom_settings' => array(
			'settings' => array(
				'landing_page_settings_group' => array(
					'type' => 'group_name',
					'display_name' => 'Landing Page Settings',
				),
				'landing_page_gallery' => array(
					'type' => 'gallery_chooser',
					'display_name' => 'Slider Gallery',
					'description' => "Choose which gallery to use for the theme landing page.",
					'help_message' => 'Drop Down Test',
				),
			)
		)
	)
);
