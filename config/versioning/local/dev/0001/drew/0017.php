<?php

$sqls = array();
$functions = array();

$functions[] = function() {
	if (is_dir(ROOT.DS.'local_master_cache') === false) {
		exec("cd ".ROOT.";mkdir local_master_cache;", $output, $result);
		if ($result != 0 ) {
			return false;
		}
	}

	return true;
};
