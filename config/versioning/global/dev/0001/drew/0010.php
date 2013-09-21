<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE IF NOT EXISTS `major_errors` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `aggregated` tinyint(1) NOT NULL DEFAULT '0',
  `account_id` char(36) NOT NULL,
  `location` char(100) NOT NULL,
  `line_num` int(11) NOT NULL,
  `description` char(255) CHARACTER SET utf8 NOT NULL,
  `extra_data` longtext CHARACTER SET utf8,
  `severity` char(8) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'normal',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aggregated` (`aggregated`),
  KEY `description` (`description`,`aggregated`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$sqls[] = "CREATE TABLE IF NOT EXISTS `major_error_aggragate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` char(255) NOT NULL,
  `severity` char(30) NOT NULL,
  `count` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
