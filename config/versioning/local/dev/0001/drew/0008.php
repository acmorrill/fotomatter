<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE IF NOT EXISTS `major_errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` char(100) NOT NULL,
  `line_num` int(11) NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `extra_data` longtext CHARACTER SET utf8,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";


$sqls[] = "CREATE TABLE `photo_caches` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`photo_id` INT NOT NULL ,
`cdn-filename` CHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`max_width` INT NULL DEFAULT NULL ,
`max_height` INT NULL DEFAULT NULL ,
`created` DATETIME NULL DEFAULT NULL ,
`modified` DATETIME NULL DEFAULT NULL
) ENGINE = MYISAM ;";


$sqls[] = "ALTER TABLE  `photos` ADD  `pixel_width` INT NOT NULL AFTER  `photo_format_id` ,
ADD  `pixel_height` INT NOT NULL AFTER  `pixel_width` ,
ADD  `tag_attributes` CHAR( 100 ) NOT NULL AFTER  `pixel_height`";

$sqls[] = "ALTER TABLE  `photos` CHANGE  `pixel_width`  `pixel_width` INT( 11 ) NULL DEFAULT NULL";
$sqls[] = "ALTER TABLE  `photos` CHANGE  `pixel_height`  `pixel_height` INT( 11 ) NULL DEFAULT NULL";
$sqls[] = "ALTER TABLE  `photos` CHANGE  `tag_attributes`  `tag_attributes` CHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL";
$sqls[] = "ALTER TABLE  `photo_caches` ADD UNIQUE (
`cdn-filename`
)";
$sqls[] = "ALTER TABLE  `photo_caches` ADD UNIQUE (
`photo_id` ,
`max_width` ,
`max_height`
)";
$sqls[] = "ALTER TABLE  `photo_caches` CHANGE  `cdn-filename`  `cdn-filename` CHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";
$sqls[] = "ALTER TABLE  `photo_caches` ADD  `status` ENUM(  'queued',  'processing',  'ready' ) NOT NULL DEFAULT  'queued' AFTER  `max_height`";
$sqls[] = "ALTER TABLE  `photos` ADD  `cdn-filename-forcache` CHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER  `cdn-filename`";
$sqls[] = "ALTER TABLE  `photos` ADD UNIQUE (
`cdn-filename-forcache`
)";

// FILE COMMITTED



