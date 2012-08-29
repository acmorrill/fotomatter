<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE IF NOT EXISTS `site_one_level_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `external_id` int(11) NOT NULL,
  `external_model` char(50) NOT NULL,
  `weight` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;";









