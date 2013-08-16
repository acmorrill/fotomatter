<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE IF NOT EXISTS `welcome_hashes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` text NOT NULL,
  `account_id` char(36) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
$sqls[] = "ALTER TABLE  `welcome_hashes` CHANGE  `hash`  `hash` CHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
$sqls[] = "ALTER TABLE  `welcome_hashes` ADD UNIQUE (`account_id`)";
$sqls[] = "ALTER TABLE  `welcome_hashes` ADD UNIQUE (`hash`)";
