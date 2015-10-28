<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE `photo_galleries` ADD INDEX `weight` (`weight`)";
$sqls[] = "ALTER TABLE `photo_galleries_photos` ADD INDEX `photo_order` (`photo_gallery_id`, `photo_order`)";
