<?php

$sqls = array();

$functions = array();

$functions[] = function() {
$theme = ClassRegistry::init('Theme');
$theme->add_theme_display_name('large_image_gray_bar_licky', 'Paper');
return true;
};

