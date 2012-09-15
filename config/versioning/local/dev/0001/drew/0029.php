<?php

$sqls = array();
$functions = array();

$sqls[] = "ALTER TABLE  `site_one_level_menus` ADD  `is_system` TINYINT NOT NULL DEFAULT  '0' AFTER  `id`";
$sqls[] = "ALTER TABLE  `site_one_level_menus` ADD  `ref_name` CHAR( 30 ) NOT NULL DEFAULT  'custom' AFTER  `is_system`";

$functions[] = function() {
	$SiteOneLevelMenu = ClassRegistry::init('SiteOneLevelMenu');
	
	
	
	// add home menu item
	$home_menu_item['SiteOneLevelMenu']['is_system'] = 1;
	$home_menu_item['SiteOneLevelMenu']['ref_name'] = 'home';
	$home_menu_item['SiteOneLevelMenu']['external_id'] = 0;
	$home_menu_item['SiteOneLevelMenu']['external_model'] = 'SitePage';
	$SiteOneLevelMenu->create();
	if (!$SiteOneLevelMenu->save($home_menu_item)) {
		return false;
	}
	$total_menu_items = $SiteOneLevelMenu->find('count', array(
		'contain' => false
	));
	if ($total_menu_items > 1 && !$SiteOneLevelMenu->moveto($SiteOneLevelMenu->id, 1)) {
		return false;
	}
	
	
	// add the gallery menu item
	$image_galleries_item['SiteOneLevelMenu']['is_system'] = 1;
	$image_galleries_item['SiteOneLevelMenu']['ref_name'] = 'image_galleries';
	$image_galleries_item['SiteOneLevelMenu']['external_id'] = 0;
	$image_galleries_item['SiteOneLevelMenu']['external_model'] = 'SitePage';
	$SiteOneLevelMenu->create();
	if (!$SiteOneLevelMenu->save($image_galleries_item)) {
		return false;
	}
	$total_menu_items = $SiteOneLevelMenu->find('count', array(
		'contain' => false
	));
	if ($total_menu_items > 2 && !$SiteOneLevelMenu->moveto($SiteOneLevelMenu->id, 2)) {
		return false;
	}
	
	return true;
};








 
 

