<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE `welcome_hashes` CHANGE `hash` `hash` CHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'This is the hash that is on the newly built account table on overlord (not the welcome hash)';";


