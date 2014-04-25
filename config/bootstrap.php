<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
a * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 i*
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 i*/
define("HELP_TOUR_ENGLISH_TEXT", 'Get Help With This Page'); 

define("SITE_SETTINGS_APC_CACHE_TTL", 28800); // 8  hours
define("SITE_SETTINGS_APC_DEFAULT_KEY", 'USE_THE_DEFAULT'); // the string to store for site settings apc to indicate to use the default setting
define("FRONTEND_VIEW_CACHING_STRTOTIME_TTL", '1 week'); // how long to keep view caching

date_default_timezone_set('America/Denver');

define("FORGOT_PASSWORD_SALT", 'a0YngDg079JmYJ5ahCxWV6PFovsyGn');
define("CARDNUMBER_MASK", 'XXXXXXXXXXXX');

define("OVERLORD_API_KEY", 'baYMbSR0EM0REmSheFHc0Qo2RUmEGoToNFnPWFcyAEUYRlaOgSynnI1F9DyI');

// global container urls
define("GLOBAL_FOTOMATTER_CONTAINER_URL", 'http://d032ca0eed6846c31fe8-4af0fb97e675ed71f0af4b096cd907ac.r56.cf2.rackcdn.com/');
define("GLOBAL_FOTOMATTER_CONTAINER_SECURE_URL", 'https://d4f9baf5b96f3eeed9c9-4af0fb97e675ed71f0af4b096cd907ac.ssl.cf2.rackcdn.com/');

// limit functions
define("LIMIT_MAX_FREE_PHOTOS", 4); // DREW TODO - change this back to 100


define('DOMAIN_MARKUP_DOLLAR', '0');

// webroot abs path
define("WEBROOT_ABS", ROOT.DS.APP_DIR.DS.'webroot');

// less_css root path
define("LESSCSS_ROOT", ROOT.DS.APP_DIR.DS.'lesscss');

// themes paths 
define("PATH_TO_THEMES", ROOT.DS.APP_DIR.DS.'themes');

// site logo paths
define("SITE_LOGO_PATH", ROOT.DS.'site_logo');
define("SITE_LOGO_THEME_BASE_PATH", SITE_LOGO_PATH.DS.'base');
define("SITE_LOGO_THEME_BASE_WEB_PATH", DS.'base');
define("SITE_LOGO_UPLOAD_PATH", SITE_LOGO_PATH.DS.'uploaded');
define("UPLOADED_LOGO_PATH", SITE_LOGO_UPLOAD_PATH.DS.'base_uploaded_logo.png');
define("SITE_LOGO_UPLOAD_WEB_PATH", DS.'uploaded');
define("SITE_LOGO_CACHES_PATH", SITE_LOGO_PATH.DS.'caches');
define("SITE_LOGO_CACHES_WEB_PATH", DS.'caches');

// site background paths
define("SITE_BACKGROUND_PATH", ROOT.DS.'site_background');
define("SITE_THEME_UPLOADED_IMAGES", SITE_BACKGROUND_PATH.DS.'theme_uploaded_images');
define("UPLOADED_BACKGROUND_PATH", SITE_THEME_UPLOADED_IMAGES.DS.'base_uploaded_background.jpg');
define("SITE_THEME_MERGED_FINAL_IMAGES", SITE_BACKGROUND_PATH.DS.'theme_merged_final_images');
define("SITE_THEME_BG_EDITED_IMAGES", SITE_BACKGROUND_PATH.DS.'theme_bg_edited_images');
//define("SITE_BACKGROUND_WEB_PATH", DS.'site_background');
define("SITE_THEME_UPLOADED_IMAGES_WEB_PATH", DS.'theme_uploaded_images');
define("SITE_THEME_MERGED_FINAL_IMAGES_WEB_PATH", DS.'theme_merged_final_images');
define("SITE_THEME_BG_EDITED_IMAGES_WEB_PATH", DS.'theme_bg_edited_images');
define("UPLOADED_BACKGROUND_WEB_PATH", SITE_THEME_UPLOADED_IMAGES_WEB_PATH.DS.'base_uploaded_background.jpg');

