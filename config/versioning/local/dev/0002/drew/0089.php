<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE `photo_print_types` DROP `use_dynamic` ;";
$sqls[] = "ALTER TABLE `photo_avail_sizes_photo_print_types` ADD `fixed_available` TINYINT( 1 ) NOT NULL AFTER `photo_print_type_id` ,
ADD `fixed_price` DECIMAL( 10, 2 ) NOT NULL AFTER `fixed_available` ,
ADD `fixed_shipping_price` DECIMAL( 10, 2 ) NOT NULL AFTER `fixed_price` ,
ADD `fixed_custom_turnaround` CHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `fixed_shipping_price` ,
ADD `fixed_global_default` TINYINT( 1 ) NOT NULL AFTER `fixed_custom_turnaround` ,
ADD `fixed_force_settings` TINYINT( 1 ) NOT NULL AFTER `fixed_global_default` ;";
$sqls[] = "ALTER TABLE `photo_avail_sizes_photo_print_types` CHANGE `fixed_shipping_price` `fixed_handling_price` DECIMAL( 10, 2 ) NOT NULL ;";
$sqls[] = "ALTER TABLE `photo_avail_sizes_photo_print_types` CHANGE `fixed_price` `fixed_price_increase_percent` FLOAT NOT NULL ;";
$sqls[] = "ALTER TABLE `photo_avail_sizes_photo_print_types` CHANGE `fixed_custom_turnaround` `fixed_custom_turnaround` INT NOT NULL COMMENT 'measured in days';";
$sqls[] = "ALTER TABLE `photo_avail_sizes_photo_print_types` ADD `print_fulfiller_print_type_fixed_size_id` INT NOT NULL AFTER `photo_print_type_id` ;";
$sqls[] = "ALTER TABLE `photo_avail_sizes_photo_print_types` CHANGE `fixed_price_increase_percent` `fixed_price` DECIMAL( 10, 2 ) NOT NULL ;";


