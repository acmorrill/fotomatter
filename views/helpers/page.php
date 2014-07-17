<?php
class PageHelper extends AppHelper {

	function __call($method_name, $args) {
		$this->SitePage = ClassRegistry::init('SitePage');
		
		return call_user_func_array(array($this->SitePage, $method_name), $args);
	}
	
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
	
	public function get_all_pages() {
		$this->SitePage = ClassRegistry::init('SitePage');
		
		$all_pages = $this->SitePage->find('all', array(
			'contain' => false
		));
		
		return $all_pages;
	}
	
	public function get_site_page_by_id($site_page_id) {
		$this->SitePage = ClassRegistry::init("SitePage");
		return $this->SitePage->find('first', array(
			'conditions'=>array(
				'SitePage.id'=>$site_page_id
			),
			'contain'=>false
		));
	}
	
}