// paths for schema directories
define("LOCAL_SCHEMA_PATH", ROOT.DS.APP_DIR.DS.'config'.DS.'versioning'.DS.'local'.DS.'schema');   
define("GLOBAL_SCHEMA_PATH", ROOT.DS.APP_DIR.DS.'config'.DS.'versioning'.DS.'global'.DS.'schema');   

// image and image caching paths
define("TEMP_IMAGE_PATH", ROOT.DS.'image_tmp');
define("TEMP_IMAGE_VAULT", ROOT.DS.'image_vault');
define("TEMP_IMAGE_UNIT", ROOT.DS.'unit_test_cache');
define("LOCAL_MASTER_CACHE", ROOT.DS.'local_master_cache');
define("LOCAL_SMALLER_MASTER_CACHE", ROOT.DS.'local_smaller_master_cache');

// photo and caching constants
define("MASTER_CACHE_PREFIX", 'mastercache_');
define("SMALLER_MASTER_CACHE_PREFIX", 'mastercache_smaller_');
define("LARGE_MASTER_CACHE_SIZE", 1500);
define("SMALL_MASTER_CACHE_SIZE", 250);
define('MAX_UPLOAD_SIZE_MEGS', 5);
define('FREE_MAX_RES', 2000);
define("USE_CACHE_SPEED", true);

//path to overlord. (so it can change for development)
Configure::write('OVERLORD_URL', 'builds.fotomatter.net');
Configure::write('SHOW_FAKE_BILLING_DATA', false);

// path to fonts
define("GLOBAL_TTF_FONT_PATH", ROOT.DS.APP_DIR.DS.'webroot'.DS.'fonts');


if (PHP_SAPI !== 'cli' && (!isset($_SERVER['argv']) || $_SERVER['argv'][3] != 'db')) {
//	$local_db = get_local_db_handle();
//	$theme_sql = "SELECT * FROM theme_id as Theme
//		WHERE Theme.display_name = (SELECT )
//	";
//	mysql_query($theme_sql, $local_db);
	
//	App::import('Model', 'SiteSetting');
//	App::import('Model', 'Theme');
//	$SiteSetting = new SiteSetting();
//	$Theme = new Theme();
//	$curr_theme = $SiteSetting->getVal('current_theme', 'default');
//	$the_theme = $Theme->get_theme($curr_theme);
	
	//die(ROOT.DS."current_theme_webroot");
	$GLOBALS['CURRENT_THEME_PATH'] = dirname(realpath(ROOT.DS."current_theme_webroot"));
	$GLOBALS['PARENT_THEME_PATH'] = dirname(realpath(ROOT.DS."parent_theme_webroot"));
	// DREW TODO - delete the below
//	if (!empty($the_theme)) {
//		if ($the_theme['Theme']['theme_id'] == 0) {
//			$GLOBALS['CURRENT_THEME_PATH'] = PATH_TO_THEMES.DS.$curr_theme;
//			$GLOBALS['PARENT_THEME_PATH'] = PATH_TO_THEMES.DS.$curr_theme;
//		} else {
//			$GLOBALS['CURRENT_THEME_PATH'] = PATH_TO_THEMES.DS.$the_theme['ParentTheme']['ref_name'].DS.'subthemes'.DS.$curr_theme;
//			$GLOBALS['PARENT_THEME_PATH'] = PATH_TO_THEMES.DS.$the_theme['ParentTheme']['ref_name'];
//		}
//	} else {
//		$GLOBALS['CURRENT_THEME_PATH'] = PATH_TO_THEMES.DS.'default';
//		$GLOBALS['PARENT_THEME_PATH'] = PATH_TO_THEMES.DS.'default';
//	}
	define("DEFAULT_THEME_PATH", PATH_TO_THEMES.DS.'default');

	//die(VIEWS);
	//die(PATH_TO_THEMES.DS.$curr_theme.DS.'views'.DS);
	//die(PATH_TO_THEMES.DS.'default'.DS.'views');

	App::build(array(
	//	'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
	//	'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
			'views' => array($GLOBALS['CURRENT_THEME_PATH'].DS."views".DS, $GLOBALS['PARENT_THEME_PATH'].DS."views".DS, DEFAULT_THEME_PATH.DS.'views'.DS),
	//	'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
	//	'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
	//	'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
	//	'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
			'helpers' => array($GLOBALS['CURRENT_THEME_PATH'].DS."helpers".DS, $GLOBALS['PARENT_THEME_PATH'].DS."helpers".DS, DEFAULT_THEME_PATH.DS.'helpers'.DS),
	//	'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
	//	'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
	//	'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
	));
}



