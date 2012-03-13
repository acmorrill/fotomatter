<?php
class GalleriesController extends AppController {
	var $name = 'Galleries';
	var $uses = array();

	public function  beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'admin/galleries';
	}

	public function admin_index() {

	}
	 
}