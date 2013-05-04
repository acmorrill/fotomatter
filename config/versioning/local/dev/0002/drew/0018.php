<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `authnet_orders` CHANGE  `foreign_key`  `foreign_key` CHAR( 36 ) NULL DEFAULT NULL;";
$sqls[] = "ALTER TABLE  `authnet_profiles` CHANGE  `user_id`  `user_id` CHAR( 36 ) NOT NULL;";