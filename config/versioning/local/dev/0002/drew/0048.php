<?php

$sqls = array();

$functions = array();

$sqls[] = "UPDATE `cron_jobs` SET `strtotime` = '+2 weeks' WHERE `cron_jobs`.`method_name` ='send_cron_working_email';";





