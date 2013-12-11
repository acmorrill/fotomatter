<?php

$sqls = array();
$functions = array();



$sqls[] = "ALTER TABLE  `paypal_reimbursement_logs` CHANGE  `order_ids`  `order_ids` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
$sqls[] = "INSERT INTO `cron_jobs` (`id`, `strtotime`, `class_type`, `class_name`, `method_name`, `last_run`, `modified`, `created`) VALUES
(null, '+30 minutes', 'model', 'CakeAuthnet.AuthnetOrder', 'check_for_settled_transactions', null, '2013-09-14 19:30:01', '2013-09-13 00:00:00');";
