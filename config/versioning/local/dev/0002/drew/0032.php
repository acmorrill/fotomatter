<?php

$sqls = array();
$functions = array();



$sqls[] = "CREATE TABLE IF NOT EXISTS `cron_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `strtotime` char(128) NOT NULL,
  `class_type` char(30) NOT NULL,
  `class_name` varchar(80) NOT NULL,
  `method_name` varchar(80) NOT NULL,
  `last_run` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;";
