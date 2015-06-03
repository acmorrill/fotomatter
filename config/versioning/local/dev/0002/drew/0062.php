<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD  `use_date_taken` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `date_taken` ;";