function get_local_db_handle() {
	require_once(CONFIGS.'database.php');
	$dbconfig = new DATABASE_CONFIG();
       
	$local_db = mysql_connect($dbconfig->server_global['host'], $dbconfig->server_global['login'], $dbconfig->server_global['password'], true);
	if (mysql_error($local_db)) {
		echo ("Cannot connect to local db. Check config, and try again.");
		return;
	}

	mysql_select_db($dbconfig->server_global['database'], $local_db);
	if (mysql_error($local_db)) {
		echo ("Cannot select local db. Check config, and try again.");
		return;
	}
	return $local_db;
}


/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */
function record_major_error($location, $line_number, $description, $log_data) {
	$local_db = get_local_db_handle();
	
	$location = mysql_real_escape_string($location);
	$line_number = mysql_real_escape_string($line_number);
	$description = mysql_real_escape_string($description);
	$log_data_str = mysql_real_escape_string(print_r($log_data, true));
	$account_id = basename(realpath(ROOT));
	$sql = "INSERT INTO  `major_errors` (`id` , `account_id`, `location` ,`line_num` ,`description` ,`extra_data` ,`severity` ,`created`)
		VALUES (NULL ,  '$account_id', '$location',  '$line_number',  '$description',  '$log_data_str',  'high', NOW()  );
	";
	mysql_query($sql, $local_db);
}

/**
 * Record major error for fatal errors
 * 
 * @return type 
 */
function myFatalErrorHandler() {
	$error = error_get_last();
	
	if (isset($error['type']) && $error['type'] === 2048 || $error['type'] === 8192 || error_reporting() === 0) {
		return;
	}
	
	$location = 'Fatal Error';
	if (isset($error['file'])) {
		$location = $error['file'];
	}
	$line_number = '1';
	if (isset($error['line'])) {
		$line_number = $error['line'];
	}
	$description = 'Fatal Error: A fatal error occurred and was handled by a shutdown function in bootstrap.php';
	if (isset($error['message'])) {
		$description = "Fatal Error: ".$error['message'];
	}
	
	$log_data = compact('error');
	
	
	// record the error
	record_major_error($location, $line_number, $description, $log_data);
}
register_shutdown_function('myFatalErrorHandler');


/**
* Record major error for runtime errors
*/
function myErrorHandler($errno, $errstr, $errfile, $errline) {
	if ($errno === 2048 || $errno === 8192 || error_reporting() === 0) {
		return;
	}
	
	$log_data = compact('errno', 'errstr', 'errfile', 'errline');
	$errstr = mysql_escape_string($errstr);
	$errline = mysql_escape_string($errline);
	$description = "An error recorded by myErrorHandler in bootstrap.php: $errstr on line $errline";
	
	
	// record the error
	record_major_error($errfile, $errline, $description, $log_data);

	
	/* Don't execute PHP internal error handler */
	return false;
}
$old_error_handler = set_error_handler("myErrorHandler");
require('core_ignored.php');
