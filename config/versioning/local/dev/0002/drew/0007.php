<?php

$sqls = array();
$functions = array();



$sqls[] = "CREATE TABLE IF NOT EXISTS `photo_avail_sizes` (
  `id` int(11) NOT NULL DEFAULT '0',
  `short_side_length` float NOT NULL,
  `photo_format_ids` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$sqls[] = "ALTER TABLE `photo_avail_sizes` ADD UNIQUE ( `short_side_length` );";

$sqls[] = "ALTER TABLE  `photo_avail_sizes` CHANGE  `id`  `id` INT( 11 ) NOT NULL AUTO_INCREMENT;";
