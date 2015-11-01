<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD  `file_size` FLOAT NOT NULL DEFAULT  '0' COMMENT  'The uploaded file size in megabytes' AFTER  `photo_format_id` ;";
