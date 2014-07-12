<?php

$sqls = array();

$functions = array();

$functions[] = function() {
    $theme = ClassRegistry::init('Theme');
    $theme->add_theme_display_name('f32_dynamic_background', 'f/32');
    return true;
};


