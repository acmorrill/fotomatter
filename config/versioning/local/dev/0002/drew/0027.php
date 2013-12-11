<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `authnet_orders` ADD  `approval_date` DATETIME NULL AFTER  `order_status`";
$sqls[] = "ALTER TABLE  `authnet_line_items` ADD  `approval_date` DATETIME NULL AFTER  `order_status`";
$sqls[] = "ALTER TABLE  `authnet_orders` ADD  `pay_out_status` CHAR( 20 ) NOT NULL DEFAULT  'not_payed' AFTER  `order_status`";