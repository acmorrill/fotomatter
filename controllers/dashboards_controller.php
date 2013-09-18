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
		 $this->MajorError = ClassRegistry::init('MajorError');
//		 $this->MajorError->create_fake_major_errors();
		 
		 $this->MajorError->aggragate_errors();
		 
		 $this->HashUtil->set_new_hash('hash_five'); // DREW TODO - remove this
     }
}