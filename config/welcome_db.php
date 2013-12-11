<?php 

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
	$on_welcome_site = $_SERVER['HTTP_HOST'] === WELCOME_SITE_URL;
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
				require("/var/www/accounts/{$hash_data['account_id']}/db_configs.php"); // DREW TODO - this needs to be on for on the real server
			} else {
				// redirect to the dns site
				$request_uri_arr = parse_url($_SERVER['REQUEST_URI']);
//				db_redirect('http://fotomatter.dev/'.$request_uri_arr['path']); // DREW TODO - turn this off
				db_redirect('http://'.$hash_data['site_domain'].".fotomatter.net/".$request_uri_arr['path']); // DREW TODO - make this into https - and turn this on!
			}
		} else {
			// check if hash applies to current site
			if ($_SERVER['HTTP_HOST'] !== $hash_data['site_domain'].'.fotomatter.net') { // DREW TODO - remove the false &&
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