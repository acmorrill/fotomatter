<?php

$sqls = array();
$functions = array();

$sqls[] = 'ALTER TABLE  `photo_caches` ADD  `unsharp_amount` FLOAT NULL DEFAULT NULL AFTER  `tag_attributes`';