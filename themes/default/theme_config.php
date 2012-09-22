<?php


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
				'web_path' => ''
			),
			'default_space' => array(
				'width' => 300,
				'height' => 150
			)
		)
	)
);