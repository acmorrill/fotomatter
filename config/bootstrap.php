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
define("SITE_THEME_MERGED_FINAL_IMAGES", SITE_BACKGROUND_PATH.DS.'theme_merged_final_images');
//define("SITE_BACKGROUND_WEB_PATH", DS.'site_background');
define("SITE_THEME_UPLOADED_IMAGES_WEB_PATH", DS.'theme_uploaded_images');
define("SITE_THEME_MERGED_FINAL_IMAGES_WEB_PATH", DS.'theme_merged_final_images');

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

// path to fonts
define("GLOBAL_TTF_FONT_PATH", ROOT.DS.APP_DIR.DS.'webroot'.DS.'fonts');

if (PHP_SAPI !== 'cli' && (!isset($_SERVER['argv']) || $_SERVER['argv'][3] != 'db')) {
    App::import('Model', 'SiteSetting');
    App::import('Model', 'Theme');
    $SiteSetting = new SiteSetting();
    $Theme = new Theme();
    $curr_theme = $SiteSetting->getVal('current_theme', 'default');
    $the_theme = $Theme->get_theme($curr_theme);
    if (!empty($the_theme)) {
            if ($the_theme['Theme']['theme_id'] == 0) {
                    define("CURRENT_THEME_PATH", PATH_TO_THEMES.DS.$curr_theme);
                    define("PARENT_THEME_PATH", PATH_TO_THEMES.DS.$curr_theme);
            } else {
                    define("CURRENT_THEME_PATH", PATH_TO_THEMES.DS.$the_theme['ParentTheme']['ref_name'].DS.'subthemes'.DS.$curr_theme);
                    define("PARENT_THEME_PATH", PATH_TO_THEMES.DS.$the_theme['ParentTheme']['ref_name']);
            }
    } else {
            define("CURRENT_THEME_PATH", PATH_TO_THEMES.DS.'default');
            define("PARENT_THEME_PATH", PATH_TO_THEMES.DS.'default');
    }
    define("DEFAULT_THEME_PATH", PATH_TO_THEMES.DS.'default');

    //die(VIEWS);
    //die(PATH_TO_THEMES.DS.$curr_theme.DS.'views'.DS);
    //die(PATH_TO_THEMES.DS.'default'.DS.'views');

    App::build(array(
    //	'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
    //	'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
            'views' => array(CURRENT_THEME_PATH.DS."views".DS, PARENT_THEME_PATH.DS."views".DS, DEFAULT_THEME_PATH.DS.'views'.DS),
    //	'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
    //	'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
    //	'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
    //	'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
            'helpers' => array(CURRENT_THEME_PATH.DS."helpers".DS, PARENT_THEME_PATH.DS."helpers".DS, DEFAULT_THEME_PATH.DS.'helpers'.DS),
    //	'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
    //	'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
    //	'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
    ));
}


/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */


