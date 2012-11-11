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
	
	public function get_two_level_menu_items() {
		$this->SiteTwoLevelMenu = ClassRegistry::init('SiteTwoLevelMenu');
		$two_level_menu_items = $this->SiteTwoLevelMenu->find('all', array(
			'order' => array(
				'SiteTwoLevelMenu.weight'
			),
			'contain' => array(
				'PhotoGallery',
				'SitePage',
				'SiteTwoLevelMenuContainer' => array(
					'SiteTwoLevelMenuContainerItem' => array(
						'order' => array(
							'SiteTwoLevelMenuContainerItem.weight'
						),
						'PhotoGallery',
						'SitePage',
					),
				),
			),
		));
		
		return $two_level_menu_items;
	}

	/**
	 * this function is used to interpret the single level menu data
	 * 
	 * @param type $menu_item
	 * @param type $all_menu_item_data
	 * @return boolean|string 
	 */
	public function get_menu_item_data($menu_item, $all_menu_item_data) {
		$data = array();
		
		$start_data;
		if (isset($menu_item['external_model'])) {
			$start_data = $menu_item['external_model'];
			if (isset($menu_item['is_system']) && $menu_item['is_system'] == 1) {
				$start_data = 'System';
			}
		} else {
			return false;
		}
		
		
		$data['type'] = $start_data;
		$data['id'] = $menu_item['id'];
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
			case 'SiteTwoLevelMenuContainer':
				$data['name'] = $all_menu_item_data['SiteTwoLevelMenuContainer']['display_name'];
				$data['display_type'] = __('Menu Container', true);
				$data['url'] = '';
				$data['submenu_items'] = array();
				foreach ($all_menu_item_data['SiteTwoLevelMenuContainer']['SiteTwoLevelMenuContainerItem'] as $sub_menu_item) {
					$data['submenu_items'][] = $this->get_menu_item_data($sub_menu_item, $sub_menu_item);
				}
				
				break;
		}
		
		
		return $data;
	}
}