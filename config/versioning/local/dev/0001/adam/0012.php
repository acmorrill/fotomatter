<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `photo_galleries` CHANGE  `type`  `type` CHAR( 8 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'standard'";
$sqls[] = "ALTER TABLE  `photo_caches` CHANGE  `status`  `status` CHAR( 10 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  'queued'";
