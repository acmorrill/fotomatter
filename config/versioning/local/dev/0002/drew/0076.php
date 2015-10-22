<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photo_print_types` ADD  `print_fulfiller_id` INT UNSIGNED NULL AFTER  `print_fulfillment_type` ,
ADD  `print_fulfiller_print_type_id` INT UNSIGNED NULL AFTER  `print_fulfiller_id` ;";
