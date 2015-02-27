<?php

$sqls = array();

$functions = array();

$sqls[] = "DELETE FROM cron_jobs WHERE method_name = 'send_cron_working_email';";

