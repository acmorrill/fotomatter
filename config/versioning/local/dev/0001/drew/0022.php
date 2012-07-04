<?php

$sqls = array();
$functions = array();

$sqls[] = "CREATE TABLE  `site_page_elements` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` CHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`configuration` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`order` INT NOT NULL
) ENGINE = MYISAM ;";

$sqls[] = "ALTER TABLE  `site_page_elements` ADD  `page_id` INT NOT NULL AFTER  `id`";

$sqls[] = "ALTER TABLE  `site_page_elements` DROP  `title`";

$sqls[] = "ALTER TABLE  `site_page_elements` DROP  `configuration`";

$sqls[] = "ALTER TABLE  `site_page_elements` DROP  `page_id`";

$sqls[] = "ALTER TABLE  `site_page_elements` ADD  `ref_name` CHAR( 50 ) NOT NULL AFTER  `id`";

$sqls[] = "ALTER TABLE  `site_page_elements` ADD  `version` INT NOT NULL DEFAULT  '1' AFTER  `order`";

$sqls[] = "INSERT INTO  `site_page_elements` (`id` ,`ref_name` ,`order` ,`version`)
VALUES (NULL ,  'para_header_image',  '1',  '1');";

$sqls[] = "INSERT INTO  `site_page_elements` (`id` ,`ref_name` ,`order` ,`version`)
VALUES (NULL ,  'image',  '2',  '1');";

$sqls[] = "CREATE TABLE  `site_pages_site_page_elements` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`site_page_id` INT NOT NULL ,
`site_page_element_id` INT NOT NULL ,
`created` DATETIME NULL DEFAULT NULL ,
`modified` DATETIME NULL DEFAULT NULL
) ENGINE = MYISAM ;";

$sqls[] = "ALTER TABLE  `site_pages_site_page_elements` ADD  `config` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `site_page_element_id`";

$sqls[] = "ALTER TABLE  `site_pages_site_page_elements` ADD  `page_element_order` INT NOT NULL AFTER  `config`";





