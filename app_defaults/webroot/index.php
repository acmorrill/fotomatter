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
	} else { ?>
		<?php $apc_site_disabled_key = "disabled_site_{$_SERVER['local']['database']}"; ?>
		<?php if (apc_exists($apc_site_disabled_key)): ?>
			<?php if (!startsWith(trim($_SERVER['REQUEST_URI'], '/'), "admin")): ?>
				<h1>Website down for maintenance</h1>
				<?php die(); ?>
			<?php endif; ?>
		<?php endif; ?>
	<?php
		$Dispatcher = new Dispatcher();
		$Dispatcher->dispatch();
	}
