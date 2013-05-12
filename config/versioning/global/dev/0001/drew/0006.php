<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE `country_states` ADD INDEX (  `state_name` )";