<?php
class DashboardsController extends AppController {
     var $name = 'Dashboards';
     var $uses = array();

     public function  beforeFilter() {
          parent::beforeFilter();

          $this->layout = 'admin/dashboard';
     }

     public function admin_index() {
		 
     }
}