<?php

// config for theme: simple_lightgrey_textured

$theme_config = array(
	'theme_name' => 'simple_lightgrey_textured',
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
		),
		'Ecommerces' => array(
			'view_cart' => array(
				'layout' => 'custom_page',
				'view' => 'view_cart',
			),
			'checkout_login_or_guest' => array(
				'layout' => 'custom_page',
				'view' => 'checkout_login_or_guest',
			),
			'checkout_get_address' => array(
				'layout' => 'custom_page',
				'view' => 'checkout_get_address',
			),
			'checkout_finalize_payment' => array(
				'layout' => 'custom_page',
				'view' => 'checkout_finalize_payment',
			),
			'checkout_thankyou' => array(
				'layout' => 'custom_page',
				'view' => 'checkout_thankyou',
			),
		),
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
		'theme_user_chosen_background' => array(
			'theme_has_user_chosen_background' => true,
			'repeating_background' => true,
			'background_path_theme_setting_name' => 'site_repeated_background',
		),
		'theme_avail_custom_settings' => array(
			'settings' => array(
				'landing_page_settings_group' => array(
					'type' => 'group_name',
					'display_name' => 'General Settings',
				),
				'landing_page_gallery' => array(
					'type' => 'gallery_chooser',
					'display_name' => 'Slideshow Gallery',
					'description' => "Choose which gallery to use for the theme landing page.",
					'help_message' => 'Drop Down Test',
				),
				'site_repeated_background' => array(
					'type' => 'small_image_radio',
					'display_name' => 'Theme Background Texture',
					'description' => "Choose the texture that will be used for the background on your website.",
					'help_message' => 'Small Image Radio Test',
					'possible_values' => array(
						'/img/gray_bg/grey_wash_wall_1.png', // NOTE: there is a leading /
						'/img/gray_bg/grey_wash_wall_2.png',
						'/img/gray_bg/grey_wash_wall_3.png',
						'/img/gray_bg/grey_wash_wall_4.png',
						'/img/gray_bg/grey_wash_wall_5.png',
						'/img/gray_bg/grey_wash_wall_6.png',
						'/img/gray_bg/grey_wash_wall_7.png',
						'/img/gray_bg/grey_wash_wall_8.png',
						'/img/gray_bg/cloth_1.png', 
						'/img/gray_bg/cloth_2.png',
						'/img/gray_bg/cloth_3.png',
						'/img/gray_bg/cloth_4.png',
						'/img/gray_bg/cloth_5.png',
					),
					'default_value' => '/img/gray_bg/grey_wash_wall_7.png',
				),
				'show_white_border' => array(
					'type' => 'on_off',
					'display_name' => 'White Border',
					'description' => "If set to on the images displayed in the theme will have a white border.",
					'possible_values' => array(
						'on' => array( 'display' => 'On' ),
						'off' => array( 'display' => 'Off' ),
					),
					'default_value' => 'off',
				),
			),
		),
		'theme_gallery_listing_config' => array(
			'default_images_per_page' => 12
		),
	)
	
);
