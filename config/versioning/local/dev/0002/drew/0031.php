<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `authnet_orders` CHANGE  `pay_out_status`  `pay_out_status` CHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'not_paid'";
