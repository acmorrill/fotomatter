<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `authnet_orders` ADD  `full_response` TEXT NOT NULL AFTER  `created`";
$sqls[] = "ALTER TABLE  `authnet_orders` ADD  `transaction_id` INT NOT NULL AFTER  `full_parsed_response`";
$sqls[] = "ALTER TABLE  `authnet_orders` CHANGE  `transaction_id`  `transaction_id` BIGINT( 11 ) NOT NULL";