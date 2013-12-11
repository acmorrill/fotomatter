<?php
	require("dimension.php");
	require("databaseConnect.php");
	
	//get all images
	$images = mysql_query("SELECT * FROM allimages");
	
	$standardFormat = array('11', '16', '20', '24', '30', '35', '40', '44', '48');
	$panoramic = array('10', '16', '22', '29');
	
	while($row = mysql_fetch_array($images)) {
		// get image dimensions
		$img_src = $row['title'];
		$dimensions = getimagesize ("../photos/large/".$img_src);
		
		// get image tier
		$tierNum = $row['tier'];
		$tier = mysql_fetch_array(mysql_query("Select * from tiers where id = $tierNum"));
		
		// get image format
		$format = $row['format'];
		
		// get images sizes
		$sizes = split(",",$row['availSizes']);
		
		$imageDimens = array();
		for ($i = 0; $i < sizeof($sizes); $i++) {
			if ($format == "portrait") {
				$newDimension = new Dimension($row['id'], ($dimensions[0]-20), ($dimensions[1]-20), $tier, $sizes[$i], 0);
				$newDimension->calcBaseWidthHeight();
				$newDimension->calcAveDimen();
				$imageDimens[$i] = $newDimension;
			} else {
				$newDimension = new Dimension($row['id'], ($dimensions[0]-20), ($dimensions[1]-20), $tier, $sizes[$i], 1);
				$newDimension->calcBaseWidthHeight();
				$newDimension->calcAveDimen();
				$imageDimens[$i] = $newDimension;
			}
		}
		
		// save dimensions array to database
		$savetodb = serialize($imageDimens);		
		$query = "UPDATE allimages SET sizePriceArray='$savetodb' where id = '$row[id]'";
		mysql_query($query);
	}
	
	mysql_close($con);
	print("<h1>Images prices and sizes calculated</h1>");

?>