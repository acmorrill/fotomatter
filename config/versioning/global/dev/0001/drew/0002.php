<?php

$sqls = array();
$functions = array();

$sqls[] = "INSERT INTO  `server_settings` (`id` ,`name` ,`value` ,`created` ,`modified`)
VALUES (NULL ,  'test2',  'blah2', NULL , NULL);";

$functions[] = function() {
	$ServerSetting = ClassRegistry::init('ServerSetting');
	return $ServerSetting->setVal('test2', 'blah3');
};
