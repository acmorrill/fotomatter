<?php
	// connect to database
	$con = mysql_connect("localhost","celestj7_andrew","!4ovLuc97)q*");
	if (!$con) {
	  die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("celestj7_images", $con);
?>
