<?php

$theme_config = array(
	'theme_controller_action_layouts'=>array(
		'SitePages'=>array(
			'landing_page'=>array(
				'layout'=>'landing',
				'view'=>false
			)
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
		)
	)
);