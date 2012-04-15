<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `major_errors` ADD  `severity` ENUM(  'low',  'normal',  'high' ) NOT NULL AFTER  `extra_data`;";
$sqls[] = "ALTER TABLE  `major_errors` CHANGE  `severity`  `severity` ENUM(  'low',  'normal',  'high' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT  'normal'";

/// the above is committed




