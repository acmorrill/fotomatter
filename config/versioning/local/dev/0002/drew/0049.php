<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `account_domains` CHANGE  `type`  `type` CHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'purchased' COMMENT  'purchased|external|system';";





