<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `authnet_orders` ADD  `one_time_charge` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `id`";