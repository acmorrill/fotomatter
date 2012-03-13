<?php
class PhotoGroupsController extends AppController {
     var $name = 'PhotoGroups';
     var $uses = array();

     public function  beforeFilter() {
          parent::beforeFilter();

          $this->layout = 'admin/photo_groups';
     }

     public function admin_index() {
		 
     }
}