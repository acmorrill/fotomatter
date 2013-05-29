<?php
$functions = array();
$sqls = array();

$sqls[] = 'ALTER TABLE  `countries` ADD  `order` INT NOT NULL AFTER  `status`';
$sqls[] = 'UPDATE `countries` SET  `order` =  \'100\' WHERE  `id` =223;';
$sqls[] = 'UPDATE  `global`.`countries` SET  `order` =  \'99\' WHERE  `id` =38;';
$sqls[] = 'UPDATE  `global`.`countries` SET  `order` =  \'98\' WHERE  `countries`.`id` =138;';
$sqls[] = 'UPDATE  `global`.`countries` SET  `order` =  \'97\' WHERE  `countries`.`id` =222;';
$sqls[] = 'UPDATE  `global`.`countries` SET  `order` =  \'96\' WHERE  `countries`.`id` =13;';