<?php

$sqls = array();
$functions = array();


$functions[] = function() {
	$theme = ClassRegistry::init('Theme');
	$theme->add_theme('white_angular');
	return true;
};


$sqls[] = "UPDATE  `themes` SET  `display_name` =  'White Angular' WHERE  `themes`.`ref_name` ='white_angular';";
