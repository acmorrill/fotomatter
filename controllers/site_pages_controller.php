<?php
class SitePagesController extends AppController { 
	public $name = 'SitePages'; 
	public $uses = array(
		'SitePage', 'SitePageElement', 'SitePagesSitePageElement'
	);
	public $helpers = array(
		'Page',
		'Photo'
	);
	public $components = array(
		'HashUtil'
	);
			
	
	public function  beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'admin/pages';
		
		$this->HashUtil->set_new_hash('site_pages');
	}

	
	public function admin_index() {
		$site_pages = $this->SitePage->find('all', array(
			'limit' => 100,
			'contain' => false
		));
		
		$this->set(compact('site_pages'));
	} 
	
	public function admin_ajax_add_page_element($page_id, $page_element_id) {
		$this->layout = false;
		
		$config = isset($this->params['form']['config']) ? $this->params['form']['config']: '';
		
		$returnArr = array();
		
		if (($new_element_id = $this->SitePage->add_element_to_page($page_id, $page_element_id, $config)) !== false) {
			$returnArr['code'] = 1;
			
			
			$sitePagesSitePageElements = $this->SitePagesSitePageElement->find('all', array(
				'conditions' => array(
					'SitePagesSitePageElement.id' => $new_element_id
				),
				'order' => array(
					'SitePagesSitePageElement.page_element_order'
				),
				'contain' => array(
					'SitePageElement'
				)
			));
			
			$returnArr['element_html'] = $this->Element('page_elements/list_admin_page_elements', compact(
				'sitePagesSitePageElements'
			));
		} else {
			$this->SitePage->major_error('Failed to add page element to page', compact('page_id', 'page_element_id', 'config'));
			$returnArr['code'] = -1;
			$returnArr['message'] = __('Failed to add page element', true);
		}
		
		$this->return_json($returnArr);
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
	
	public function admin_configure_page($page_id) {
		$this->data = $this->SitePage->find('first', array(
			'conditions' => array(
				'SitePage.id' => $page_id
			),
			'contain' => false
		));
		
		
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
		
		
		$this->set(compact('page_id', 'sitePagesSitePageElements'));
	}
	
	public function admin_ajax_set_page_element_order($site_pages_site_page_element_id, $order) {
		$returnArr = array();
		
		$this->SitePagesSitePageElement->id = $site_pages_site_page_element_id;
		if ($this->SitePagesSitePageElement->moveto($site_pages_site_page_element_id, $order)) {
			$returnArr['code'] = 1;
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to arrange page element in page';
			$this->SitePage->major_error('failed to arrange page element in page', compact('page_id', 'page_element_id', 'order'));
		}
		
		$this->return_json($returnArr);
	}
}
