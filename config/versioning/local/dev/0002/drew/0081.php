<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photo_caches` CHANGE  `cdn-filename`  `cdn-filename` CHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;";
$sqls[] = "ALTER TABLE  `photo_caches` CHANGE  `unsharp_amount`  `unsharp_amount` DECIMAL( 3,2 ) NULL DEFAULT  '0';";


