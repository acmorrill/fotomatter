<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `major_error_aggragate` ADD  `aggregated` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `id`";
$sqls[] = "ALTER TABLE  `major_error_aggragate` ADD INDEX (  `error_date` ,  `aggregated` )";
$sqls[] = "ALTER TABLE  `major_error_aggragate` ADD INDEX (  `aggregated` )";


