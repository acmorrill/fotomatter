<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE  `categories` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`type` ENUM(  'smart',  'standard' ) NOT NULL ,
`display_name` CHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`created` DATETIME NULL DEFAULT NULL ,
`modified` DATETIME NULL DEFAULT NULL
) ENGINE = MYISAM ;";

$sqls[] = "CREATE TABLE  `photos` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`display_title` CHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`display_subtitle` CHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`alt_text` CHAR( 128 ) NOT NULL ,
`enabled` TINYINT NOT NULL DEFAULT  '1',
`photo_format_id` INT NOT NULL ,
`created` DATETIME NULL DEFAULT NULL ,
`modified` DATETIME NULL DEFAULT NULL
) ENGINE = MYISAM ;";

$sqls[] = "ALTER TABLE  `photos` CHANGE  `photo_format_id`  `photo_format_id` INT( 11 ) NOT NULL DEFAULT  '1';";


$sqls[] = "CREATE TABLE  `photo_formats` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`display_name` CHAR( 64 ) NOT NULL ,
`ref_name` CHAR( 64 ) NOT NULL
) ENGINE = MYISAM ;";

$sqls[] = "INSERT INTO  `photo_formats` (`id` ,`display_name` ,`ref_name`)
VALUES (NULL ,  'Landscape',  'landscape');";

$sqls[] = "INSERT INTO  `photo_formats` (`id` ,`display_name` ,`ref_name`)
VALUES (NULL ,  'Portrait',  'portrait');";

$sqls[] = "INSERT INTO  `photo_formats` (`id` ,`display_name` ,`ref_name`)
VALUES (NULL ,  'Square',  'square');";

$sqls[] = "INSERT INTO  `photo_formats` (`id` ,`display_name` ,`ref_name`)
VALUES (NULL ,  'Panoramic',  'panoramic');";

$sqls[] = "INSERT INTO  `photo_formats` (`id` ,`display_name` ,`ref_name`)
VALUES (NULL ,  'Vertical Panoramic',  'vertical_panoramic');";
