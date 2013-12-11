<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE  `photo_prebuild_cache_sizes` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`max_height` INT NOT NULL ,
`max_width` INT NOT NULL ,
`created` DATETIME NULL DEFAULT NULL ,
`modified` DATETIME NULL DEFAULT NULL
) ENGINE = MYISAM ;";
$sqls[] = "INSERT INTO  `photo_prebuild_cache_sizes` (`id` ,`max_height` ,`max_width` ,`created` ,`modified`)
VALUES (NULL ,  '60',  '60',  '2012-04-28 00:13:12',  '2012-04-28 00:13:15');";
$sqls[] = "INSERT INTO  `photo_prebuild_cache_sizes` (`id` ,`max_height` ,`max_width` ,`created` ,`modified`)
VALUES (NULL ,  '110',  '110',  '2012-04-28 00:13:12',  '2012-04-28 00:13:15');";
$sqls[] = "INSERT INTO  `photo_prebuild_cache_sizes` (`id` ,`max_height` ,`max_width` ,`created` ,`modified`)
VALUES (NULL ,  '155',  '155',  '2012-04-28 00:13:12',  '2012-04-28 00:13:15');";

