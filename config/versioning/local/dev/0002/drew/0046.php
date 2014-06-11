<?php

$sqls = array();

$functions = array();

$sqls[] = "INSERT INTO `cron_jobs` (`id`, `strtotime`, `class_type`, `class_name`, `method_name`, `last_run`, `modified`, `created`) VALUES
(null, '+15 minutes', 'model', 'CronJob', 'send_cron_working_email', NULL, '2014-06-10 00:00:00', '2014-06-10 00:00:00');";





