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

$sqls[] = "CREATE TABLE IF NOT EXISTS `photo_print_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `print_name` char(255) NOT NULL,
  `turnaround_time` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$sqls[] = "CREATE TABLE IF NOT EXISTS `photo_avail_sizes_photo_print_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_avail_size_id` int(11) NOT NULL,
  `photo_print_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` ADD  `non_pano_available` TINYINT( 1 ) NOT NULL AFTER  `photo_print_type_id`";

$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` ADD  `non_pano_price` DECIMAL NOT NULL AFTER  `non_pano_available`";

$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` ADD  `non_pano_shipping_price` DECIMAL NOT NULL AFTER  `non_pano_price`";

$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` ADD  `non_pano_global_default` TINYINT( 1 ) NOT NULL AFTER `non_pano_shipping_price`";

$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` ADD  `non_pano_force_settings` TINYINT( 1 ) NOT NULL AFTER `non_pano_global_default`";

$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` ADD  `pano_available` TINYINT( 1 ) NOT NULL AFTER  `non_pano_force_settings`";

$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` ADD  `pano_price` DECIMAL NOT NULL AFTER  `pano_available`";

$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` ADD  `pano_shipping_price` DECIMAL NOT NULL AFTER  `pano_price`";

$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` ADD  `pano_global_default` TINYINT( 1 ) NOT NULL AFTER `pano_shipping_price`";

$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` ADD  `pano_force_settings` TINYINT( 1 ) NOT NULL AFTER `pano_global_default`";

$sqls[] = "ALTER TABLE  `photo_print_types` ADD `order` INT NOT NULL AFTER `id`";

$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` ADD  `non_pano_custom_turnaround` CHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `non_pano_shipping_price`";

$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` ADD  `pano_custom_turnaround` CHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `pano_shipping_price`";
