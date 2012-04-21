<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE IF NOT EXISTS `xhprof_profiles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `xhprof_id` varchar(15) NOT NULL,
  `http_accept` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1";

$sqls[] = "ALTER TABLE  `xhprof_profiles` ADD  `name_space` VARCHAR( 16 ) NOT NULL AFTER  `http_accept`";
$sqls[] = "ALTER TABLE  `xhprof_profiles` ADD  `request_uri` VARCHAR( 1080 ) NOT NULL AFTER  `http_accept`";
$sqls[] = "ALTER TABLE  `xhprof_profiles` ADD  `nano_seconds` FLOAT NOT NULL AFTER  `name_space`";
