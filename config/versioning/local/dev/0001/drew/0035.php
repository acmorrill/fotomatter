<?php

$sqls = array();
$functions = array();

$functions[] = function() {
	$theme = ClassRegistry::init('Theme');
	$theme->add_theme('simple_lightgrey_textured');
	return true;
};