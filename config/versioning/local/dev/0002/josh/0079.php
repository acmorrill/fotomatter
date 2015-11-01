<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photo_caches` CHANGE  `max_width`  `max_width` INT( 11 ) NOT NULL ;";
$sqls[] = "ALTER TABLE  `photo_caches` CHANGE  `max_height`  `max_height` INT( 11 ) NOT NULL ;";
$sqls[] = "ALTER TABLE  `photo_caches` CHANGE  `unsharp_amount`  `unsharp_amount` FLOAT NULL DEFAULT  '.4';";
$sqls[] = "ALTER TABLE  `photo_caches` DROP INDEX `photo_id`;";
$sqls[] = "ALTER TABLE  `photo_caches` ADD UNIQUE INDEX `photo_id` (`photo_id`, `max_width`, `max_height`, `crop`, `unsharp_amount`);";
