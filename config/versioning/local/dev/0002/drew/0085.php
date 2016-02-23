<?php

$sqls = array();

$functions = array();

$sqls[] = "INSERT INTO `cron_jobs` (`id`, `strtotime`, `class_type`, `class_name`, `method_name`, `last_run`, `run_count`, `success_count`, `failure_count`, `last_runtime`, `average_runtime`, `modified`, `created`) VALUES (NULL, '+1 hours', 'component', 'FotomatterNoticeEmail', 'send_all_unsent_notice_emails', NULL, '0', '0', '0', '0', '0', '2016-02-20 00:00:00', '2016-02-20 00:00:00');";


