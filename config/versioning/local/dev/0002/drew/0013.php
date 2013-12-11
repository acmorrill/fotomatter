<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `photo_caches` ADD  `crop` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `max_height`;";
$sqls[] = "ALTER TABLE  `photo_caches` DROP INDEX  `photo_id` ,
ADD UNIQUE  `photo_id` (  `photo_id` ,  `max_width` ,  `max_height` ,  `crop` );";