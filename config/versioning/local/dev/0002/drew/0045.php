<?php

$sqls = array();

$functions = array();

$sqls[] = "INSERT INTO  `cron_jobs` (`id` ,`strtotime` ,`class_type` ,`class_name` ,`method_name` ,`last_run` ,`modified` ,`created`)
VALUES (NULL ,  '+1 day 3 am',  'model',  'AccountDomain',  'send_expired_domain_emails', NULL ,  '2014-05-17 00:00:00',  '2014-05-17 00:00:00');";

