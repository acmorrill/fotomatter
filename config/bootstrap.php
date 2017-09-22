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
 */


require('welcome_db.php');


$site_domain = '';
function get_site_domain() {
	if (!empty($site_domain)) {
		return $site_domain;
	}
	
	$local_db = get_local_db_handle(false);
	
	// so need to grab the system domain
	$sql = "
		SELECT value FROM site_settings
		WHERE name = 'site_domain'
	";
	$result = mysql_query($sql, $local_db);
	$site_domain = mysql_result($result, 0);
	return $site_domain;
}

function get_primary_domain() {
	// grab the account_id
	$local_db = get_local_db_handle(false);
	
	$primary_domain_apc_key = 'primary_domain_'.$_SERVER['local']['database'];
	if (apc_exists($primary_domain_apc_key)) {
		return apc_fetch($primary_domain_apc_key);
	}

	
	$end_of_day_today = date('Y-m-d H:i:s', strtotime('23:59:59'));
	$sql = "
		SELECT url FROM account_domains AS AccountDomain
		WHERE
			(
				AccountDomain.type = 'purchased'
				AND
				AccountDomain.is_primary = '1'
				AND
				AccountDomain.expires > '$end_of_day_today'
			)
			OR
			(
				AccountDomain.type != 'purchased'
				AND
				AccountDomain.is_primary = '1'
			)
		LIMIT 1
	";
	$result = mysql_query($sql, $local_db);
	$primary_domain_arr = mysql_fetch_array($result);
	$primary_domain = '';
	if (!empty($primary_domain_arr['url'])) {
		$primary_domain = $primary_domain_arr['url'];
	}
	
	if (empty($primary_domain)) {
		$site_domain = get_site_domain();
		if (!empty($site_domain)) {
			$primary_domain = "$site_domain.fotomatter.net";
		}
	}
	
	apc_store($primary_domain_apc_key, $primary_domain, 28800); // 8 hours

	return $primary_domain;
}







