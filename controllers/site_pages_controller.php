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
	
	public function admin_ajax_set_page_order($page_id, $new_order) {
		$returnArr = array();
		
		if ($this->SitePage->moveto($page_id, $new_order)) {
			$returnArr['code'] = 1;
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to change page order';
		}
		
		$this->return_json($returnArr);
	}
	
	public function admin_edit_page($id) {
		if ( empty($this->data) ) {
			$this->data = $this->SitePage->find('first', array(
				'conditions' => array(
					'SitePage.id' => $id
				),
				'contain' => false
			));
 		} else {
			// set or unset the id (depending on if its an edit or add)
			$this->data['SitePage']['id'] = $id;
			

			if (!$this->SitePage->save($this->data)) {
				$this->SitePage->major_error('failed to save page in edit page', $this->data);
				$this->Session->setFlash('Failed to save page');
			} else {
				$this->Session->setFlash('Page saved');
			}
		}
		
	}
	
	public function admin_configure_page($id) {
		$this->data = $this->SitePage->find('first', array(
			'conditions' => array(
				'SitePage.id' => $id
			),
			'contain' => false
		));
	}
}
