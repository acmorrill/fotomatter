<?php

$sqls = array();

$functions = array();

$sqls[] = "DELETE FROM `site_one_level_menus` WHERE `site_one_level_menus`.`ref_name` = 'home'";
$sqls[] = "SET @i=0; UPDATE `site_one_level_menus` SET weight = @i:=@i+1;";

