<?php

$sqls = array();
$functions = array();


$functions[] = function() {
	$theme = ClassRegistry::init('Theme');
	$theme->add_theme('white_angular');
	return true;
};

