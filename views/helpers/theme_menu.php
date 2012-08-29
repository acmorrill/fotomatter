<?php
class ThemeMenuHelper extends AppHelper {
	
	public function get_single_menu_items() {
		$this->SiteOneLevelMenu = ClassRegistry::init('SiteOneLevelMenu');
		$single_menu_items = $this->SiteOneLevelMenu->find('all');
		
		return $single_menu_items;
	}
}