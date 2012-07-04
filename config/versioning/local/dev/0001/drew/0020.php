<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE `site_pages` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` CHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`weight` INT NOT NULL ,
`type` CHAR( 20 ) NOT NULL DEFAULT  'custom',
`created` DATETIME NULL DEFAULT NULL ,
`modified` DATETIME NULL DEFAULT NULL
) ENGINE = MYISAM COMMENT =  'possible types are ''custom'',''smart''';";

