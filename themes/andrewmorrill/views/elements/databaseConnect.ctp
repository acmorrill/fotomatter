<?php
	// connect to database
	$con = mysql_connect("localhost","root","123000am");
	if (!$con) {
	  die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("celestj7_images", $con);
?>
