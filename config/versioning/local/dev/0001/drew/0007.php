<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD UNIQUE (
`cdn-filename`
)";

$sqls[] = "ALTER TABLE  `photos` CHANGE  `cdn-filename`  `cdn-filename` CHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
