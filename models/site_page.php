<?php

class SitePage extends AppModel {
	public $name = 'SitePage';
	public $hasMany = array(
		'SitePagesSitePageElement' => array(
			'dependent' => true
		),
		// these are both rightly done in the beforeDelete
//		'SiteTwoLevelMenu' => array( 
//			'dependent' => true
//		),
//		'SiteTwoLevelMenuContainerItem' => array(
//			'dependent' => true
//		),
	);
	public $actsAs = array('Ordered' => array('foreign_key' => false));
	
	public function beforeDelete($cascade = true) {
		parent::beforeDelete($cascade);
		
		$site_page_id = $this->id;
		
		$delete_one_level_menu_query = "DELETE FROM site_one_level_menus WHERE external_id = :site_page_id AND external_model = 'SitePage'";
		//die($delete_one_level_menu_query);
		if (!$this->query($delete_one_level_menu_query, array('site_page_id' => $site_page_id))) {
			$this->major_error('Failed to delete one level menu connection on site page delete', compact('site_page_id'));
			return false;
		}
		
		$delete_two_level_menu_query = "DELETE FROM site_two_level_menus WHERE external_id = :site_page_id AND external_model = 'SitePage'";
		if (!$this->query($delete_two_level_menu_query, array('site_page_id' => $site_page_id))) {
			$this->major_error('Failed to delete two level menu connection on site page delete', compact('site_page_id'));
			return false;
		}
		
		$delete_two_level_menu_container_item_query = "DELETE FROM site_two_level_menu_container_items WHERE external_id = :site_page_id AND external_model = 'SitePage'";
		if (!$this->query($delete_two_level_menu_container_item_query, array('site_page_id' => $site_page_id))) {
			$this->major_error('Failed to delete two level menu container item connection on site page delete', compact('site_page_id'));
			return false;
		}
		
		return true;
	}
	
	public function get_total_pages() {
		return $this->find('count');
	}
	
	public function add_element_to_page($site_page_id, $site_page_element_id, $config) {
		$data['SitePagesSitePageElement']['site_page_id'] = $site_page_id;
		$data['SitePagesSitePageElement']['site_page_element_id'] = $site_page_element_id;
		$data['SitePagesSitePageElement']['config'] = $config;
		
		
		$this->SitePagesSitePageElement->create();
		if ($this->SitePagesSitePageElement->save($data)) {
			return $this->SitePagesSitePageElement->id;
		} else {
			return false;
		}
	}
	
	public function count_pages_of_type($type) {
		return $this->find('count', array(
			'conditions' => array(
				'SitePage.type' => $type,
			),
			'contain' => false
		));
	}
}
