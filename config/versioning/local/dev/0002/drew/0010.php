<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `photo_sellable_prints` ADD  `override_for_photo` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `photo_avail_sizes_photo_print_type_id`";
$sqls[] = "ALTER TABLE  `photo_sellable_prints` ADD  `price` DECIMAL( 10, 2 ) NOT NULL AFTER  `override_for_photo`";
$sqls[] = "ALTER TABLE  `photo_sellable_prints` ADD  `shipping_price` DECIMAL( 10, 2 ) NOT NULL AFTER  `price`";
$sqls[] = "ALTER TABLE  `photo_sellable_prints` ADD  `custom_turnaround` CHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `shipping_price`";
$sqls[] = "ALTER TABLE  `photo_sellable_prints` ADD  `available` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `override_for_photo`";