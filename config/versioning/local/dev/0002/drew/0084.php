<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE `photo_print_types` ADD `is_dynamic` TINYINT( 1 ) NULL DEFAULT NULL AFTER `print_fulfillment_type` ;";

