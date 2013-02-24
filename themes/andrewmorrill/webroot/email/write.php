<?php
require_once("../php/databaseConnect.php");
mysql_select_db("celestj7_maillist", $con);

$user_data = $_GET['var'];
$safe_data = mysql_real_escape_string($user_data);

mysql_query("INSERT INTO users (name, email, time_created) VALUES('noname', '$safe_data', NOW());") or die(mysql_error());
?>
