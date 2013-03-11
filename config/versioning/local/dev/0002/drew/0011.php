<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `photo_sellable_prints` CHANGE  `available`  `available` TINYINT( 1 ) NULL DEFAULT NULL";
$sqls[] = "ALTER TABLE  `photo_sellable_prints` CHANGE  `price`  `price` DECIMAL( 10, 2 ) NULL DEFAULT NULL";
$sqls[] = "ALTER TABLE  `photo_sellable_prints` CHANGE  `shipping_price`  `shipping_price` DECIMAL( 10, 2 ) NULL DEFAULT NULL";
$sqls[] = "ALTER TABLE  `photo_sellable_prints` CHANGE  `custom_turnaround`  `custom_turnaround` CHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";