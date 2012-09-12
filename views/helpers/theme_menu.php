<?php
class ThemeMenuHelper extends AppHelper {
	
	public function get_single_menu_items() {
		$this->SiteOneLevelMenu = ClassRegistry::init('SiteOneLevelMenu');
		$single_menu_items = $this->SiteOneLevelMenu->find('all');
		
		return $single_menu_items;
	}
	
	public function get_menu_item_data($menu_item) {
		$data = array();
		
		$start_data;
		if (isset($menu_item['SiteOneLevelMenu']['external_model'])) {
			$start_data = $menu_item['SiteOneLevelMenu']['external_model'];
		} else if (isset($menu_item['SiteTwoLevelMenu']['external_model'])) {
			$start_data = $menu_item['SiteTwoLevelMenu']['external_model'];
		} else {
			return false;
		}
		switch ($start_data) {
			case 'PhotoGallery':
				$data['name'] = $menu_item['PhotoGallery']['display_name'];
				break;
			case 'SitePage':
				$data['name'] = $menu_item['SitePage']['title'];
				break;
		}
		
		return $data;
	}
}