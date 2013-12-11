<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD  `cdn-filename-smaller-forcache` CHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `cdn-filename-forcache`";
$sqls[] = "ALTER TABLE  `photos` CHANGE  `cdn-filename-smaller-forcache`  `cdn-filename-smaller-forcache` CHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
$sqls[] = "ALTER TABLE  `photos` ADD  `smaller_forcache_pixel_width` INT NULL AFTER  `forcache_pixel_height`";
$sqls[] = "ALTER TABLE  `photos` ADD  `smaller_forcache_pixel_height` INT NULL AFTER  `smaller_forcache_pixel_width`";

$functions[] = function() {
	if (is_dir(ROOT.DS.'local_smaller_master_cache') === false) {
		exec("cd ".ROOT.";mkdir local_smaller_master_cache;", $output, $result);
		if ($result != 0 ) {
			return false;
		}
	}

	return true;
};
