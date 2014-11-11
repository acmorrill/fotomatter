<?php

// config for theme: grezzo

$theme_config = array(
	'theme_controller_action_layouts'=>array(
		'SitePages'=>array(
			'landing_page'=>array(
				'layout'=>'gallery',
				'view'=>false
			),
			'custom_page'=>array(
				'layout'=>'custom_page',
				'view'=>'custom_page'
			)
		),
		'PhotoGalleries' => array(
			'view_gallery' => array(
				'layout' => 'gallery',
				'view' => false,
			),
		),
		'Ecommerces' => array(
			'view_cart' => array(
				'layout' => 'cart',
				'view' => 'view_cart',
			),
			'checkout_login_or_guest' => array(
				'layout' => 'cart',
				'view' => 'checkout_login_or_guest',
			),
			'checkout_get_address' => array(
				'layout' => 'cart',
				'view' => 'checkout_get_address',
			),
			'checkout_finalize_payment' => array(
				'layout' => 'cart',
				'view' => 'checkout_finalize_payment',
			),
			'checkout_thankyou' => array(
				'layout' => 'cart',
				'view' => 'checkout_thankyou',
			),
		)
	),
	'admin_config'=>array(
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
		'main_menu'=>array(
			'levels'=>2
		),
		
		'theme_avail_custom_settings' => array(
			'settings' => array(
				'landing_page_settings_group' => array(
                    'type' => 'group_name',
                    'display_name' => 'Global theme settings',
                ),
				'background_color' => array(
					'type' => 'dropdown',
					'display_name' => 'Background Color',
					'description' => "Select the background color of the theme.",
					'help_message' => "Background color test",
					'possible_values' => array(
						'black' => array('display' => 'Black'),
						'blue' => array('display' => 'Blue'),
						'yellow' => array('display' => 'Yellow'),
						'pink' => array('display' => 'Pink'),
					),
					'default_value' => 'black'
				),
									
				'header_is_full_width' => array(
					'type' => 'on_off',
					'display_name' => 'Header is Full Width',
					'description' => "The menu and logo image will ether be the width of the full page or appear closer to the center of the page.",
					'help_message' => 'On Off Test',
					'possible_values' => array(
						'on' => array( 'display' => 'On' ),
						'off' => array( 'display' => 'Off' ),
					),
					'default_value' => 'on',
				),
				
				'gallery_selection' => array(
					'type' => 'gallery_chooser',
					'display_name' => 'Choose a gallery',
					'description' => "Select the galley that will display on the home page.",
					'help_message' => "Choose a gallery test",
					'possible_values' => array(
						
					),
					'default_value' => '',
				),
				
			)
		),
	)
);
?>