<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `authnet_orders` ADD  `one_time_response_code` INT NOT NULL DEFAULT  '0' AFTER  `one_time_charge` ;";
$sqls[] = "ALTER TABLE  `authnet_orders` CHANGE  `order_status`  `order_status` CHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT  'new' COMMENT 'values go from new -> approved -> settled';";