<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `major_errors` CHANGE  `severity`  `type` CHAR( 8 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'normal'";
