<?php
class UsersController extends AppController {
    public $name = 'Users';
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
	
    function admin_logout() {
        $this->Session->delete('Permissions');
        $this->redirect($this->Auth->logout());
    }
	
	function admin_index() {
		$this->layout = 'admin/users';	
	}
}