$root_path = ROOT;
$GLOBALS['in_checkout'] = false;
$GLOBALS['in_no_redirect_url'] = false;
$GLOBALS['in_admin'] = false;
$GLOBALS['http_host'] = '';
$GLOBALS['current_primary_domain'] = '';
$GLOBALS['on_welcome_site'] = false;
$GLOBALS['app_env'] = array();
if (PHP_SAPI !== 'cli' && (!isset($_SERVER['argv']) || $_SERVER['argv'][3] != 'db')) {
	// get the current primary domain
	$GLOBALS['current_primary_domain'] = get_primary_domain();
	
	
	// grab the http host without www
	$GLOBALS['http_host'] = $_SERVER["HTTP_HOST"];
//	$prefix = 'www.';
//	if (substr($_SERVER["HTTP_HOST"], 0, strlen($prefix)) == $prefix) { 
//		$GLOBALS['http_host'] = substr($_SERVER["HTTP_HOST"], strlen($prefix)); 
//	}

	
	/////////////////////////////////////////////////////
	// get the app enviroment
	$app_realpath = realpath(APP);
	$GLOBALS['app_env']['current'] = $app_realpath == '/var/www/current' ? true : false;
	$GLOBALS['app_env']['upgrade'] = $app_realpath == '/var/www/upgrade' ? true : false;
	$GLOBALS['app_env']['staging'] = $app_realpath == '/var/www/staging' ? true : false;
	$GLOBALS['app_env']['dev'] = $app_realpath == '/var/www/dev' ? true : false;
	
	
	// figure out if we are in the admin
	$GLOBALS['in_admin'] = startsWith($_SERVER['REQUEST_URI'], '/admin');
	
	
	//////////////////////////////////////////////////////////////////
	// figure out if url is in checkout
	//-----------------------------------------------
	$not_checkout_urls = array(
		'/ecommerces/view_cart' => true,
		'/ecommerces/add_to_cart' => true,
		'/ecommerces/update_cart_qty' => true,
		'/ecommerces/remove_cart_item_by_index' => true,
		'/ecommerces/checkout_thankyou' => true,
		'/ecommerces/destroy_cart' => true,
	);
	$url_not_in_checkout = false;
	foreach ($not_checkout_urls as $url => $foo) {
		if (startsWith($_SERVER['REQUEST_URI'], $url)) {
			$url_not_in_checkout = true;
			break;
		}
	}
	$is_in_checkout = startsWith($_SERVER['REQUEST_URI'], '/ecommerces') || startsWith($_SERVER['REQUEST_URI'], '/site_pages/contact_us') || startsWith($_SERVER['REQUEST_URI'], '/site_pages/send_contact_us_email');
	if ( $is_in_checkout && $url_not_in_checkout === false) {
		$GLOBALS['in_checkout'] = true; // DREW TODO _ figure out why won't redirect sometimes
	}
	//-----------------------------------------------



	//////////////////////////////////////////////////////////////////
	// figure out if no redirects should happen
	//-----------------------------------------------
	$no_redirect_urls = array(
		'/ecommerces/check_frontend_cart' => true,
		'/site_pages/ping' => true,
		'/users/request_admin_password_change' => true,
		'/users/change_admin_password' => true,
	);
	foreach ($no_redirect_urls as $url => $foo) {
		if (startsWith($_SERVER['REQUEST_URI'], $url)) {
			$GLOBALS['in_no_redirect_url'] = true;
			break;
		}
	}
	//-----------------------------------------------
	
	
	
	///////////////////////////////////////////////////////////////
	// if on the welcome site we need to adjust the paths
	$WELCOME_SITE_URL = WELCOME_SITE_URL;
	if (empty($WELCOME_SITE_URL)) {
		$WELCOME_SITE_URL = 'welcome.fotomatter.net';
	}
	$GLOBALS['on_welcome_site'] = $GLOBALS['http_host'] === $WELCOME_SITE_URL;
	if ($GLOBALS['on_welcome_site'] === true) {
		// grab the account_id
		$local_db = get_local_db_handle(false);
		$sql = "
			SELECT value FROM site_settings
			WHERE name = 'account_id'
		";
		$result = mysql_query($sql, $local_db);
		$account_id = mysql_result($result, 0);
		if (!empty($account_id)) {
			$root_path = "/var/www/accounts/$account_id";
		}
	}
	
	
	///////////////////////////////////////////////////////////////////////////////
	// useful redirect vars
		$redirect_to_ssl = $GLOBALS['in_admin'] || $GLOBALS['in_checkout'];
		$can_redirect = !$GLOBALS['in_no_redirect_url'] && !$GLOBALS['on_welcome_site'];
		$not_on_ssl = empty($_SERVER['HTTPS']);
		$on_ssl = !$not_on_ssl;
		$is_not_dev_or_debug = Configure::read('debug') == 0 || empty($GLOBALS['app_env']['dev']);
	
	
	//////////////////////////////////////////////////////////////////////
	// redirect to ssl if need be
		$site_domain = get_site_domain();
		$system_url = "$site_domain.fotomatter.net";
		$on_system_site = $GLOBALS['http_host'] === $system_url;
		$need_to_redirect_to_system = $not_on_ssl || !$on_system_site;
		$redirect_to_system = $can_redirect && $need_to_redirect_to_system && $is_not_dev_or_debug && $redirect_to_ssl;
		if ($redirect_to_system) {
			header("Location: https://$site_domain.fotomatter.net{$_SERVER['REQUEST_URI']}");
			die();
		}
	
	//////////////////////////////////////////////////////////////////
	// check to see if we need to redirect to primary domain
	//----------------------------------------------
		//////////////////////////////////////////////////////////////////////////
		// redirect to primary domain if:
		// 1) not already on primary or on https
		// 2) not on welcome_site
		// 3) primary is not expired (if is purchased type domain)
		// 4) if don't need to redirect to ssl
		$not_on_primary_domain = $GLOBALS['http_host'] != $GLOBALS['current_primary_domain'];
		$need_to_redirect_to_primary = $not_on_primary_domain || $on_ssl;
		$redirect_to_primary_domain = $can_redirect && $is_not_dev_or_debug && !$redirect_to_ssl && $need_to_redirect_to_primary;
		if ($redirect_to_primary_domain) {
			header("Location: http://{$GLOBALS['current_primary_domain']}{$_SERVER['REQUEST_URI']}");
			die();
		}
}


// site_default_images
define("SITE_DEFAULT_CONTAINER_NAME", 'site_default_images'); 
define("SITE_DEFAULT_CONTAINER_URL", 'http://5b3fca59f2744e30ab19-83f12fdaaac179c142328b923267ceea.r18.cf2.rackcdn.com'); 
define("SITE_DEFAULT_CONTAINER_SECURE_URL", 'https://d8da4ad7f3fcaf47b7ae-83f12fdaaac179c142328b923267ceea.ssl.cf2.rackcdn.com'); 



