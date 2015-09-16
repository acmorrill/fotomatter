<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `photos` ADD INDEX (  `file_size` ) COMMENT  '';";
