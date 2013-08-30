<?php
/**
 * This is core configuration file.
 *
 * Use it to configure core behaviour ofCake.
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
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * In this file you set up your database connection details.
 *
 * @package       cake
 * @subpackage    cake.config
 */
/**
 * Database configuration class.
 * You can specify multiple configurations for production, development and testing.
 *
 * driver => The name of a supported driver; valid options are as follows:
 *		mysql 		- MySQL 4 & 5,
 *		mysqli 		- MySQL 4 & 5 Improved Interface (PHP5 only),
 *		sqlite		- SQLite (PHP5 only),
 *		postgres	- PostgreSQL 7 and higher,
 *		mssql		- Microsoft SQL Server 2000 and higher,
 *		db2			- IBM DB2, Cloudscape, and Apache Derby (http://php.net/ibm-db2)
 *		oracle		- Oracle 8 and higher
 *		firebird	- Firebird/Interbase
 *		sybase		- Sybase ASE
 *		adodb-[drivername]	- ADOdb interface wrapper (see below),
 *		odbc		- ODBC DBO driver
 *
 * You can add custom database drivers (or override existing drivers) by adding the
 * appropriate file to app/models/datasources/dbo.  Drivers should be named 'dbo_x.php',
 * where 'x' is the name of the database.
 *
 * persistent => true / false
 * Determines whether or not the database should use a persistent connection
 *
 * connect =>
 * ADOdb set the connect to one of these
 *	(http://phplens.com/adodb/supported.databases.html) and
 *	append it '|p' for persistent connection. (mssql|p for example, or just mssql for not persistent)
 * For all other databases, this setting is deprecated.
 *
 * host =>
 * the host you connect to the database.  To add a socket or port number, use 'port' => #
 *
 * prefix =>
 * Uses the given prefix for all the tables in this database.  This setting can be overridden
 * on a per-table basis with the Model::$tablePrefix property.
 *
 * schema =>
 * For Postgres and DB2, specifies which schema you would like to use the tables in. Postgres defaults to
 * 'public', DB2 defaults to empty.
 *
 * encoding =>
 * For MySQL, MySQLi, Postgres and DB2, specifies the character encoding to use when connecting to the
 * database.  Uses database default.
 *
 */

////////////////////////////////////////
// helper functions
function db_redirect($url, $statusCode = 302) {
   header('Location: ' . $url, true, $statusCode);
   die();
}
function startsWith($haystack, $needle) {
	$length = strlen($needle);
	return (substr(strtolower($haystack), 0, $length) === strtolower($needle));
}

	

////////////////////////////////////////////////////////////////////
// if have welcome hash get param then store in cookie
// and redirect (to hide the get param and review cookie)
if (!empty($_GET['wh'])) {
	unset($_COOKIE['welcome_hash']);
	
	$cookie_date = time() + 60*60*24*30; // 30 days
	// NOTE: cookie works on both welcome and regular site
	setcookie('welcome_hash', mysql_real_escape_string($_GET['wh']), $cookie_date, '/admin/welcome', '.fotomatter.net');
	
	
	// redirect to remove the get param
	$request_uri_arr = parse_url($_SERVER['REQUEST_URI']);
	db_redirect("http://".$_SERVER['HTTP_HOST'].$request_uri_arr['path']); // DREW TODO - change this to https
}


// require db_config (could be welcome db_config)
require(ROOT."/db_configs.php");





///////////////////////////////////////////////////////////////////////////////////
// if we have the welcome cookie hash then try and see if we can use it
if (isset($_COOKIE['welcome_hash'])) {
	////////////////////////////////////////////////
	// get the welcome hash data from the db
	$welcome_hash = $_COOKIE['welcome_hash'];
	$link = mysql_connect($_SERVER['global']['host'], $_SERVER['global']['login'], $_SERVER['global']['password']);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($_SERVER['global']['database'], $link);
	$query = "SELECT * FROM welcome_hashes WHERE hash='$welcome_hash' LIMIT 1;";
	$query_result = mysql_query($query, $link);
	$hash_data = mysql_fetch_assoc($query_result);
	mysql_free_result($query_result);
	mysql_close($link);
	
	
	
	/////////////////////////////////////
	// check if hash is valid
	if (!empty($hash_data['hash']) && !empty($hash_data['created']) && !empty($hash_data['account_id']) && !empty($hash_data['site_domain'])) {
		// hash is valid - if on welcome site we do some more work
		$on_welcome_site = $_SERVER['HTTP_HOST'] === 'welcome.fotomatter.net';
		if ($on_welcome_site === true) {
			// check to make sure the hash is not too old
			$max_old = 60*15; // 15 mins
			$created = strtotime($hash_data['created']);
			if ($created + $max_old > time()) {
				// make sure that we are only trying to use the welcome controller or login via the welcome site
				if (startsWith($_SERVER['REQUEST_URI'], '/admin/welcome') === false && startsWith($_SERVER['REQUEST_URI'], '/admin/users/login') === false) {
					unset($_COOKIE['welcome_hash']);
					header('HTTP/1.0 404 Not Found');
					die();
				}
				
				
				// success - we can act as a new site's db
//				require("/var/www/accounts/{$hash_data['account_id']}/db_configs.php"); // DREW TODO - this needs to be on for on the real server
			} else {
				// redirect to the dns site
				$request_uri_arr = parse_url($_SERVER['REQUEST_URI']);
				db_redirect('http://fotomatter.dev/'.$request_uri_arr['path']); // DREW TODO - turn this off
				db_redirect('http://'.$hash_data['site_domain'].".fotomatter.net/".$request_uri_arr['path']); // DREW TODO - make this into https - and turn this on!
			}
		} else {
			// check if hash applies to current site
			if (false && $_SERVER['HTTP_HOST'] !== $hash_data['site_domain'].'.fotomatter.net') { // DREW TODO - remove the false &&
				unset($_COOKIE['welcome_hash']);
				header('HTTP/1.0 404 Not Found');
				die();
			}
		}
	} else {
		// means hash was bad so clear the cookie (so it won't work in welcome controller)
		unset($_COOKIE['welcome_hash']);
		header('HTTP/1.0 404 Not Found');
		die();
	}
} 


class DATABASE_CONFIG {

	var $default = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => '',
		'password' => '',
		'database' => '',
		'prefix' => '',
		//'encoding' => 'utf8',
	);

	/*var $old_photos = array(
               'driver' => 'mysql',
               'persistent' => false,
               'host' => 'localhost',
               'login' => 'root',
               'password' => '123000am',
               'database' => 'celestj7_images',
               'prefix' => '',
       ); */
	
	var $server_global = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => '',
		'password' => '',
		'database' => '',
		'prefix' => ''
	);
	
	public function __construct() {
	    $this->default['host'] = $_SERVER['local']['host'];
	    $this->default['login'] = $_SERVER['local']['login'];
	    $this->default['password'] = $_SERVER['local']['password'];
	    $this->default['database'] = $_SERVER['local']['database'];
	    
	    $this->server_global['host'] = $_SERVER['global']['host'];
	    $this->server_global['login'] = $_SERVER['global']['login'];
	    $this->server_global['password'] = $_SERVER['global']['password'];
	    $this->server_global['database'] = $_SERVER['global']['database'];
	    
	    
	}
}
