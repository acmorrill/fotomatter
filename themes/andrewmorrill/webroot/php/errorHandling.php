<?php
function customError($error_level, $error_message, $error_file, $error_line, $error_context) { 
	echo "<b>Error:</b> [$error_level] $error_message, $error_file, $error_line, $error_context<br />";
	echo "Ending Script";
	die();
}

set_error_handler("customError");
?>
