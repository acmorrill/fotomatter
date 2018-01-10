<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE `photo_avail_sizes_photo_print_types` CHANGE `photo_print_type` `photo_print_type` CHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Valid types are ''self'', ''autofixed'', ''autodynamic''';";





