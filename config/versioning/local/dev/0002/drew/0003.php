<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE IF NOT EXISTS `theme_user_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_id` int(11) NOT NULL,
  `name` char(30) NOT NULL,
  `value` text NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `theme_id` (`theme_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

