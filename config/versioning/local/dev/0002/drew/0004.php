<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD  `date_taken` DATETIME NULL DEFAULT NULL AFTER  `id`";

$sqls[] = "ALTER TABLE  `photo_galleries` ADD  `smart_settings` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `description`";

$sqls[] = "ALTER TABLE  `photos` CHANGE  `date_taken`  `date_taken` DATE NULL DEFAULT NULL";

$sqls[] = "ALTER TABLE  `photos_tags` ADD UNIQUE (
`tag_id` ,
`photo_id`
);";

$sqls[] = "ALTER TABLE  `photos_tags` ADD UNIQUE (
`photo_id` ,
`tag_id`
);";

