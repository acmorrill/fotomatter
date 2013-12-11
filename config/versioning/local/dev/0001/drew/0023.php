<?php

$sqls = array();
$functions = array();

$functions[] = function() {
	$SiteSetting = ClassRegistry::init('SiteSetting');
	return $SiteSetting->setVal('current_theme', 'default');
};







