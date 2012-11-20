<?php
$_SERVER['local']['host'] = '%host%';
$_SERVER['local']['login'] = '%login%';
$_SERVER['local']['password'] = '%password%';
$_SERVER['local']['database'] = '%database%';

$_SERVER['global']['host'] = '%global_host%';
$_SERVER['global']['login'] = '%global_login%';
$_SERVER['global']['password'] = '%global_password%';
$_SERVER['global']['database'] = '%global_database%';

if (PHP_SAPI != 'cli') {
    define('TMP', ROOT . '/tmp/');
}