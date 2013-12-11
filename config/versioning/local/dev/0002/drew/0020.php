<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `authnet_orders` ADD  `order_status` CHAR( 20 ) NOT NULL DEFAULT  'new' AFTER  `id`";