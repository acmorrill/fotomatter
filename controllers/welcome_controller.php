<?php
class WelcomeController extends AppController {
    public $name = 'Welcome';
	public $uses = array();
	public $layout = 'admin/welcome';
	
	
	public function  beforeFilter() {
		if (isset($_COOKIE['welcome_hash'])) {
			$this->Auth->allow('admin_create_password', 'admin_index');
		} else {
			$this->Auth->allow('admin_index');
		}
		
 		parent::beforeFilter();
	}
	
	public function admin_index($account_welcome_email_hash) {
		$this->Welcome = ClassRegistry::init('Welcome');
		
		// check valid hash
		$hash_valid = $this->Welcome->welcome_email_hash_is_valid($account_welcome_email_hash);
		
		if ($hash_valid === false) {
			$this->Welcome->major_error('Welcome index with invalid hash', compact('account_welcome_email_hash'), 'low');
			header('HTTP/1.0 404 Not Found');
			exit();
		}
		
		// check site built
		$site_built = $this->Welcome->site_is_built($account_welcome_email_hash);
		
		if ($site_built !== false) {
			header("Location: /admin/welcome/create_password?wh=".$site_built);
			exit();
		}
		
		
		$this->set(compact('account_welcome_email_hash', 'hash_valid', 'site_built'));
	}
	
	
    public function admin_create_password() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');

		
		// don't do this method if the password has already been set
		if ($this->SiteSetting->getVal('welcome_password_set', 1) == 1) {
			$this->redirect('/admin/welcome/choose_theme');
		}
		
		
		$account_email = $this->SiteSetting->getVal('account_email', false);
		$this->set(compact('account_email'));
		
		if ($account_email === false) {
			$this->SiteSetting->major_error('account_email not set for account');
			$this->Session->setFlash('An error occured during site build. Please contact support.');
		}
		
		
		// validate data if it was submitted
		if (!empty($this->data['password']) && !empty($this->data['confirm_password'])) {
			try {
				$this->Validation->validate('account_valid_password', $this->data, 'password', __('The password must be at least 8 characters long.', true));
				$this->Validation->validate('password_match', $this->data['password'], $this->data['confirm_password'], 'The passwords must match.');
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
				return;
			}
			
			
			$this->User = ClassRegistry::init('User');
			if (($new_user_id = $this->User->create_user($account_email, $this->data['password'], true)) === false) {
				// failed to create user
				$data = $this->data;
				$this->User->major_error('Failed to create the initial user!', compact('account_email', 'data'), 'high');
				$this->Session->setFlash('An error occured during site build. Please contact support.');
				return;
			}
			
			
			// user created so mark welcome_password as having been done
			$this->SiteSetting->setVal('welcome_password_set', 1);
			
			
			// log the new user in
			$this->Auth->login($new_user_id);
			$this->redirect('/admin/welcome/choose_theme');
		}
	}
	
	
	public function admin_choose_theme() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		// don't do this method if the theme has already been chosen
		if ($this->SiteSetting->getVal('welcome_theme_chosen', 1) == 1) {
			$this->redirect('/admin/welcome/your_site');
		}
		
		
		if (!empty($this->data['new_theme_id'])) {
			$this->Theme = ClassRegistry::init('Theme');
			$this->Theme->change_to_theme_by_id($this->data['new_theme_id']);
			
			$this->SiteSetting->setVal('welcome_theme_chosen', 1);
			
			$this->redirect('/admin/welcome/your_site');
		}
	}
	
	
//	public function admin_add_features() { // DREW TODO - maybe add this to the welcome
//		exit('add features'); // DREW TODO - maybe give them a discount if they get a feature right away
//	}
	
	
	public function admin_your_site() {
		// figure out the correct dns domain
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$site_domain = $this->SiteSetting->getVal('site_domain', false);
		$dns_domain = '';
		if ($site_domain === false) {
			$this->SiteSetting->major_error('Site domain not set!');
		} else {
			$dns_domain = $site_domain.".fotomatter.net";
		}
		
		$dns_domain = "fotomatter.dev";
		
		$this->set(compact('dns_domain'));
	}
}
