<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE IF NOT EXISTS `domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(65) COLLATE utf8_bin NOT NULL,
  `tld` char(4) COLLATE utf8_bin NOT NULL,
  `renewal_date` datetime NOT NULL,
  `is_primary` tinyint(1) NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `organization` varchar(80) NULL,
  `address_1` varchar(80) NOT NULL,
  `address_2` varchar(80) NOT NULL,
  `country_id` int(11) NOT NULL,
  `country_state_id` int(11) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `fax` varchar(20) NULL,
  `modified` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;";

