<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `authnet_orders` ADD  `refund_transaction_id` INT( 11 ) NOT NULL AFTER  `transaction_id`";
$sqls[] = "ALTER TABLE  `authnet_orders` CHANGE  `refund_transaction_id`  `refund_transaction_id` BIGINT( 11 ) NOT NULL";