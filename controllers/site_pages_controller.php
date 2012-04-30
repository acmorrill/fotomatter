<?php
class SitePagesController extends AppController { 
	public $name = 'SitePages'; 
	public $uses = array(
		'SitePage'
	);
	
	public function  beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'admin/pages';
	}

	
	public function admin_index() {
		$site_pages = $this->SitePage->find('all', array(
			'limit' => 100,
			'contain' => false
		));
		
		$this->set(compact('site_pages'));
	} 
	
	public function admin_edit_page($page_id) {
		
	}
}
