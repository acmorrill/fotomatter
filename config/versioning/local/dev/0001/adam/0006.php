<?php

$sqls = array();
$functions = array();

$sqls[] = "INSERT INTO  `site_settings` (`id` ,`name` ,`value` ,`created` ,`modified`)
VALUES (NULL ,  'our_goal',  '100 users', NULL , NULL);";

$functions[] = function() {
	$SiteSetting = ClassRegistry::init('SiteSetting');
	return $SiteSetting->setVal('our_skills', 'the coolest');
};
