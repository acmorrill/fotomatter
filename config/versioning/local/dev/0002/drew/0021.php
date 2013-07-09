<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `photo_print_types` ADD  `print_fulfillment_type` CHAR( 30 ) NOT NULL DEFAULT  'self' AFTER  `turnaround_time`";