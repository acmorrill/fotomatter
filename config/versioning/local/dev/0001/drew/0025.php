<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE IF NOT EXISTS `themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL DEFAULT '0',
  `ref_name` char(40) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$sqls[] = "ALTER TABLE  `themes` CHANGE  `modified`  `modified` DATETIME NULL DEFAULT NULL;";
$sqls[] = "ALTER TABLE  `themes` ADD UNIQUE (`ref_name`)";







