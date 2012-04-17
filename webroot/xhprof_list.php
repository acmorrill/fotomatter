<?php
//define cake defaults
/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 *
 */
	if (!defined('ROOT')) {
		define('ROOT', dirname(dirname(dirname(__FILE__))));
	}
/**
 * The actual directory name for the "app".
 *
 */
	if (!defined('APP_DIR')) {
		define('APP_DIR', basename(dirname(dirname(__FILE__))));
	}
/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 */
	if (!defined('CAKE_CORE_INCLUDE_PATH')) {
		define('CAKE_CORE_INCLUDE_PATH', ROOT);
	}

require_once(ROOT . "/app/config/database.php");
$db = new DATABASE_CONFIG();
mysql_connect($db->default['host'], $db->default['login'], $db->default['password']);
mysql_select_db($db->default['database']);

$config_contents = file_get_contents(ROOT."/app/config/core.php");
//Configure::write('debug', 4);
preg_match("/Configure\:\:write\([\"']{1}debug[\"']{1}[\s]*\,[\s]*(.*?)\)/i", $config_contents, $matches);
if ($matches[1] != 4) {
	header('HTTP/1.1 403 Forbidden');
	die();
}

$average_result = mysql_query("select AVG(nano_seconds) as average_time from xhprof_profiles");
$av_row = mysql_fetch_assoc($average_result);
$average_time = $av_row['average_time'];

$sql_result = mysql_query("select * from xhprof_profiles");
$results = array();
while ($row = mysql_fetch_assoc($sql_result)) {
	$row['diff'] = $row['nano_seconds'] - $average_time;
	if ($row['diff'] < -10000) {
		$row['color'] = 'green';
	} elseif ($row['diff'] > 10000) {
		$row['color'] = 'red';
	} else {
		$row['color'] = 'black';
	}
	$results[] = $row;
		
}
?>
<html lang="en" dir="ltr" class="no-js">
	<head>	
		<title>Xhprofile List</title>
	</head>
	<body>
		Average Nano Seconds: <?php echo $average_time; ?>
		<table border="1">
			<tr>
				<th>HTTP_ACCEPT</th>
				<th>Request Url</th>
				<th>Name Space</th>
				<th>View Chart</th>
				<th>Run Time</th>
				<th>Difference</th>
			</tr>
			<?php foreach ($results as $row): ?>
			<tr>
				<td><?php echo $row['http_accept']; ?></td>
				<td><?php echo $row['request_uri']; ?></td>
				<td><?php echo $row['name_space']; ?></td>
				<td><?php echo "<a href=\"http://xhprof/index.php?run={$row['xhprof_id']}&source={$row['name_space']}\">View Graph</a><br/>"; ?></td>
				<td><?php echo $row['nano_seconds']; ?></td>
				<td style="color:<?php echo $row['color']; ?>"><?php echo $row['diff']; ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</body>
</html>
