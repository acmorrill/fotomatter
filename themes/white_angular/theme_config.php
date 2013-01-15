<?php

// config for theme: andrewmorrill

$theme_config = array(
	'theme_controller_action_layouts' => array(
		'SitePages' => array(
			'landing_page' => 'landing',
			'custom_page' => 'landing'
		),
		'PhotoGalleries' => array(
			'choose_gallery' => 'landing',
			'view_gallery' => 'landing'
		),
		'Photos' => array(
			'view_photo' => 'landing'
		)
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
