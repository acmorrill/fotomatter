<?php

$sqls = array();
$functions = array();

$functions[] = 
function() {
//update tmp cache locations
exec("cd ".TEMP_IMAGE_PATH."; rm -rf *", $output, $result);
if ($result != 0) {
	return false;
}

//create unit test directory
exec("cd ".ROOT.";mkdir unit_test_cache;", $output, $result);
if ($result != 0 ) {
	return false;
}

exec("cd ".ROOT.";mkdir image_vault;", $output, $result);
if ($result != 0) {
	return false;
}
return true;
};
