<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD  `megapixels` FLOAT NOT NULL DEFAULT  '0' COMMENT  'The size of the image in megapixels' AFTER  `file_size` ;";
