<?php
class PhotoGalleriesController extends AppController {
	var $name = 'PhotoGalleries';
	var $uses = array();

	public function  beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'admin/galleries';
	}

	public function admin_index() {
		
	}
	 
}