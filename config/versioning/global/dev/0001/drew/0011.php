<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `major_error_aggragate` ADD  `error_date` DATE NOT NULL AFTER  `description`";
$sqls[] = "ALTER TABLE  `major_error_aggragate` DROP INDEX  `description`";
$sqls[] = "ALTER TABLE  `major_error_aggragate` ADD UNIQUE (`description` ,`error_date`)";
$sqls[] = "ALTER TABLE  `major_error_aggragate` ADD INDEX (  `error_date` )";


