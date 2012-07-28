<?php

$sqls = array();
$functions = array();

$functions[] = function() {
	if (!file_exists(ROOT.DS.'current_theme_webroot')) {
		$command = "ln -s ".ROOT.DS."app/themes/default/webroot/ current_theme_webroot";
		exec($command);
	}
	
	if (!file_exists(ROOT.DS.'default_theme_webroot')) {
		$command = "ln -s ".ROOT.DS."app/themes/default/webroot/ default_theme_webroot";
		exec($command);
	}
	
	return true;
};







