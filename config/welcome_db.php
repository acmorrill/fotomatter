<?php
////////////////////////////////////////
// helper functions
function get_local_db_handle_in_welcome() {
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


function record_major_welcome_error($description, $log_data) {
	$local_db = get_local_db_handle_in_welcome();
	
	$location = 'welcome_db.php';
	$line_number = 0;
	$description = mysql_real_escape_string($description);
	$log_data_str = mysql_real_escape_string(print_r($log_data, true));
	$account_id = basename(realpath(ROOT));
	$sql = "INSERT INTO  `major_errors` (`id` , `account_id`, `location` ,`line_num` ,`description` ,`extra_data` ,`severity` ,`created`)
		VALUES (NULL ,  '$account_id', '$location',  '$line_number',  '$description',  '$log_data_str',  'high', NOW()  );
	";
	mysql_query($sql, $local_db);
}


function db_redirect($url, $statusCode = 302) {
	header('Location: ' . $url, true, $statusCode);
	die();
}

function startsWith($haystack, $needle) {
	$length = strlen($needle);
	return (substr(strtolower($haystack), 0, $length) === strtolower($needle));
}

function get_hash_data($welcome_hash) {
	////////////////////////////////////////////////
	// get the welcome hash data from the db
	$link = mysql_connect($_SERVER['global']['host'], $_SERVER['global']['login'], $_SERVER['global']['password']);
	if (!$link) {
		record_major_welcome_error("could not connect to global database", compact('_SERVER'));
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($_SERVER['global']['database'], $link);
	$welcome_hash = mysql_real_escape_string($welcome_hash, $link);
	$query = "SELECT * FROM welcome_hashes WHERE hash='$welcome_hash' LIMIT 1;";
	$query_result = mysql_query($query, $link);
	$hash_data = mysql_fetch_assoc($query_result);
	mysql_free_result($query_result);
	mysql_close($link);
	
	return $hash_data;
}

////////////////////////////////////////////////////////////////////
// if have welcome hash get param then store in cookie
// and redirect (to hide the get param and review cookie)
if (!empty($_GET['wh'])) {
	unset($_COOKIE['welcome_hash']);
	
	$escaped_wh = mysql_escape_string($_GET['wh']); // NOTE: cannot use mysql_real_escape_string because there is not actual db connection yet
	$hash_data = get_hash_data($escaped_wh);

	
	////////////////////////////////////////////////////////////////////
	// NOTE: cookie works on both welcome and regular site
	$cookie_date = time() + 60 * 60 * 24 * 30; // 30 days
	if (isset($hash_data['site_domain'])) {
		setcookie('welcome_hash', $escaped_wh, $cookie_date, '/admin/welcome/', "welcome.fotomatter.net");
		setcookie('welcome_hash', $escaped_wh, $cookie_date, '/admin/welcome/', "{$hash_data['site_domain']}.fotomatter.net");
	} else {
		record_major_welcome_error("hash data empty 1", compact('hash_data', '_GET', '_SERVER'));
	}


	// redirect to remove the get param
	$request_uri_arr = parse_url($_SERVER['REQUEST_URI']);
	db_redirect("https://" . $_SERVER['HTTP_HOST'] . $request_uri_arr['path']);
}


// require db_config (could be welcome db_config)
require(ROOT . "/db_configs.php");



///////////////////////////////////////////////////////////////////////////////////
// if we have the welcome cookie hash then try and see if we can use it
// to pull in the actual sites db_configs.php
if (isset($_COOKIE['welcome_hash'])) {
	$WELCOME_SITE_URL = WELCOME_SITE_URL;
	if (empty($WELCOME_SITE_URL)) {
		$WELCOME_SITE_URL = 'welcome.fotomatter.net';
	}
	$on_welcome_site = $_SERVER['HTTP_HOST'] === $WELCOME_SITE_URL;
	
	
	////////////////////////////////////////////////
	// get the welcome hash data from the db
	$hash_data = get_hash_data($_COOKIE['welcome_hash']);
	

	/////////////////////////////////////
	// check if hash is valid
	if (!empty($hash_data['hash']) && !empty($hash_data['created']) && !empty($hash_data['account_id']) && !empty($hash_data['site_domain'])) {
		// hash is valid - if on welcome site we do some more work
		if ($on_welcome_site === true) {
			// check to make sure the hash is not too old
			/*$max_old = 60 * 15; // 15 mins
			$created = strtotime($hash_data['created']);
			if ($created + $max_old > time()) { // will only work if the hash was created within the last 15 minutes*/
				// make sure that we are only trying to use the welcome controller or login via the welcome site
				if (startsWith($_SERVER['REQUEST_URI'], '/admin/welcome') == false && startsWith($_SERVER['REQUEST_URI'], '/admin/users/login') == false) {
					unset($_COOKIE['welcome_hash']);
					record_major_welcome_error("in bad place on welcome site", compact('hash_data', '_COOKIE', '_SERVER'));
					header('HTTP/1.0 404 Not Found');
					die();
				}
				

				// success - we can act as a new site's db
				require("/var/www/accounts/{$hash_data['account_id']}/db_configs.php"); // DREW TODO - this needs to be on for on the real server
			/*} else {
				// redirect to the dns site
				$request_uri_arr = parse_url($_SERVER['REQUEST_URI']);
				$redirect_path = 'https://' . $hash_data['site_domain'] . ".fotomatter.net" . $request_uri_arr['path'];
//				db_redirect('http://fotomatter.dev/'.$request_uri_arr['path']); // DREW TODO - turn this off
				db_redirect($redirect_path); // DREW TODO - make this into https - and turn this on!
			}*/
		} else {
			// check if hash applies to current site
			if ($_SERVER['HTTP_HOST'] !== $hash_data['site_domain'] . '.fotomatter.net') {
				unset($_COOKIE['welcome_hash']);
				record_major_welcome_error("hash applies to some other site", compact('hash_data', '_COOKIE', '_SERVER'));
				header('HTTP/1.0 404 Not Found');
				die();
			}
		}
	} else {
		// means hash was bad so clear the cookie (so it won't work in welcome controller)
		unset($_COOKIE['welcome_hash']);
		record_major_welcome_error("hash data empty 2", compact('hash_data', '_COOKIE', '_SERVER'));
		header('HTTP/1.0 404 Not Found');
		die();
	}
} 