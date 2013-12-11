<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` CHANGE  `non_pano_price`  `non_pano_price` DECIMAL( 10, 2 ) NOT NULL";
$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` CHANGE  `non_pano_shipping_price`  `non_pano_shipping_price` DECIMAL( 10, 2 ) NOT NULL";
$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` CHANGE  `pano_price`  `pano_price` DECIMAL( 10, 2 ) NOT NULL";
$sqls[] = "ALTER TABLE  `photo_avail_sizes_photo_print_types` CHANGE  `pano_shipping_price`  `pano_shipping_price` DECIMAL( 10, 2 ) NOT NULL";