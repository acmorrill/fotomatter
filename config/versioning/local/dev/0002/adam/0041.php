<?php
$functions = array();
$sqls = array();

$sqls[] = 'ALTER TABLE  `account_domains` ADD  `expires` DATETIME NOT NULL AFTER  `url` ;';