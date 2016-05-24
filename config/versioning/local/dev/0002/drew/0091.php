<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE `photo_print_types` CHANGE `turnaround_time` `turnaround_time` INT( 11 ) NOT NULL DEFAULT '14' COMMENT 'measured in days';";





