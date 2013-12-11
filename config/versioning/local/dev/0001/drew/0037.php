<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE IF NOT EXISTS `site_two_level_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_system` tinyint(4) NOT NULL DEFAULT '0',
  `ref_name` char(30) NOT NULL DEFAULT 'custom',
  `external_id` int(11) NOT NULL,
  `external_model` char(50) NOT NULL,
  `weight` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";

$sqls[] = "CREATE TABLE IF NOT EXISTS `site_two_level_menu_containers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` char(30) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$sqls[] = "CREATE TABLE IF NOT EXISTS `site_two_level_menu_container_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_two_level_menu_container_id` int(11) NOT NULL,
  `ref_name` char(30) NOT NULL,
  `external_id` int(11) NOT NULL,
  `external_model` char(50) NOT NULL,
  `weight` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";