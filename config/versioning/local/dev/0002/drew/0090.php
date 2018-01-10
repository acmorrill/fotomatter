<?php

$sqls = array();

$functions = array();

$sqls[] = 'DROP TABLE IF EXISTS photo_avail_sizes_photo_print_types;';
$sqls[] = "
CREATE TABLE IF NOT EXISTS `photo_avail_sizes_photo_print_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_avail_size_id` int(11) NOT NULL,
  `photo_print_type_id` int(11) NOT NULL,
  `print_fulfiller_print_type_fixed_size_id` int(11) NOT NULL,
  `available` tinyint(1) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `handling_price` decimal(10,2) NOT NULL,
  `custom_turnaround` int(11) NOT NULL DEFAULT '0' COMMENT 'measured in days - 0 means use global default',
  `global_default` tinyint(1) NOT NULL,
  `force_settings` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;";
$sqls[] = "ALTER TABLE `photo_avail_sizes_photo_print_types` ADD `photo_print_type` CHAR( 20 ) NOT NULL COMMENT 'Valid types are ''self'', ''autofixed'', ''autodynamic'', ''autofixeddynamic''' AFTER `force_settings` ;";
$sqls[] = "TRUNCATE TABLE `photo_print_types` ";





