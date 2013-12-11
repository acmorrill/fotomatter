<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `users` ADD  `admin` TINYINT( 1 ) NOT NULL DEFAULT  '1' AFTER  `id`;";