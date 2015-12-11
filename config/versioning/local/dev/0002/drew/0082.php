<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photo_caches` DROP INDEX `photo_id`;";
$sqls[] = "ALTER TABLE  `photo_caches` ADD UNIQUE INDEX `photo_id` (`photo_id`, `max_width`, `max_height`, `crop`, `unsharp_amount`);";

