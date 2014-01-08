<?php
$functions = array();
$sqls = array();

$functions[] = function() {
	$theme = ClassRegistry::init('Theme');
	$theme->add_theme_display_name('grezzo', 'Grezzo', 'white_slider');
	return true;
};

