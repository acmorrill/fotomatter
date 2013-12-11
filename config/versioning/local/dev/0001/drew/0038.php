<?php

$sqls = array();
$functions = array();

$functions[] = function() {
	$theme = ClassRegistry::init('Theme');
	$theme->add_theme('white_slider');
	return true;
};
