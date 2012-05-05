<?php

$sqls = array();
$functions = array();

$sqls[] = 'CREATE TABLE IF NOT EXISTS `hashes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` char(32) NOT NULL,
  `name_space` char(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';
