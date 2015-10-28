<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photos` CHANGE  `cdn-filename`  `cdn-filename` CHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;";
$sqls[] = "ALTER TABLE  `photos` CHANGE  `cdn-filename-forcache`  `cdn-filename-forcache` CHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;";
$sqls[] = "ALTER TABLE  `photos` CHANGE  `cdn-filename-smaller-forcache`  `cdn-filename-smaller-forcache` CHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;";
$sqls[] = "ALTER TABLE  `photo_caches` CHANGE  `cdn-filename`  `cdn-filename` CHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;";

$sqls[] = "ALTER TABLE `photo_galleries` ADD INDEX `weight` (`weight`)";
$sqls[] = "ALTER TABLE `photo_galleries_photos` ADD INDEX `photo_order` (`photo_gallery_id`, `photo_order`)";
