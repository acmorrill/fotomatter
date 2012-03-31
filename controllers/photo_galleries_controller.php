<?php
class PhotoGalleriesController extends AppController {
	var $name = 'PhotoGalleries';
	var $uses = array('PhotoGallery');

	public function  beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'admin/galleries';
		
		//$this->Auth->allow('test');
	}

	public function admin_index() {
		$galleries = $this->PhotoGallery->find('all', array(
			'limit' => 100
		));
		
		$this->set(compact('galleries'));
	}
	
	public function admin_ajax_set_photogallery_order($photoGalleryId, $newOrder) {
		$returnArr = array();
		
		if ($this->PhotoGallery->moveto($photoGalleryId, $newOrder)) {
			$returnArr['code'] = 1;
			$returnArr['message'] = 'gallery order changed successfully';
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to change gallery order';
		}
		
		echo json_encode($returnArr);
		exit();
	}
	 
}