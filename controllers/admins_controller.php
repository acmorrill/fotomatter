<?php
class AdminsController extends AppController {
     var $name = 'Admins';
     var $uses = array();
     
	public $helpers = array('Menu');

     public function  beforeFilter() {
          parent::beforeFilter();

          $this->layout = 'admin';

          $adminLeftMenu['test'][] = array('path' => '/admins/index', 'name' => 'Index');
          $adminLeftMenu['test'][] = array('path' => '/admins/photos', 'name' => 'Photos');

          $this->set('adminLeftMenu', $adminLeftMenu);
     }

     public function admin_index() {
          $this->redirect('/photos/list_oldphotos/');
     }
}
