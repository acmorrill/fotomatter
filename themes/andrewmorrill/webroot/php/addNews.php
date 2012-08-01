<?php
	if (isset($_REQUEST['heading'])) { //if "email" is filled out, proceed  //check if the email address is invalid
		$heading = $_REQUEST['heading'];
		$content = $_REQUEST['content'];
		
		require_once("databaseConnect.php");
		mysql_select_db("celestj7_news", $con);
		mysql_query("INSERT INTO data (date, heading, content) 
		VALUES (NOW(), '$heading', '$content')");
		
		echo "news added";
	} else { //if "email" is not filled out, display the form
		echo "<form method='post' action='addNews.php'>
		Heading:</br>
		<input type='text' name='heading' size='50' maxlength='60'></br>
		Content:</br>
		<textarea name='content' wrap='physical' cols='47' rows='10' type='text'></textarea></br>
		<input type='submit' name='submit' value='Add'>
		</form>";
	}
?>