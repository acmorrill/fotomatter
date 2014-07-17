<?php
class SitePagesController extends AppController { 
	public $name = 'SitePages'; 
	public $uses = array(
		'SiteSetting', 
		'SitePage', 
		'SitePageElement', 
		'SitePagesSitePageElement'
	);
	public $helpers = array(
		'Page',
		'Photo',
		'Cache'
	);
	//public $cacheAction = "1 hour";
			
	
	public function  beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'admin/pages';
		
		$this->Auth->allow('landing_page', 'custom_page', 'htaccess', 'contact_us', 'send_contact_us_email');
		
		/////////////////////////////////////////////
		// limit pages
		if (in_array($this->action, array(
			'admin_index',
		))) {
			$this->FeatureLimiter->limit_view($this, 'page_builder', 'page_builder'); // $controller, $feature_ref_name, $element_path in /elements/admin/limit_views
		} else if(!in_array($this->action, array( // this allowed no matter what
			'ping',
			'landing_page',
		))) { // everything else allowed only if have page feature
			$this->FeatureLimiter->limit_function($this, 'page_builder'); // $controller, $feature_ref_name
		} 
	}
	
	public function ping() {
		if (isset($_GET['callback'])) {
			$callback = $_GET['callback'];
			echo $callback."(".json_encode(true).");"; 
		}
		exit();
	}
	
	public function landing_page() {
		$this->setup_front_end_view_cache($this);
		
		$this->ThemeRenderer->render($this);
	}
	
	public function custom_page($site_page_id) {
		$this->setup_front_end_view_cache($this);
		
		$site_page = $this->SitePage->find('first', array(
			'conditions' => array(
				'SitePage.id' => $site_page_id
			),
			'contain' => array(
				'SitePagesSitePageElement' => array(
					'SitePageElement'
				)
			)
		));
	
		$this->set(compact('site_page_id', 'site_page'));
		$this->ThemeRenderer->render($this);
	}
	
	public function contact_us($site_page_id) {
		$this->setup_front_end_view_cache($this);
		
		$site_page = $this->SitePage->find('first', array(
			'conditions' => array(
				'SitePage.id' => $site_page_id
			),
			'contain' => false,
		));
	
		$this->set(compact('site_page_id', 'site_page'));
		$this->ThemeRenderer->render($this);
	}
	public function send_contact_us_email($site_page_id) {
		//check input
		try {
			$this->Validation->validate('not_empty', $this->data['SitePage'], 'contact_us_name', __('You must provide your name', true));
			$this->Validation->validate('not_empty', $this->data['SitePage'], 'contact_us_email', __('You must provide an email address', true));
			$this->Validation->validate('valid_email', $this->data['SitePage'], 'contact_us_email', __('Email address invalid', true));
			$this->Validation->validate('not_empty', $this->data['SitePage'], 'contact_us_content', __('You must provide a message', true));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'admin/flashMessage/error');
			$this->redirect("/site_pages/contact_us/$site_page_id");
			exit();
		}
		
		
		////////////////////////////////////////////
		// send the actual email
		if ($this->FotomatterEmail->send_end_user_contact_us_email($this, $this->data['SitePage']) === false) {
			$this->Session->setFlash("Failed to send email", 'admin/flashMessage/error');
			$this->redirect("/site_pages/contact_us/$site_page_id");
			exit();
		}
		
		
		$this->Session->setFlash("Email sent", 'admin/flashMessage/success');
		$this->redirect("/site_pages/contact_us/$site_page_id");
	}

	public function admin_index() {
		$this->HashUtil->set_new_hash('site_pages');

		$this->layout = 'admin/sidebar_less';
		
		$site_pages = $this->SitePage->find('all', array(
			'limit' => 100,
			'contain' => false
		));
		
		$this->set(compact('site_pages'));
	} 

	public function admin_save_page_elements() {
		$returnArr = array();
		$returnArr['code'] = 1;
		
		$page_data = $this->params['form']['element_data'];
		
		$parsed_page_data = array();
		foreach ($page_data as $site_pages_site_page_element_id => $element_data) {
			$curr_output = array();
			parse_str($element_data, $curr_output);
			$parsed_page_data[$site_pages_site_page_element_id] = $curr_output;
		}
		
		foreach ($parsed_page_data as $site_pages_site_page_element_id => $parsed_element_data) {
			$SitePagesSitePageElement_data['SitePagesSitePageElement']['id'] = $site_pages_site_page_element_id;
			$SitePagesSitePageElement_data['SitePagesSitePageElement']['config'] = $parsed_element_data;
			
			//$this->log($SitePagesSitePageElement_data, 'SitePagesSitePageElement_data');
			
			if (!$this->SitePagesSitePageElement->save($SitePagesSitePageElement_data)) {
				$returnArr['code'] = -1;
				$returnArr['message'] = 'failed to save SitePagesSitePageElement config data';
				$this->SitePagesSitePageElement->major_error('failed to save SitePagesSitePageElement config data', compact('parsed_page_data'));
				break;
			} 
		}
		
		
		$this->return_json($returnArr);
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
	
	public function admin_add_custom_page() {
		$new_page = array();
		$new_page['SitePage']['title'] = 'Page Title';
		$new_page['SitePage']['type'] = 'custom';
		
		$this->SitePage->create();
		if (!$this->SitePage->save($new_page)) {
			$this->Session->setFlash('Failed to create new page');
			$this->SitePage->major_error('Failed to create new custom page in (add_custom_page) in site_pages_controller.php', compact('new_page'));
			$this->redirect('/admin/site_pages');
		} else {
			//$this->Session->setFlash('New page created');
			$this->redirect('/admin/site_pages/edit_page/'.$this->SitePage->id);
		}
	}
	
	public function admin_add_external_page() {
		$new_page = array();
		$new_page['SitePage']['title'] = 'External Page';
		$new_page['SitePage']['type'] = 'external';
		$new_page['SitePage']['external_link'] = 'http://www.externalsite.com';
		
		$this->SitePage->create();
		if (!$this->SitePage->save($new_page)) {
			$this->Session->setFlash('Failed to create new external page', 'admin/flashMessage/error');
			$this->SitePage->major_error('Failed to create new external page in (admin_add_external_page) in site_pages_controller.php', compact('new_page'));
			$this->redirect('/admin/site_pages');
		} else {
			//$this->Session->setFlash('New page created');
			$this->redirect('/admin/site_pages/edit_page/'.$this->SitePage->id);
		}
	}
	
	public function admin_add_contact_us_page() {
		// check to see if there is a contact us page already
		if ($this->SitePage->count_pages_of_type('contact_us') > 0) {
			$this->Session->setFlash('Failed to create new contact us page.', 'admin/flashMessage/error');
			$this->SitePage->major_error('tried to create duplicate contact us page in site_pages_controller.php', array());
			$this->redirect('/admin/site_pages');
		}
		
		$new_page = array();
		$new_page['SitePage']['title'] = 'Contact';
		$new_page['SitePage']['type'] = 'contact_us';
		$first_name = $this->SiteSetting->getVal('first_name', '');
		$last_name = $this->SiteSetting->getVal('last_name', '');
		$new_page['SitePage']['contact_header'] = sprintf(__("Contact %s %s", true), $first_name, $last_name);
		$new_page['SitePage']['contact_message'] = __("Please fill out the form below to contact me.", true);
		
		$this->SitePage->create();
		if (!$this->SitePage->save($new_page)) {
			$this->Session->setFlash('Failed to create new contact us page', 'admin/flashMessage/error');
			$this->SitePage->major_error('Failed to create contact us page in (admin_contact_us_page) in site_pages_controller.php', compact('new_page'));
			$this->redirect('/admin/site_pages');
		} else {
			$this->redirect('/admin/site_pages/edit_page/'.$this->SitePage->id);
		}
	}
	
	public function admin_edit_page($id) {
		if ( !empty($this->data) ) {
			// set or unset the id (depending on if its an edit or add)
			$this->data['SitePage']['id'] = $id;
			

			if (!$this->SitePage->save($this->data)) {
				$this->SitePage->major_error('failed to save page in edit page', $this->data);
				$this->Session->setFlash('Failed to save page', 'admin/flashMessage/error');
			} else {
				$this->Session->setFlash('Page saved', 'admin/flashMessage/success');
			}
 		}
		
		if (isset($id)) {
			$this->data = $this->SitePage->find('first', array(
				'conditions' => array(
					'SitePage.id' => $id
				),
				'contain' => false
			));
		}
	}
	
	public function admin_configure_page($page_id) {
		$this->layout = 'admin/pages_configure';
		
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
		
		
		$this->set(compact('page_id', 'sitePagesSitePageElements', 'contact_us_count'));
	}
	
	public function admin_ajax_set_page_element_order($site_pages_site_page_element_id, $order) {
		$returnArr = array();
		
		$this->SitePagesSitePageElement->id = $site_pages_site_page_element_id;
		if ($this->SitePagesSitePageElement->moveto($site_pages_site_page_element_id, $order)) {
			$returnArr['code'] = 1;
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to arrange page element in page';
			$this->SitePage->major_error('failed to arrange page element in page', compact('site_pages_site_page_element_id', 'order'));
		}
		
		$this->return_json($returnArr);
	}
	
	public function admin_delete_page($page_id = null) {
		if ($page_id == null) {
			 $this->redirect('/admin/site_pages');
		}
		
		
		if ($this->SitePage->delete($page_id)) {
			$this->Session->setFlash(__('Page deleted successfully.', true), 'admin/flashMessage/success');
		} else {
			$this->Session->setFlash(__('Failed to delete page.', true), 'admin/flashMessage/error');
			$this->Photo->major_error('Failed to delete page in admin_delete_page', compact('page_id'));
		}
		
		
		$this->redirect('/admin/site_pages');
	}
	
	public function admin_ajax_remove_page_element($site_pages_site_page_element_id) {
		$returnArr = array();
		
		if ($this->SitePagesSitePageElement->delete($site_pages_site_page_element_id)) {
			$returnArr['code'] = 1;
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to delete page element in page';
			$this->SitePage->major_error('failed to delete page element in page', compact('site_pages_site_page_element_id'));
		}
		
		$this->return_json($returnArr);
	}
	
	
}
