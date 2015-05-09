<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `cron_jobs` ADD  `success_count` INT NOT NULL DEFAULT  '0' AFTER  `last_run` ;";
$sqls[] = "ALTER TABLE  `cron_jobs` ADD  `failure_count` INT NOT NULL DEFAULT  '0' AFTER  `success_count` ;";
$sqls[] = "ALTER TABLE  `cron_jobs` ADD  `run_count` INT NOT NULL DEFAULT  '0' AFTER  `last_run` ;";
$sqls[] = "ALTER TABLE  `cron_jobs` ADD  `average_runtime` FLOAT NOT NULL DEFAULT  '0' AFTER  `failure_count` ;";
$sqls[] = "ALTER TABLE  `cron_jobs` ADD  `last_runtime` FLOAT NOT NULL DEFAULT  '0' AFTER  `failure_count` ;";