<?php
$_SERVER['local']['host'] = '%host%';
$_SERVER['local']['login'] = '%login%';
$_SERVER['local']['password'] = '%password%';
$_SERVER['local']['database'] = '%database%';

$_SERVER['global']['host'] = '%global_host%';
$_SERVER['global']['login'] = '%global_login%';
$_SERVER['global']['password'] = '%global_password%';
$_SERVER['global']['database'] = '%global_database%';

if (PHP_SAPI != 'cli' && defined('TMP') === false) {
    define('TMP', ROOT . '/tmp/');
	define('WELCOME_SITE_URL', '%welcome_site_url%');
}

$_SERVER['PHP_SELF'] = '/app/webroot/index.php';
