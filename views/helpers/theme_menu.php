<?php
class ThemeMenuHelper extends AppHelper {
	
	public function get_single_menu_items() {
		$this->SiteOneLevelMenu = ClassRegistry::init('SiteOneLevelMenu');
		$single_menu_items = $this->SiteOneLevelMenu->find('all', array(
			'order' => array(
				'SiteOneLevelMenu.weight'
			)
		));
		
		return $single_menu_items;
	}
	
	public function get_menu_item_data($menu_item, $all_menu_item_data) {
		$data = array();
		
		$start_data;
		if (isset($menu_item['external_model'])) {
			$start_data = $menu_item['external_model'];
			if ($menu_item['is_system'] == 1) {
				$start_data = 'System';
			}
		} else {
			return false;
		}
		
		
		$data['type'] = $start_data;
		switch ($start_data) {
			case 'System': 
				if ($menu_item['ref_name'] === 'home') {
					$data['name'] = __('Home', true);
					$data['url'] = '/';
				} else if ($menu_item['ref_name'] === 'image_galleries') {
					$data['name'] = __('Image Galleries', true); // DREW TODO - change this later to use the gallery name setting
					$data['url'] = '/photo_galleries/choose_gallery';
				} else {
					return false;
				}
				$data['display_type'] = __('System', true);	
				break;
			case 'PhotoGallery':
				$data['name'] = $all_menu_item_data['PhotoGallery']['display_name'];
				$data['display_type'] = __('Gallery', true);
				$data['url'] = '/';
				break;
			case 'SitePage':
				$data['name'] = $all_menu_item_data['SitePage']['title'];
				$data['display_type'] = __('Page', true);
				$data['url'] = '/site_pages/custom_page/'.$menu_item['external_id'];
				break;
		}
		
		
		return $data;
	}
}