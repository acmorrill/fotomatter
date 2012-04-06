<?php
class PhotoGalleriesController extends AppController {
	var $name = 'PhotoGalleries';
	var $uses = array('PhotoGallery', 'Photo');
	var $helpers = array('Photo');

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
	
	public function admin_edit_gallery($id) {
		
		if ( empty($this->data) ) {
			$this->data = $this->PhotoGallery->find('first', array(
				'conditions' => array(
					'PhotoGallery.id' => $id
				),
				'contain' => array(
					'Photo'
				)
			));
 		} else {
			// set or unset the id (depending on if its an edit or add)
			$this->data['PhotoGallery']['id'] = $id;
			

			if (!$this->PhotoGallery->save($this->data)) {
				$this->PhotoGallery->major_error('failed to save photo gallery in edit gallery', $this->data);
				$this->Session->setFlash('Failed to save photo gallery');
			} else {
				$this->Session->setFlash('Photo gallery saved');
			}
		}
	}
	
	public function admin_edit_gallery_connect_photos($gallery_id) {
		$this->data = $this->PhotoGallery->find('first', array(
			'conditions' => array('PhotoGallery.id' => $gallery_id),
			'contain' => array(
				'Photo'
			)
		));
		
		$photo_ids = Set::extract('/Photo/id', $this->data);
		
		$not_connected_photos = $this->Photo->find('all', array(
			'conditions' => array(
				'NOT' => array(
					'Photo.id' => $photo_ids
				)
			),
			'contain' => false,
			'limit' => 40 // todo - maybe maybe make this a global var cus its used elsewhere as well
		));
		
		$this->set(compact('not_connected_photos', 'gallery_id'));
	}
	
	// TODO - merge this function with the above somehow
	public function admin_ajax_get_more_photos_to_connect($gallery_id, $last_photo_id) {
		$this->layout = false;
		
		$returnArr = array();
		
		$curr_gallery = $this->PhotoGallery->find('first', array(
			'conditions' => array('PhotoGallery.id' => $gallery_id),
			'contain' => array(
				'Photo'
			)
		));
		
		$photo_ids = Set::extract('/Photo/id', $curr_gallery);
		
		
		$not_connected_photos = $this->Photo->find('all', array(
			'conditions' => array(
				'NOT' => array(
					'Photo.id' => $photo_ids
				),
				'Photo.id >' => $last_photo_id
			),
			'contain' => false,
			'limit' => 40 // todo - maybe maybe make this a global var cus its used elsewhere as well
		));
		
		
		$this->autoRender = false;
 
		/* Set up new view that won't enter the ClassRegistry */
		$view = new View($this, false);
		$view->set(compact('not_connected_photos'));
		$view->viewPath = 'elements';

		/* Grab output into variable without the view actually outputting! */
		$returnArr['count'] = count($not_connected_photos);
		$returnArr['html'] = $view->render('admin/photo/photo_connect_not_in_gallery_photo_cont');
		
		
		echo json_encode($returnArr);
		exit();
	}
	
	public function admin_edit_gallery_arrange_photos($id) {
		$photo_gallery = $this->PhotoGallery->find('first', array(
			'conditions' => array('PhotoGallery.id' => $id),
			'contain' => false,
		));
		
		$this->set(compact('photo_gallery'));
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