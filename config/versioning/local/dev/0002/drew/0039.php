<?php

$sqls = array();

$functions[] = function() {
	$theme = ClassRegistry::init('Theme');
	$theme->add_theme_display_name('test_bg_theme', 'Testing BG Theme');
	return true;
};



