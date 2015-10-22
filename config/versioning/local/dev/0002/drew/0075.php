<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photo_print_types` CHANGE  `print_fulfillment_type`  `print_fulfillment_type` CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'self' COMMENT 'Valid types are ''self'', ''autofixed'', ''autodynamic'', ''automisc''';";
