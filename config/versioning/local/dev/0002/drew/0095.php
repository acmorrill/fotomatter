<?php

$sqls = array();
$functions = array();

$functions[] = function() {
	if (is_dir(ROOT.DS.'local_fullsize_temp') === false) {
		exec("cd ".ROOT.";mkdir local_fullsize_temp; chmod 0777 local_fullsize_temp;", $output, $result);
		if ($result != 0 ) {
			return false;
		}
	}

	return true;
};