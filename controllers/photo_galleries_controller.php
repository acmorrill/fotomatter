<?php
class PhotoGalleriesController extends AppController {
	public $name = 'PhotoGalleries';
	public $uses = array('PhotoGallery', 'Photo', 'PhotoGalleriesPhoto', 'PhotoFormat');
	public $helpers = array('Photo', 'Gallery', 'Paginator');

	public function  beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'admin/galleries';
		
		$this->Auth->allow('choose_gallery', 'view_gallery', 'ajax_get_gallery_photos_after');
	}
	
	public function choose_gallery() {
		$this->renderEmpty();
	}
	
	public function view_gallery($gallery_id = null) {
		$gallery_listing_config = $this->viewVars['theme_config']['admin_config']['theme_gallery_listing_config'];
		
		$conditions = array();
		if (isset($gallery_id)) {
			$conditions = array(
				'PhotoGallery.id' => $gallery_id
			);
		}
		
		// find the gallery
		$curr_gallery = $this->PhotoGallery->find('first', array(
			'conditions' => $conditions,
			'limit' => 1,
			'contain' => false
		));
		
		$this->paginate = array(
			'PhotoGalleriesPhoto' => array(
				'conditions' => array(
					'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id
				),
				'limit' => $gallery_listing_config['default_images_per_page'], // DREW TODO - maybe make this number (the number of photos per gallery page) a global option in the admin
				'contain' => array(
					'Photo' => array(
						'PhotoFormat'
					)
				),
				'order' => 'PhotoGalleriesPhoto.photo_order'
			)
		);
		$photos = $this->paginate('PhotoGalleriesPhoto');    

		
		$this->set(compact('curr_gallery', 'photos', 'gallery_id'));
		
		$this->renderEmpty();
	}

	public function admin_index() {
		$galleries = $this->PhotoGallery->find('all', array(
			'limit' => 100,
			'contain' => false
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
	
	public function admin_ajax_get_photos_in_gallery($gallery_id) {
		$photos = $this->PhotoGallery->find('first', array(
			'conditions' => array(
				'PhotoGallery.id' => $gallery_id
			),
			'contain' => array(
				'PhotoGalleriesPhoto' => array(
					'Photo'
				)
			)
		));
		
		$not_in_gallery_icon_size = isset($this->params['form']['not_in_gallery_icon_size']) ? $this->params['form']['not_in_gallery_icon_size']: 'medium';
		
		$returnArr = array();
		$returnArr['count'] = count($photos['PhotoGalleriesPhoto']);
		$returnArr['image_template_html'] = preg_replace( "/[\n\r]/", '', $this->Element('admin/photo/photo_connect_in_gallery_photo_cont', array(
			'connected_photos' => array('dummy'),
			'hide_data' => true,
			'not_in_gallery_icon_size' => $not_in_gallery_icon_size 
		)));
		$returnArr['html'] = $this->Element('admin/photo/photo_connect_in_gallery_photo_cont', array( 
			'connected_photos' => $photos['PhotoGalleriesPhoto'], 
			'not_in_gallery_icon_size' => $not_in_gallery_icon_size 
		));
		$this->return_json($returnArr);
	}
	
	public function ajax_get_gallery_photos_after($gallery_id, $last_photo_id, $limit) {
		if (empty($limit)) {
			$limit = 30;
		}
		

		$last_photo = $this->PhotoGalleriesPhoto->find('first', array(
			'conditions' => array(
				'PhotoGalleriesPhoto.photo_id' => $last_photo_id,
				'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id
			),
			'contain' => false
		));
		
		$photos_total_count = $this->PhotoGalleriesPhoto->find('count', array(
			'conditions' => array(
				'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id,
				'PhotoGalleriesPhoto.photo_order >' => $last_photo['PhotoGalleriesPhoto']['photo_order']
			),
			'order' => 'PhotoGalleriesPhoto.photo_order',
			'contain' => false
		));
		
		$photos = $this->PhotoGalleriesPhoto->find('all', array(
			'conditions' => array(
				'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id,
				'PhotoGalleriesPhoto.photo_order >' => $last_photo['PhotoGalleriesPhoto']['photo_order']
			),
			'order' => 'PhotoGalleriesPhoto.photo_order',
			'limit' => $limit,
			'contain' => array(
				'Photo'
			)
		));

		
		$returnArr['has_more'] = count($photos) < $photos_total_count;
		$returnArr['large_html'] = $this->element('gallery/gallery_image_lists/simple_list', array(
			'photos' => $photos,
			'height' => '500',
			'width' => '2000',
			'sharpness' => '.4'
		));
		$returnArr['small_html'] = $this->element('gallery/gallery_image_lists/simple_list', array(
			'photos' => $photos,
			'height' => '50',
			'width' => '200',
			'sharpness' => '.4'
		));
		
		$this->log($photos, 'photos_after');

		$this->return_json($returnArr);
	}
	
	public function admin_edit_gallery_connect_photos($gallery_id, $last_photo_id = 0) {
		$limit = 30;
		
		$this->data = $this->PhotoGallery->find('first', array(
			'conditions' => array('PhotoGallery.id' => $gallery_id),
			'contain' => array(
				'PhotoGalleriesPhoto' => array(
					'Photo'
				)
			)
		));
		
		$photo_ids = Set::extract('/PhotoGalleriesPhoto/Photo/id', $this->data);
		
		// get named params for sorting
		$order = isset($this->params['named']['order']) ? $this->params['named']['order']: 'modified';
		$sort_dir = isset($this->params['named']['sort_dir']) ? $this->params['named']['sort_dir']: 'desc';
		
		// get params for filters
		$photo_formats = isset($this->params['form']['photo_formats']) ? $this->params['form']['photo_formats']: null;
		$photos_not_in_a_gallery = isset($this->params['form']['photos_not_in_a_gallery']) ? $this->params['form']['photos_not_in_a_gallery']: false;
		$not_in_gallery_icon_size = isset($this->params['form']['not_in_gallery_icon_size']) ? $this->params['form']['not_in_gallery_icon_size']: 'medium';
		
		if ($not_in_gallery_icon_size == 'small') {
			$limit = 85;
		} else if ($not_in_gallery_icon_size == 'medium') {
			$limit = 35;
		} else if ($not_in_gallery_icon_size == 'large') {
			$limit = 25;
		}
		
		$conditions = array(
			'NOT' => array(
				'Photo.id' => $photo_ids
			)
		);
		/*******************************************
		 * figure out filter conditions
		 */
		if (!empty($photo_formats)) {
			$format_ids = array();
			foreach ($photo_formats as $photo_format) {
				$format = $this->PhotoFormat->find('first', array(
					'conditions' => array(
						'PhotoFormat.ref_name' => $photo_format
					),
					'contain' => false
				));
				$format_ids[] = $format['PhotoFormat']['id'];
			}
			$conditions['PhotoFormat.id'] = $format_ids;
		}
		if ($photos_not_in_a_gallery === 'true') {
			$query = 'SELECT photos.id FROM photos
					  LEFT JOIN photo_galleries_photos ON photos.id = photo_galleries_photos.photo_id
					  WHERE photo_galleries_photos.photo_id IS NULL;';
			$photo_ids = $this->Photo->query($query);
			$photo_ids = Set::extract('/photos/id', $photo_ids);
			
			$conditions['Photo.id'] = $photo_ids;
		}
		// end filter find conditions
		
		
		/*******************************************
		 * figure out sort conditions
		 */
		if ($last_photo_id > 0) {
			$last_photo = $this->Photo->find('first', array(
				'conditions' => array(
					'Photo.id' => $last_photo_id
				),
				'contain' => false
			));
			if ($sort_dir == 'asc') {
				$comp = '>';
			} else {
				$comp = '<';
			}
			$conditions['Photo.'.$order.' '.$comp] = $last_photo['Photo'][$order];
		}
		// end sort find conditions
		
		
		$not_connected_photos = $this->Photo->find('all', array(
			'conditions' => $conditions,
			'order' => array(
				$order => $sort_dir
			),
			'contain' => array(
				'PhotoFormat'
			),
			'limit' => $limit
			
		));
		
		$set_vars = compact('not_connected_photos', 'gallery_id', 'last_photo_id', 'order', 'sort_dir', 'not_in_gallery_icon_size');
		if ($this->RequestHandler->isAjax()) {
			$returnArr['count'] = count($not_connected_photos);
			$returnArr['html'] = $this->element('admin/photo/photo_connect_not_in_gallery_photo_cont', $set_vars);
			$returnArr['params'] = $set_vars;

			$this->return_json($returnArr);
		} else {
			$this->set($set_vars);
		}
	}
	
	
	/**
	 *
	 * @param type $gallery_id -- the gallery from which to remove photos
	 * @param type $photo_id -- the photo to remove (null means remove all photos)
	 */
	public function admin_ajax_removephotos_from_gallery($gallery_id, $photo_id = null) {
		$returnArr = array();
		
		$conditions = array(
			'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id
		);
		
		if (!empty($photo_id)) {
			$conditions['PhotoGalleriesPhoto.photo_id'] = $photo_id;
		}
		
		if ($this->PhotoGalleriesPhoto->deleteAll($conditions, true, true)) {
			$returnArr['code'] = 1;
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
		
		$not_in_gallery_icon_size = isset($this->params['form']['not_in_gallery_icon_size']) ? $this->params['form']['not_in_gallery_icon_size']: 'medium';
		
		$this->set(compact('gallery_id', 'not_in_gallery_icon_size'));
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
		
		
		$this->PhotoGalleriesPhoto->id = $PhotoGalleriesPhoto_to_move['PhotoGalleriesPhoto']['id'];
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