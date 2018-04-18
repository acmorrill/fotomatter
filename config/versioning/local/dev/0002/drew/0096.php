<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE `authnet_orders` ADD `processing_to_overlord` TINYINT(1) NOT NULL DEFAULT '0' AFTER `id`, ADD `processed_to_overlord` TINYINT(1) NOT NULL DEFAULT '0' AFTER `processing_to_overlord`, ADD `process_to_overlord_status` VARCHAR(30) NULL AFTER `processed_to_overlord`;";
$sqls[] = "INSERT INTO `cron_jobs` (`id`, `strtotime`, `class_type`, `class_name`, `method_name`, `last_run`, `run_count`, `success_count`, `failure_count`, `last_runtime`, `average_runtime`, `modified`, `created`) VALUES (NULL, '+2 minutes', 'component', 'FotomatterBilling', 'process_authnet_orders_to_overlord', NULL, '0', '0', '0', '0', '0', NULL, NULL);";
$sqls[] = "ALTER TABLE `authnet_orders` ADD INDEX `processing_to_overlord` (`processing_to_overlord`, `processed_to_overlord`, `created`)";