<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `authnet_profiles` ADD  `shipping_firstname` VARCHAR( 80 ) NOT NULL AFTER  `billing_phoneNumber`";
$sqls[] = "ALTER TABLE  `authnet_profiles` ADD  `shipping_lastname` VARCHAR( 80 ) NOT NULL AFTER  `shipping_firstname`";
$sqls[] = "ALTER TABLE  `authnet_profiles` ADD  `shipping_address` VARCHAR( 80 ) NOT NULL AFTER  `shipping_lastname`";
$sqls[] = "ALTER TABLE  `authnet_profiles` ADD  `shipping_city` VARCHAR( 80 ) NOT NULL AFTER  `shipping_address`";
$sqls[] = "ALTER TABLE  `authnet_profiles` ADD  `shipping_state` VARCHAR( 80 ) NOT NULL AFTER  `shipping_city`";
$sqls[] = "ALTER TABLE  `authnet_profiles` ADD  `shipping_zip` VARCHAR( 80 ) NOT NULL AFTER  `shipping_state`";
$sqls[] = "ALTER TABLE  `authnet_profiles` ADD  `shipping_country` VARCHAR( 80 ) NOT NULL AFTER  `shipping_zip`";
$sqls[] = "ALTER TABLE `authnet_profiles` drop column payment_cardCode;";