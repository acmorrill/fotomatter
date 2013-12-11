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
	)
);
