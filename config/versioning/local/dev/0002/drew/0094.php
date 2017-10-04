<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE `photo_print_types` ADD `print_type_ships_by_itself` TINYINT NULL COMMENT 'used for estimating shipping costs in the frontend shopping cart. Only applies to self fulfillment.' AFTER `turnaround_time`, ADD `print_type_can_be_rolled` TINYINT NULL COMMENT 'used for estimating shipping costs in the frontend shopping cart. Only applies to self fulfillment.' AFTER `print_type_ships_by_itself`;";





