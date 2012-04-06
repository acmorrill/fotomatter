<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE  `photo_galleries` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`display_name` CHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`description` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`created` DATETIME NULL DEFAULT NULL ,
`modified` DATETIME NULL DEFAULT NULL
) ENGINE = MYISAM ;";

$sqls[] = "CREATE TABLE  `photo_galleries_photos` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`photo_id` INT NOT NULL ,
`photo_gallery_id` INT NOT NULL
) ENGINE = MYISAM ;";

$sqls[] = "ALTER TABLE `photo_galleries_photos` ADD UNIQUE (
`photo_id` ,
`photo_gallery_id`
)";

$sqls[] = "DROP TABLE  `categories`;";

$sqls[] = "ALTER TABLE  `photo_galleries` ADD  `type` ENUM(  'smart',  'standard' ) NOT NULL DEFAULT  'standard' AFTER  `id`;";

$sqls[] = "ALTER TABLE  `photo_galleries` ADD  `weight` INT NOT NULL AFTER  `id`;";

// this is commited




