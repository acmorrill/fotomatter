<?php

$sqls = array();

$functions = array();

$sqls[] = 'ALTER TABLE  `users` ADD  `facebook` char(128) NULL DEFAULT NULL AFTER `email_address` ;';