define("FOTOMATTER_SUPPORT_EMAIL", 'support@fotomatter.net'); 
define("FOTOMATTER_SUPPORT_EMAIL_REPLYTO", 'Fotomatter <support@fotomatter.net>'); 


// postmark credentials
Configure::write('Postmark.uri', 'https://api.postmarkapp.com/email');
Configure::write('Postmark.key', '34ede038-cd7b-4c34-b92b-7234d09ab03c');


define("HELP_TOUR_ENGLISH_TEXT", 'Get Help With This Page'); 

define("SITE_SETTINGS_APC_CACHE_TTL", 28800); // 8  hours
define("SITE_SETTINGS_APC_DEFAULT_KEY", 'USE_THE_DEFAULT'); // the string to store for site settings apc to indicate to use the default setting
define("FRONTEND_VIEW_CACHING_STRTOTIME_TTL", '1 week'); // how long to keep view caching
define("VIEW_CACHE_PATH", $root_path . '/tmp/cache/views');

date_default_timezone_set('America/Denver');

define("FORGOT_PASSWORD_SALT", 'a0YngDg079JmYJ5ahCxWV6PFovsyGn');
define("CARDNUMBER_MASK", 'XXXXXXXXXXXX');

define("OVERLORD_API_KEY", 'baYMbSR0EM0REmSheFHc0Qo2RUmEGoToNFnPWFcyAEUYRlaOgSynnI1F9DyI');

// global container urls
define("GLOBAL_FOTOMATTER_CONTAINER_URL", 'http://d032ca0eed6846c31fe8-4af0fb97e675ed71f0af4b096cd907ac.r56.cf2.rackcdn.com/');
define("GLOBAL_FOTOMATTER_CONTAINER_SECURE_URL", 'https://d4f9baf5b96f3eeed9c9-4af0fb97e675ed71f0af4b096cd907ac.ssl.cf2.rackcdn.com/');

// limit functions
define("LIMIT_MAX_FREE_PHOTOS", 50);

// domain variables
define('DOMAIN_MARKUP_DOLLAR', '2');
define('DOMAIN_MAX_DAYS_PAST_EXPIRE', -20);

// webroot abs path
define("WEBROOT_ABS", $root_path.DS.APP_DIR.DS.'webroot');

// less_css root path
define("LESSCSS_ROOT", $root_path.DS.APP_DIR.DS.'lesscss');

// php_closure
define("PHP_CLOSURE_ROOT", $root_path.DS.APP_DIR.DS.'php_closure');

// themes paths 
define("PATH_TO_THEMES", $root_path.DS.APP_DIR.DS.'themes');

// site logo paths
define("SITE_LOGO_PATH", $root_path.DS.'site_logo');
define("SITE_LOGO_THEME_BASE_PATH", SITE_LOGO_PATH.DS.'base');
define("SITE_LOGO_THEME_BASE_WEB_PATH", DS.'base');
define("SITE_LOGO_UPLOAD_PATH", SITE_LOGO_PATH.DS.'uploaded');
define("UPLOADED_LOGO_PATH", SITE_LOGO_UPLOAD_PATH.DS.'base_uploaded_logo.png');
define("SITE_LOGO_UPLOAD_WEB_PATH", DS.'uploaded');
define("SITE_LOGO_CACHES_PATH", SITE_LOGO_PATH.DS.'caches');
define("SITE_LOGO_CACHES_WEB_PATH", DS.'caches');

// site background paths
define("SITE_BACKGROUND_PATH", $root_path.DS.'site_background');
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
define("LOCAL_SCHEMA_PATH", $root_path.DS.APP_DIR.DS.'config'.DS.'versioning'.DS.'local'.DS.'schema');   
define("GLOBAL_SCHEMA_PATH", $root_path.DS.APP_DIR.DS.'config'.DS.'versioning'.DS.'global'.DS.'schema');   

// image and image caching paths
define("TEMP_IMAGE_PATH", $root_path.DS.'image_tmp');
define("TEMP_IMAGE_VAULT", $root_path.DS.'image_vault');
define("TEMP_IMAGE_UNIT", $root_path.DS.'unit_test_cache');
define("LOCAL_MASTER_CACHE", $root_path.DS.'local_master_cache');
define("LOCAL_SMALLER_MASTER_CACHE", $root_path.DS.'local_smaller_master_cache');

