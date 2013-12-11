<?php

$sqls = array();
$functions = array();

$sqls[] = 'ALTER TABLE  `theme_hidden_settings` CHANGE  `name`  `name` CHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;';