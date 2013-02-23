<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `themes` ADD  `disabled` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `id`";
$sqls[] = "UPDATE  `themes` SET  `disabled` =  '1' WHERE  `themes`.`ref_name` ='adam'";
$sqls[] = "UPDATE  `themes` SET  `disabled` =  '1' WHERE  `themes`.`ref_name` ='amazing'";
$sqls[] = "UPDATE  `themes` SET  `disabled` =  '1' WHERE  `themes`.`ref_name` ='difandrew'";
$sqls[] = "UPDATE  `themes` SET  `disabled` =  '1' WHERE  `themes`.`ref_name` ='difandrew2'";
$sqls[] = "ALTER TABLE  `themes` ADD  `display_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `id`";
$sqls[] = "UPDATE  `themes` SET  `display_name` =  'Dynamic Gray/Blue Lines' WHERE  `themes`.`ref_name` ='andrewmorrill'";
$sqls[] = "UPDATE  `themes` SET  `display_name` =  'Simple Light Grey Textured' WHERE  `themes`.`ref_name` ='simple_lightgrey_textured'";
$sqls[] = "UPDATE  `themes` SET  `display_name` =  'Minimalist White Slider' WHERE  `themes`.`ref_name` ='white_slider';";
