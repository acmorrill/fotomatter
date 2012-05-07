<?php
class PageHelper extends AppHelper {
	
	public function get_avail_page_elements() {
		$this->SitePageElement = ClassRegistry::init('SitePageElement');
		
		return $this->SitePageElement->find('all', array(
			'contain' => false
		));
	}
	
	public function get_site_pages_site_page_elements($page_id) {
		$this->SitePagesSitePageElement = ClassRegistry::init('SitePagesSitePageElement');
		
		$sitePagesSitePageElements = $this->SitePagesSitePageElement->find('all', array(
			'conditions' => array(
				'SitePagesSitePageElement.site_page_id' => $page_id
			),
			'order' => array(
				'SitePagesSitePageElement.page_element_order'
			),
			'contain' => array(
				'SitePageElement'
			)
		));
		
		return $sitePagesSitePageElements;
	}
	
}