// photo and caching constants
define("MASTER_CACHE_PREFIX", 'mastercache_');
define("SMALLER_MASTER_CACHE_PREFIX", 'mastercache_smaller_');
define("LARGE_MASTER_CACHE_SIZE", 1500);
define("SMALL_MASTER_CACHE_SIZE", 250);
define('MAX_UPLOAD_SIZE_MEGS', 50);
define('MAX_PAID_UPLOAD_SIZE_MEGS', 100);
define('MAX_MEGEBYTES_SPACE', 50000); // 50 gigs limit
define('MAX_UPPER_LIMIT_MEGEBYTES_SPACE', 250000); // 250 gigabyte limit
define('FREE_MAX_RES', 4000);
define('MAX_PAID_MEGAPIXELS', 150);
define("USE_CACHE_SPEED", true);

// facebook settings
define("FACEBOOK_APP_ID", '360914430736815');
define("FACEBOOK_APP_SECRET", 'de3419a89b4423f82f690e5909876928');
define("FACEBOOK_GRAPH_VERSION", 'v2.5');

//path to overlord. (so it can change for development)
Configure::write('OVERLORD_URL', 'builds.fotomatter.net');
Configure::write('SHOW_FAKE_BILLING_DATA', false);

// path to fonts
define("GLOBAL_TTF_FONT_PATH", $root_path.DS.APP_DIR.DS.'webroot'.DS.'fonts');

$dbs = array();
function get_local_db_handle($global_db = true) {
	if ($global_db) {
		if (!empty($dbs['global_db'])) {
			return $dbs['global_db'];
		}
		
		$db_data = $_SERVER['global'];
		$dbs['global_db'] = mysql_connect($db_data['host'] . ':3306', $db_data['login'], $db_data['password'], true);
		if (mysql_error($dbs['global_db'])) {
			echo ("Cannot connect to local db. Check config, and try again.");
			return;
		}

		mysql_select_db($db_data['database'], $dbs['global_db']);
		if (mysql_error($dbs['global_db'])) {
			echo ("Cannot select local db. Check config, and try again.");
			return;
		}
		
		return $dbs['global_db'];
	} else {
		if (!empty($dbs['local_db'])) {
			return $dbs['local_db'];
		}
		
		$db_data = $_SERVER['local'];
		$dbs['local_db'] = mysql_connect($db_data['host'] . ':3306', $db_data['login'], $db_data['password'], true);
		if (mysql_error($dbs['local_db'])) {
			echo ("Cannot connect to local db. Check config, and try again.");
			return;
		}

		mysql_select_db($db_data['database'], $dbs['local_db']);
		if (mysql_error($dbs['local_db'])) {
			echo ("Cannot select local db. Check config, and try again.");
			return;
		}
		
		return $dbs['local_db'];
	}
}

function close_db_handle($global_db = true) {
	if ($global_db) {
		if (!empty($dbs['global_db'])) {
			mysql_close($dbs['global_db']);
		}
	} else {
		if (!empty($dbs['local_db'])) {
			mysql_close($dbs['local_db']);
		}
	}
}


/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */
function record_major_error($location, $line_number, $description, $log_data) {
	global $root_path;
	
	$local_db = get_local_db_handle();
	
	$location = mysql_real_escape_string($location);
	$line_number = mysql_real_escape_string($line_number);
	$description = mysql_real_escape_string($description);
	$log_data_str = mysql_real_escape_string(print_r($log_data, true));
	$account_id = basename(realpath($root_path));
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
	$errstr = mysql_real_escape_string($errstr);
	$errline = mysql_real_escape_string($errline);
	$description = "An error recorded by myErrorHandler in bootstrap.php: $errstr on line $errline in file $errfile";
	
	
	// record the error
	record_major_error($errfile, $errline, $description, $log_data);

	
	/* Don't execute PHP internal error handler */
	return false;
}
$old_error_handler = set_error_handler("myErrorHandler");


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

	
	$GLOBALS['CURRENT_THEME_PATH'] = dirname(realpath($root_path.DS."current_theme_webroot"));
	$GLOBALS['PARENT_THEME_PATH'] = dirname(realpath($root_path.DS."parent_theme_webroot"));
	define("DEFAULT_THEME_PATH", PATH_TO_THEMES.DS.'default');
	define("DEFAULT_THEME_WEBROOT_ABS", PATH_TO_THEMES.DS.'default'.DS.'webroot');
	
	

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


require('core_ignored.php');
