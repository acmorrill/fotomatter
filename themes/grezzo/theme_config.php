<?php

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
				'background_color' => array(
					'type' => 'dropdown',
					'display_name' => 'Background Color',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
					'help_message' => "Background color test",
					'possible_values' => array(
						'black' => array('display' => 'Black'),
						'white' => array('display' => 'White'),
						'pink' => array('display' => 'Pink'),
						'red' => array('display' => 'Red'),
						'yellow' => array('display' => 'Yellow'),
						'blue' => array('display' => 'Blue'),
					),
					'default_value' => 'black'
				),
				
				'header_is_full_width' => array(
					'type' => 'on_off',
					'display_name' => 'Header is Full Width',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
					'help_message' => 'On Off Test',
					'possible_values' => array(
						'on' => array( 'display' => 'On' ),
						'off' => array( 'display' => 'Off' ),
					),
					'default_value' => 'on',
				),
				
				'footer_text' => array(
					'type' => 'textarea',
					'display_name' => 'Footer text',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
					'help_message' => 'Footer Text test',
					'possible_values' => '.*\\\S+.*', // can be regex
					'default_value' => "Andrew's face is all over the place. It's his face. BA-BOOM!",	
				),
				
				'footer_menu' => array(
					'type' => 'on_off',
					'display_name' => 'Footer Menu',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unch",
					'help_message' => 'Footer menu on off test',
					'possible_values' => array(
						'on' => array('display' => 'On'),
						'off' => array('display' => 'Off'),
					),
					'default_value' => 'on',
				),
				
				'gallery_selection' => array(
					'type' => 'gallery_chooser',
					'display_name' => 'Choose a gallery',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has surviv",
					'help_message' => "Choose a gallery test",
					'possible_values' => array(
						
					),
					'default_value' => '',
				),
				
				'font_selection' => array(
					'type' => 'dropdown',
					'display_name' => 'Choose a font type',
					'description' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
					'help_message' => 'Choose a font type',
					
				),
			)
		),
	)
);
?>
<?php /*<div class="blah <?php if ($settings['header_is_full_width'] == 'on'): ?>full_width<?php endif; ?>">
	
</div> < ?>