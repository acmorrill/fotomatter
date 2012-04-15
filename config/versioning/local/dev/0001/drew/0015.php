<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `photo_galleries_photos` ADD  `photo_order` INT NOT NULL AFTER  `photo_gallery_id`";

// todo - remove index from photo_galleries_photos




