<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `authnet_profiles` ADD  `payment_method` VARCHAR( 80 ) NOT NULL AFTER  `shipping_country`";