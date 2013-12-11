<?php

$sqls = array();
$functions = array();



$sqls[] = "CREATE TABLE IF NOT EXISTS `paypal_reimbursement_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(10,2) NOT NULL,
  `all_data` text NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";