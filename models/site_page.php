<?php

class SitePage extends AppModel {
	public $name = 'SitePage';
	public $hasMany = array(
		'SitePagesSitePageElement' => array(
			'dependent' => true
		)
	);
	public $actsAs = array('Ordered' => array('foreign_key' => false));
	
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
}
