<?php

$sqls = array();
$functions = array();

$functions[] = function() {
	//create logo base directory
	if (is_dir(SITE_LOGO_PATH) === false) {
		exec("cd ".ROOT.";mkdir site_logo;", $output, $result);
		if ($result != 0 ) {
			return false;
		}
	}
	
	//create base logo file directory
	if (is_dir(SITE_LOGO_THEME_BASE_PATH) === false) {
		exec("cd ".SITE_LOGO_PATH.";mkdir base;", $output, $result);
		if ($result != 0 ) {
			return false;
		}
	}
	
	//create base logo file directory
	if (is_dir(SITE_LOGO_CACHES_PATH) === false) {
		exec("cd ".SITE_LOGO_PATH.";mkdir caches;", $output, $result);
		if ($result != 0 ) {
			return false;
		}
	}
	
	//create base logo file directory
	if (is_dir(SITE_LOGO_UPLOAD_PATH) === false) {
		exec("cd ".SITE_LOGO_PATH.";mkdir uploaded;", $output, $result);
		if ($result != 0 ) {
			return false;
		}
	}
	
	return true;
};
