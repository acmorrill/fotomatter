<?php

$sqls = array();

$functions = array();

$sqls[] = "UPDATE  `themes` SET  `disabled` =  '1' WHERE  `themes`.`ref_name` ='grezzo';";
$sqls[] = "UPDATE  `themes` SET  `disabled` =  '1' WHERE  `themes`.`ref_name` ='test_bg_theme';";
$sqls[] = "UPDATE  `themes` SET  `disabled` =  '1' WHERE  `themes`.`ref_name` ='large_image_gray_bar_licky';";
$sqls[] = "UPDATE  `themes` SET  `disabled` =  '1' WHERE  `themes`.`ref_name` ='f32_dynamic_background';";

$sqls[] = "UPDATE  `themes` SET  `display_name` =  'Opacity' WHERE  `themes`.`ref_name` ='andrewmorrill';";
$sqls[] = "UPDATE  `themes` SET  `display_name` =  'Viewfinder' WHERE  `themes`.`ref_name` ='simple_lightgrey_textured';";
$sqls[] = "UPDATE  `themes` SET  `display_name` =  'White Balance' WHERE  `themes`.`ref_name` ='white_slider';";
$sqls[] = "UPDATE  `themes` SET  `display_name` =  'Dark Slide' WHERE  `themes`.`ref_name` ='white_slider_subone';";
$sqls[] = "UPDATE  `themes` SET  `display_name` =  'Parallax' WHERE  `themes`.`ref_name` ='white_angular';";