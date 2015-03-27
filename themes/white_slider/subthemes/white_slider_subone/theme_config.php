<?php

$theme_config = array(
	'theme_name' => 'white_slider_subone',
	'admin_config' => array(
		'logo_config' => array(
			'available_space' => array(
				'width' => 398,
				'height' => 139
			),
			'available_space_screenshot' => array(
				'absolute_path' => 	PATH_TO_THEMES.DS.'white_slider/subthemes/white_slider_subone/webroot/img/darkslide_logo_screenshot.jpg',
				'web_path' => '/img/darkslide_logo_screenshot.jpg',
				'padding' => array(
					'left' => 51,
					'top' => 0,
					'right' => 558,
					'bottom' => 386
				)
			),
			'default_space' => array( // the bounding size of the logo if no size has been specified (should be smaller than available space
				'width' => 300,
				'height' => 120
			)
		),
	),
);
