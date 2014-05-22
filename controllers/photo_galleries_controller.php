<?php
class PhotoGalleriesController extends AppController {
	public $name = 'PhotoGalleries';
	public $uses = array('PhotoGallery', 'Photo', 'PhotoGalleriesPhoto', 'PhotoFormat', 'Tag', 'PhotosTag');
	public $helpers = array('Photo', 'Gallery', 'Paginator');

	public function  beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'admin/galleries';
		
		$this->Auth->allow('choose_gallery', 'view_gallery', 'ajax_get_gallery_photos_after');
	}

	
	
	public function admin_add_standard_gallery() {
		$new_gallery = array();
		$new_gallery['PhotoGallery']['type'] = 'standard';
		$new_gallery['PhotoGallery']['display_name'] = 'Gallery Name';
		
		$this->PhotoGallery->create();
		if (!$this->PhotoGallery->save($new_gallery)) {
			$this->Session->setFlash('Failed to create new standard gallery');
			$this->PhotoGallery->major_error('Failed to create new standard gallery in (add_standard_gallery) in photo_galleries_controller.php', compact('new_gallery'));
			$this->redirect('/admin/photo_galleries');
		} else {
			//$this->Session->setFlash('New page created');
			$this->redirect('/admin/photo_galleries/edit_gallery/'.$this->PhotoGallery->id);
		}
	}
	
	public function admin_add_smart_gallery() {
		$new_gallery = array();
		$new_gallery['PhotoGallery']['type'] = 'smart';
		$new_gallery['PhotoGallery']['display_name'] = 'Gallery Name';
		
		$this->PhotoGallery->create();
		if (!$this->PhotoGallery->save($new_gallery)) {
			$this->Session->setFlash('Failed to create new smart gallery');
			$this->PhotoGallery->major_error('Failed to create new smart gallery in (add_smart_gallery) in photo_galleries_controller.php', compact('new_gallery'));
			$this->redirect('/admin/photo_galleries');
		} else {
			//$this->Session->setFlash('New page created');
			$this->redirect('/admin/photo_galleries/edit_gallery/'.$this->PhotoGallery->id);
		}
	}
	
	public function choose_gallery() {
		$this->setup_front_end_view_cache($this);
		
		$this->ThemeRenderer->render($this);
	}
	
	public function view_gallery($gallery_id = null) {
		$this->setup_front_end_view_cache($this);
		
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

		$photos = array();
		$limit = $gallery_listing_config['default_images_per_page']; // DREW TODO - maybe make this number (the number of photos per gallery page) a global option in the admin
		if ($this->is_mobile === true) {
			$limit = 1000;
		}
		if ($curr_gallery['PhotoGallery']['type'] == 'smart') {
			$smart_settings = $curr_gallery['PhotoGallery']['smart_settings'];
			
			$found_photo_ids = $this->PhotoGallery->get_smart_gallery_photo_ids($smart_settings);
			
			// do the final find with pagination
			$this->paginate = array(
				'Photo' => array(
					'conditions' => array(
						'Photo.id' => $found_photo_ids
					),
					'limit' => $limit,
					'contain' => false,
					'order' => "Photo.{$smart_settings['order_by']} {$smart_settings['order_direction']}"
				)
			);
			$photos = $this->paginate('Photo');      
			
		} else {
			$max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
			$max_photo_extra_condition = '';
			if (!empty($max_photo_id)) {
				$max_photo_extra_condition = "PhotoGalleriesPhoto.photo_id <= $max_photo_id";
			}
			
			$this->paginate = array(
				'PhotoGalleriesPhoto' => array(
					'conditions' => array(
						'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id,
						$max_photo_extra_condition,
					),
					'limit' => $limit,
					'contain' => array(
						'Photo'
					),
					'order' => 'PhotoGalleriesPhoto.photo_order'
				)
			);
			$photos = $this->paginate('PhotoGalleriesPhoto');    
		}
		
		// add in photo format using best performance
		$this->Photo->add_photo_format($photos);
		
		
		$this->set(compact('curr_gallery', 'photos', 'gallery_id', 'smart_settings'));
		
		$this->ThemeRenderer->render($this);
	}

	public function admin_index() {
		$galleries = $this->PhotoGallery->find('all', array(
			'limit' => 100,
			'contain' => false
		));
		
		$this->set(compact('galleries'));
	}
	
	public function admin_edit_smart_gallery($id) {
		if ( !empty($this->data) ) {
			// get settings to save
			$smart_settings = $this->data['smart_settings'];
			$save_settings['tags'] = isset($smart_settings['tags']) ? $smart_settings['tags'] : array();
			$save_settings['date_added_from'] = (isset($smart_settings['date_added_from']) && $smart_settings['date_added_from'] != $smart_settings['date_added_from_default']) ? date( 'm/d/Y', strtotime($smart_settings['date_added_from'])) : null;
			$save_settings['date_added_to'] = (isset($smart_settings['date_added_to']) && $smart_settings['date_added_to'] != $smart_settings['date_added_to_default']) ? date( 'm/d/Y', strtotime($smart_settings['date_added_to'])) : null;
			$save_settings['date_taken_from'] = (isset($smart_settings['date_taken_from']) && $smart_settings['date_taken_from'] != $smart_settings['date_taken_from_default']) ? date( 'm/d/Y', strtotime($smart_settings['date_taken_from'])) : null;
			$save_settings['date_taken_to'] = (isset($smart_settings['date_taken_to']) && $smart_settings['date_taken_to'] != $smart_settings['date_taken_to_default']) ? date( 'm/d/Y', strtotime($smart_settings['date_taken_to'])) : null;
			$save_settings['photo_format'] = isset($smart_settings['photo_format']) ? $smart_settings['photo_format'] : array();
			$save_settings['order_by'] = isset($smart_settings['order_by']) ? $smart_settings['order_by'] : 'created';
			$save_settings['order_direction'] = isset($smart_settings['order_direction']) ? $smart_settings['order_direction'] : 'desc';
			
			
			$smart_gallery['PhotoGallery']['id'] = $id;
			$smart_gallery['PhotoGallery']['smart_settings'] = serialize($save_settings);
			if (!$this->PhotoGallery->save($smart_gallery)) {
				$this->Session->setFlash('Failed to save smart settings.');
				$this->PhotoGallery->major_error('Failed to save smart settings.', compact('smart_gallery'));
			}
		}
		
		$this->data = $this->PhotoGallery->find('first', array(
			'conditions' => array(
				'PhotoGallery.id' => $id
			),
			'contain' => false
		));
		
		
		$tags = $this->Tag->find('all', array(
			'order' => array(
				'Tag.name'
			),
			'contain' => false
		));
		
		$this->set(compact('tags', 'id'));
	}
	
	public function admin_edit_gallery($id) {
		
		if ( !empty($this->data) ) {
			// set or unset the id (depending on if its an edit or add)
			$this->data['PhotoGallery']['id'] = $id;
			

			if (!$this->PhotoGallery->save($this->data)) {
				$this->PhotoGallery->major_error('failed to save photo gallery in edit gallery', $this->data);
				$this->Session->setFlash('Failed to save photo gallery');
			} else {
				$this->Session->setFlash('Photo gallery saved');
			}
 		} 
		
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
	
	public function ajax_get_gallery_photos_after($gallery_id, $last_photo_id, $limit=30) {
		// DREW TODO - make this function use smart gallery finding code
		// $found_photo_ids = $this->PhotoGallery->get_smart_gallery_photo_ids($smart_settings);

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
		

		$this->return_json($returnArr);
	}
	
	public function admin_edit_gallery_connect_photos($gallery_id, $last_photo_id = 0) {
		$total_photos = $this->Photo->count_total_photos();
		$max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
		$photos_left_to_add = LIMIT_MAX_FREE_PHOTOS - $total_photos;
		if (!empty($max_photo_id) && $photos_left_to_add < 0) {
			$this->FeatureLimiter->limit_view_go($this, 'unlimited_photos');
			return;
		}
		
		
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
		$total_photos = $this->Photo->count_total_photos();
		$max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
		$photos_left_to_add = LIMIT_MAX_FREE_PHOTOS - $total_photos;
		if (!empty($max_photo_id) && $photos_left_to_add < 0) {
			$this->FeatureLimiter->limit_view_go($this, 'unlimited_photos');
			return;
		}
		
		
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
			$this->PhotoGallery->major_error('failed to change gallery order', compact('photoGalleryId', 'newOrder'));
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
	
	public function admin_delete_gallery($gallery_id = null) {
		if ($gallery_id == null) {
			 $this->redirect('/admin/photo_galleries');
		}
		
		
		if ($this->PhotoGallery->delete($gallery_id)) {
			$this->Session->setFlash(__('Gallery deleted successfully.', true), 'admin/flashMessage/success');
		} else {
			$this->Session->setFlash(__('Failed to delete gallery.', true), 'admin/flashMessage/error');
			$this->Photo->major_error('Failed to delete photo gallery in admin_delete_gallery', compact($gallery_id));
		}
		
		
		$this->redirect('/admin/photo_galleries');
	}
	 
}