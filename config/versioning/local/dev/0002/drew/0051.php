<?php

$sqls = array();

$functions = array();

$sqls[] = "ALTER TABLE  `site_pages` ADD  `contact_message` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER  `type` ;";
$sqls[] = "ALTER TABLE  `site_pages` ADD  `contact_header` CHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER  `type` ;";





