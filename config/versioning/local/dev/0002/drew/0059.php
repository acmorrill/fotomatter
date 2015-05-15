<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `authnet_orders` ADD  `one_time_response_code` INT NOT NULL DEFAULT  '0' AFTER  `one_time_charge` ;";