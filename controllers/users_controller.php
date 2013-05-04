<?php
class UsersController extends AppController {
    public $name = 'Users';
    public $scaffold;
	
	public function  beforeFilter() {
		parent::beforeFilter();
	}
	
    function admin_login() {}
	
    function admin_logout() {
        $this->Session->delete('Permissions');
        $this->redirect($this->Auth->logout());
    }
	
	function admin_index() {
		$this->layout = 'admin/users';	
	}
}
