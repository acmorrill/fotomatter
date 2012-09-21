<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE IF NOT EXISTS `theme_global_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(30) CHARACTER SET utf8 NOT NULL,
  `value` char(128) CHARACTER SET utf8 NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;";
