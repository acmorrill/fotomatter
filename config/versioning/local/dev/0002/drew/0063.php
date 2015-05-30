<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD  `override_pricing` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `id` ;";
