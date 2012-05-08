<?php
class DashboardsController extends AppController {
     var $name = 'Dashboards';
     var $uses = array();
	 var $components = array('HashUtil');

     public function  beforeFilter() {
          parent::beforeFilter();

          $this->layout = 'admin/dashboard';
     }

     public function admin_index() {
		 $this->HashUtil->set_new_hash('hash_five');
     }
}