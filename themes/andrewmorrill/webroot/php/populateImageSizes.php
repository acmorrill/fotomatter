<?php 
	require("databaseConnect.php");
	
	$result = mysql_query("SELECT * FROM allimages");
	
	while($row = mysql_fetch_array($result)) {
		$img_src = $row['title'];
		
		// set large sizes images
		$largeSize = getimagesize ("../photos/large/".$img_src);
		mysql_query("UPDATE allimages SET webWidth = '$largeSize[0]' WHERE title = '$row[title]'");
		mysql_query("UPDATE allimages SET webHeight = '$largeSize[1]' WHERE title = '$row[title]'");
		
		// set extra large size images
		$extraLargeSize = getimagesize ("../photos/extraLarge/".$img_src);
		mysql_query("UPDATE allimages SET largeWebWidth = '$extraLargeSize[0]' WHERE title = '$row[title]'");
		mysql_query("UPDATE allimages SET largeWebHeight = '$extraLargeSize[1]' WHERE title = '$row[title]'");
		
		// set thumbnail sizes of images
		$thumbSize = getimagesize ("../photos/thumbs/".$img_src);
		mysql_query("UPDATE allimages SET thumbWidth = '$thumbSize[0]' WHERE title = '$row[title]'");
		mysql_query("UPDATE allimages SET thumbHeight = '$thumbSize[1]' WHERE title = '$row[title]'");
	}
	
	mysql_close($con);
	print("<h1>Images Sizes Populated</h1>");

?>