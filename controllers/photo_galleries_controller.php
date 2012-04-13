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
			'limit' => 30 // todo - maybe maybe make this a global var cus its used elsewhere as well
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
			'limit' => 30 // todo - maybe maybe make this a global var cus its used elsewhere as well
		));
		
		
		/* Grab output into variable without the view actually outputting! */
		$returnArr['count'] = count($not_connected_photos);
		$returnArr['html'] = $this->element('admin/photo/photo_connect_not_in_gallery_photo_cont', array(
			'not_connected_photos' => $not_connected_photos
		));
		
		
		$this->return_json($returnArr);
	}
	
	public function admin_ajax_movephoto_into_gallery($photo_id, $gallery_id) {
		$returnArr = array();
		
		$the_photo = $this->Photo->find('first', array(
			'conditions' => array(
				'Photo.id' => $photo_id
			),
			'contain' => false
		));

		
		if (!$the_photo) {
			$this->Photo->major_error('the photo you tried to move did not work');
			$returnArr['code'] = -1;
			$returnArr['message'] = 'the photo you tried to move did not work';
			$this->return_json($returnArr);
		}
		
		if (!$this->PhotoGallery->add_photo_to_gallery($photo_id, $gallery_id)) {
			$this->Photo->major_error('failed to add a photo to a gallery in admin_ajax_movephoto_into_gallery');
		}
		
//		$returnArr['test'] = array(
//			'connected_photos' => array($the_photo['Photo'])
//		);
//		$this->return_json($returnArr);
		
		$html = $this->element('admin/photo/photo_connect_in_gallery_photo_cont', array(
			'connected_photos' => array($the_photo['Photo'])
		));
		
		if ($html === false) {
			$this->Photo->major_error('failed to get element in movephoto');
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to get element in movephoto';
			$this->return_json($returnArr);
		}
		
		$returnArr['code'] = 1;
		$returnArr['message'] = 'successfully moved photo into gallery';
		$returnArr['html'] = $html;
		$this->return_json($returnArr);
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