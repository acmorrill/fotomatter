<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `welcome_hashes` ADD  `site_domain` CHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `account_id`";
