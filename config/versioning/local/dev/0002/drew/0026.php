<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `authnet_line_items` ADD  `order_status` CHAR( 20 ) NOT NULL AFTER  `id`";
$sqls[] = "ALTER TABLE  `authnet_line_items` CHANGE  `order_status`  `order_status` CHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT  'new'";