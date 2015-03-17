<?php

$sqls = array();

$functions = array();

$functions[] = function() {
	$theme = ClassRegistry::init('Theme');
	$theme->add_theme_display_name('white_slider_subone', 'Grezzo2', 'white_slider');
	return true;
};
