<?php

$sqls = array();
$functions = array();

$functions[] = function() {
	// create theme background uploaded images folder
	if (is_dir(SITE_BACKGROUND_PATH) === false) {
		if (!mkdir(SITE_BACKGROUND_PATH, 0775, true) || !chmod(SITE_BACKGROUND_PATH, 0775)) {
			return false;
		}
	}
	
	
	// create theme background uploaded images folder
	if (is_dir(SITE_THEME_UPLOADED_IMAGES) === false) {
		if (!mkdir(SITE_THEME_UPLOADED_IMAGES, 0775, true) || !chmod(SITE_THEME_UPLOADED_IMAGES, 0775)) {
			return false;
		}
	}
	
	
	// create theme background uploaded images folder
	if (is_dir(SITE_THEME_MERGED_FINAL_IMAGES) === false) {
		if (!mkdir(SITE_THEME_MERGED_FINAL_IMAGES, 0775, true) || !chmod(SITE_THEME_MERGED_FINAL_IMAGES, 0775)) {
			return false;
		}
	}
	
	
	return true;
};
