<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `authnet_orders` ADD  `payment_cc_last_four` CHAR( 10 ) NOT NULL AFTER  `total`";
$sqls[] = "ALTER TABLE  `authnet_orders` ADD  `one_time_authorization_code` CHAR( 12 ) NOT NULL AFTER  `payment_cc_last_four`";
$sqls[] = "ALTER TABLE  `authnet_orders` ADD  `6` CHAR NOT NULL AFTER  `one_time_authorization_code`";
$sqls[] = "ALTER TABLE  `authnet_orders` CHANGE  `6`  `expiration_date` CHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL";