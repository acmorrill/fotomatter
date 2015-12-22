<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `users` ADD  `superadmin` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `admin` ;";
$sqls[] = "UPDATE  `users` SET  `superadmin` =  '1' WHERE  `users`.`email_address` =  'support@fotomatter.net';";

