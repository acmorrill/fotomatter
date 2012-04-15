<?php
class PhotoGalleriesController extends AppController {
	var $name = 'PhotoGalleries';
	var $uses = array('PhotoGallery', 'Photo', 'PhotoGalleriesPhoto');
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
					'PhotoGalleriesPhoto' => array(
						'Photo'
					)
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
				'PhotoGalleriesPhoto' => array(
					'Photo'
				)
			)
		));
		
		$photo_ids = Set::extract('/PhotoGalleriesPhoto/Photo/id', $this->data);
		
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
				'PhotoGalleriesPhoto' => array(
					'Photo'
				)
			)
		));
		
		$photo_ids = Set::extract('/PhotoGalleriesPhoto/Photo/id', $curr_gallery);
		
		
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
	
	public function admin_ajax_removephoto_from_gallery($photo_id, $gallery_id) {
		$returnArr = array();
		
		if ($this->PhotoGalleriesPhoto->deleteAll(array(
			'PhotoGalleriesPhoto.photo_id' => $photo_id,
			'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id
		), true, true)) {
			$returnArr['code'] = 1;
			$returnArr['message'] = 'photo removed from gallery';
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to remove photo from gallery via ajax';
			$this->Photo->major_error('failed to remove photo from gallery via ajax', compact('photo_id', 'gallery_id'));
		}
		
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
		
		
		$returnArr['code'] = 1;
		$returnArr['message'] = 'successfully moved photo into gallery';
		$this->return_json($returnArr);
	}
	
	public function admin_edit_gallery_arrange_photos($gallery_id) {
		$this->data = $this->PhotoGallery->find('first', array(
			'conditions' => array('PhotoGallery.id' => $gallery_id),
			'contain' => array(
				'PhotoGalleriesPhoto' => array(
					'Photo'
				)
			)
		));
		
		$this->set(compact('gallery_id'));
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
		
		$this->return_json($returnArr);
	}
	
	public function admin_ajax_set_photo_order_in_gallery($photo_gallery_id, $photo_id, $new_order) {
		$returnArr = array();
		
		$PhotoGalleriesPhoto_to_move = $this->PhotoGalleriesPhoto->find('first', array(
			'conditions' => array(
				'PhotoGalleriesPhoto.photo_gallery_id' => $photo_gallery_id,
				'PhotoGalleriesPhoto.photo_id' => $photo_id
			),
			'contain' => false
		));
		
		if ($this->PhotoGalleriesPhoto->moveto($PhotoGalleriesPhoto_to_move['PhotoGalleriesPhoto']['id'], $new_order)) {
			$returnArr['code'] = 1;
			$returnArr['message'] = '';
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to arrange photo in a gallery';
			$this->Photo->major_error('failed to arrange photo in a gallery', compact('photo_gallery_id', 'photo_id', 'new_order'));
		}
		
		$this->return_json($returnArr);
	}
	 
}