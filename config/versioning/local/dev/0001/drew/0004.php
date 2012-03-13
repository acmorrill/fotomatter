<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD  `cdn-filename` CHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `id`";