<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD  `forcache_pixel_width` INT NULL DEFAULT NULL AFTER  `pixel_height`";
$sqls[] = "ALTER TABLE  `photos` ADD  `forcache_pixel_height` INT NULL DEFAULT NULL AFTER  `forcache_pixel_width`";
$sqls[] = "ALTER TABLE  `photo_caches` ADD  `pixel_height` INT NULL DEFAULT NULL AFTER  `max_height`";
$sqls[] = "ALTER TABLE  `photo_caches` ADD  `pixel_width` INT NULL DEFAULT NULL AFTER  `pixel_height`";
$sqls[] = "ALTER TABLE  `photo_caches` ADD  `tag_attributes` CHAR( 100 ) NULL DEFAULT NULL AFTER  `pixel_width`";
$sqls[] = "ALTER TABLE  `photos` CHANGE  `enabled`  `enabled` TINYINT( 1 ) NOT NULL DEFAULT  '1'";




