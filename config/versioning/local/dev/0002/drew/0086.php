<?php

$sqls = array();

$functions = array();

$sqls[] = "CREATE TABLE IF NOT EXISTS `account_email_notices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email_key` char(150) NOT NULL,
  `to_send_date` datetime NOT NULL,
  `sent_date` datetime DEFAULT NULL,
  `email_data` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_key` (`email_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";


