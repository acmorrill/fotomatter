<?php
/**
 * Index
 * 
 * The Front Controller for handling every request
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.webroot
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Use the DS to separate the directories in other defines
 */
	// this is no longer needed as the logo code was refactored
//	if (apc_exists('clear_cake_core_apc_cache_on_next_request')) {
//		apc_delete('app_cake_core_object_map_expires');
//		apc_delete('app_cake_core_object_map');
//		apc_delete('app_cake_core_file_map_expires');
//		apc_delete('app_cake_core_file_map');
//		apc_delete('app_cake_core_dir_map_expires');
//		apc_delete('app_cake_core_dir_map');
//		
//		apc_delete('clear_cake_core_apc_cache_on_next_request');
//	}


	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}
/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 *
 */
	if (!defined('ROOT')) {
		define('ROOT', dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))));
	}
	require_once(ROOT."/db_configs.php");
/**
 * The actual directory name for the "app".
 *
 */
	if (!defined('APP_DIR')) {
		define('APP_DIR', basename(dirname(dirname($_SERVER['SCRIPT_FILENAME']))));
	}
/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 */
	if (!defined('CAKE_CORE_INCLUDE_PATH')) {
		define('CAKE_CORE_INCLUDE_PATH', ROOT);
	}

/**
 * Editing below this line should NOT be necessary.
 * Change at your own risk.
 *
 */
	if (!defined('WEBROOT_DIR')) {
		define('WEBROOT_DIR', basename(dirname(__FILE__)));
	}
	if (!defined('WWW_ROOT')) {
		define('WWW_ROOT', dirname(__FILE__) . DS);
	}
	if (!defined('CORE_PATH')) {
		if (function_exists('ini_set') && ini_set('include_path', CAKE_CORE_INCLUDE_PATH . PATH_SEPARATOR . ROOT . DS . APP_DIR . DS . PATH_SEPARATOR . ini_get('include_path'))) {
			define('APP_PATH', null);
			define('CORE_PATH', null);
		} else {
			define('APP_PATH', ROOT . DS . APP_DIR . DS);
			define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
		}
	}
	if (php_sapi_name() == 'cli-server') {
		$_SERVER['PHP_SELF'] = '/'.basename(__FILE__);
	}
	

	if (!include(CORE_PATH . 'cake' . DS . 'bootstrap.php')) {
		trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
	}
	if (isset($_GET['url']) && $_GET['url'] === 'favicon.ico') {
		return;
	} else { 
		$apc_site_disabled_key = "disabled_site_{$_SERVER['local']['database']}";
		if (isset($_POST['disable_website_key']) && $_POST['disable_website_key'] == 'zgxzx4fIFxaMwJeLnQjUjf4hjaDkftYpbS6pKhYieIcnf1tSvEZskSqJ3oSo') {
			$ttl = 600;
			if (isset($_POST['disable_website_ttl'])) {
				$ttl = $_POST['disable_website_ttl'];
			}
			apc_store($apc_site_disabled_key, true, 600);
			http_response_code(200);
			die();
		}
		if (isset($_POST['enable_website_key']) && $_POST['enable_website_key'] == 'zgxzx4fIFxaMwJeLnQjUjf4hjaDkftYpbS6pKhYieIcnf1tSvEZskSqJ3oSo') {
			apc_delete($apc_site_disabled_key);
			http_response_code(200);
			die();
		}
//		apc_delete($apc_site_disabled_key);
//		apc_store($apc_site_disabled_key, true, 600);
	?><?php if (apc_exists($apc_site_disabled_key) && !startsWith(trim($_SERVER['REQUEST_URI'], '/'), "admin")): ?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Updating Website</title>
			<style type="text/css">
				#content_container, body, html{
					position: relative;
					height: 100%;
					width: 100%;
					margin: 0px;
				}
				#main_content {
					position: absolute;
					top: 50%;
					left: 50%;
					margin-top: -135px;
					margin-left: -300px;
					width: 600px;
					height: 300px;
				}
				#main_content img {
					float: right;
					margin-left: 20px;
				}
				#main_content h1, h2 {
					color: white;
				}
				#admin_background {
					position: fixed;
					left: -20%;
					height: 100%;
					width: 140%;
					background-image: url(/img/admin/general/admin_background.jpg);
					background-color: #0d0d0e;
					background-size: 100% auto;
					background-repeat: no-repeat;
					background-position: -50px -50px;
					z-index: -1;
				}
				#main_container {
					position: absolute;
					background-color: #282828;
					width: 100%;
					height: 380px;
					top: 50%;
					margin-top: -190px;
					border: 1px solid #000;
					-moz-box-shadow: 0px 0px 1px 1px #232527;
					-webkit-box-shadow: 0px 0px 1px 1px #232527;
					box-shadow: 0px 0px 1px 1px #232527;
				}
			</style>
		</head>
		<body>
			<div id="content_container">
				<div id="main_container">
					<div id="main_content">
						<img src="/img/repeatingsmiley.gif" width="256" height="256" />
						<h1>Currently performing awesomeness on <?php echo $_SERVER['SERVER_NAME']; ?>!</h1>
						<h2>(Actually we are doing some updates and things will be back shortly)</h2>
						<div style="clear: both;"></div>
					</div>
				</div>
				<div id="admin_background"></div>
				<div
			</div>
		</body>
		</html>
		<?php die(); ?>
	<?php endif; ?><?php
		$Dispatcher = new Dispatcher();
		$Dispatcher->dispatch();
	}
