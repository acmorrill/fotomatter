<?php
class UsersController extends AppController {
    public $name = 'Users';
	public $uses = array('User');
    public $scaffold;
	
	public function  beforeFilter() {
		parent::beforeFilter();
	}
	
    function admin_login() {
		// before displaying the login check to see if a user has ever been setup - if not - then go to the welcome page
//		$this->SiteSetting = ClassRegistry::init('SiteSetting');
//		if ($this->SiteSetting->getVal('welcome_password_set', 1) == 0) { // START HERE TOMORROW - this causes a redirect loop
//			$this->redirect('/admin/welcome/create_password');
//		}
	}
	
	function admin_change_password() {
		try {
			$this->Validation->validate('account_valid_password', $this->params['form'], 'password', __('You must have a password that is longer than eight characters.', true));
			$this->Validation->validate('password_match', $this->params['form']['password'], $this->params['form']['password_confirm'], __('Your passwords do not match.', true));
			
		} catch(Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'admin/flashMessage/error');
			$this->redirect('/admin/accounts/account_details');
			exit();
		}
		$current_user = $this->Auth->user();
		$this->User->create_user($current_user['User']['email_address'], $this->params['form']['password'], true);
		$this->Session->setFlash(__('Your password has been changed successfully.', true), 'admin/flashMessage/success');
		$this->redirect('/admin/accounts/account_details');
		exit();
	}
	
    function admin_logout() {
        $this->Session->delete('Permissions');
        $this->redirect($this->Auth->logout());
    }
	
	function admin_index() {
		$this->layout = 'admin/users';	
	}
}
