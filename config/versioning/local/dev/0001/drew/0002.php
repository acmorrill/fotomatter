<?php

$sqls = array();
$functions = array();

$sqls[] = "INSERT INTO  `site_settings` (`id` ,`name` ,`value` ,`created` ,`modified`)
VALUES (NULL ,  'our_ethic',  'perfect code!', NULL , NULL);";

$functions[] = function() {
	$SiteSetting = ClassRegistry::init('SiteSetting');
	return $SiteSetting->setVal('our_product', 'worth it!');
};
