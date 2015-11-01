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
			$this->Session->setFlash(__('Failed to create new standard gallery', true), 'admin/flashMessage/error');
			$this->PhotoGallery->major_error('Failed to create new standard gallery in (add_standard_gallery) in photo_galleries_controller.php', compact('new_gallery'));
			$this->redirect('/admin/photo_galleries');
		} else {
			//$this->Session->setFlash(__('New page created', true), 'admin/flashMessage/success');
			$this->redirect('/admin/photo_galleries/edit_gallery/'.$this->PhotoGallery->id);
		}
	}
	
	public function admin_add_smart_gallery() {
		$new_gallery = array();
		$new_gallery['PhotoGallery']['type'] = 'smart';
		$new_gallery['PhotoGallery']['display_name'] = 'Gallery Name';
		
		$this->PhotoGallery->create();
		if (!$this->PhotoGallery->save($new_gallery)) {
			$this->Session->setFlash(__('Failed to create new smart gallery', true), 'admin/flashMessage/error');
			$this->PhotoGallery->major_error('Failed to create new smart gallery in (add_smart_gallery) in photo_galleries_controller.php', compact('new_gallery'));
			$this->redirect('/admin/photo_galleries');
		} else {
			//$this->Session->setFlash(__('New page created', true), 'admin/flashMessage/success');
			$this->redirect('/admin/photo_galleries/edit_gallery/'.$this->PhotoGallery->id);
		}
	}
	
	public function choose_gallery() {
		$this->setup_front_end_view_cache($this);
		
		$this->ThemeRenderer->render($this);
	}
	
	public function view_gallery($gallery_id = null) {
		$this->setup_front_end_view_cache($this);
		
		$custom_settings = $this->viewVars['theme_config']['admin_config']['theme_avail_custom_settings']['settings'];
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
		$limit = $gallery_listing_config['default_images_per_page'];
		if (!empty($gallery_listing_config['based_on_theme_option']) && !empty($custom_settings[$gallery_listing_config['based_on_theme_option']]['current_value'])) {
			$limit = $custom_settings[$gallery_listing_config['based_on_theme_option']]['current_value'];
		}
		if ($this->is_mobile === true) {
			$limit = 1000; // DREW TODO - maybe we need to change this
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
		
		foreach ($photos as &$photo) {
			// make sure all photos have at least untitled as a title
//			if (empty($photo['Photo']['display_title'])) {
//				$photo['Photo']['display_title'] = 'Untitled';
//			}
			
			// unset date taken if use_data_taken empty
			if (empty($photo['Photo']['use_date_taken'])) {
				unset($photo['Photo']['date_taken']);
			}
		}
		$this->set(compact('curr_gallery', 'photos', 'gallery_id', 'smart_settings'));
		
		$this->ThemeRenderer->render($this);
	}

	public function admin_index() {
//		, (SELECT count(*) FROM photo_galleries_photos WHERE photo_gallery_id = PhotoGallery.id) as photos_count
		$gallery_query = "
			SELECT PhotoGallery.id, PhotoGallery.weight, PhotoGallery.type, PhotoGallery.display_name, PhotoGallery.description, PhotoGallery.created
				FROM photo_galleries AS PhotoGallery
				ORDER BY PhotoGallery.weight ASC
		";
		$photo_galleries = $this->PhotoGallery->query($gallery_query);

		// convert tag ids to int so json will be int and sort correct in angular
		foreach ($photo_galleries as &$photo_gallery) {
			$photo_gallery['PhotoGallery']['id'] = (int) $photo_gallery['PhotoGallery']['id'];
//			$photo_gallery['PhotoGallery']['photos_count'] = (int) $photo_gallery[0]['photos_count'];
			unset($photo_gallery[0]);
		}
//		$photo_galleries = Set::combine($photo_galleries, '{n}.PhotoGallery.id', '{n}');

		$this->return_json($photo_galleries);
	}
	
	public function admin_view($gallery_id, $gallery_icon_size = 'medium', $order_by = 'modified', $sort_dir = 'desc', $photos_not_in_a_gallery = false, $last_photo_id = 0, $photo_formats = null) {
		$gallery_query = "
			SELECT 
				PhotoGallery.id, PhotoGallery.weight, PhotoGallery.type, PhotoGallery.display_name, PhotoGallery.description, PhotoGallery.created, (SELECT count(*) FROM photo_galleries_photos WHERE photo_gallery_id = PhotoGallery.id) as photos_count
					FROM photo_galleries AS PhotoGallery
				WHERE PhotoGallery.id = :id
				ORDER BY PhotoGallery.weight ASC
		";
		$photo_galleries = $this->PhotoGallery->query($gallery_query, array(
			'id' => $gallery_id
		));
		
		
		$icon_sizes = $this->Photo->get_admin_photo_icon_size($gallery_icon_size);
		$height = $icon_sizes['height'];
		$width = $icon_sizes['width'];
		$class = $icon_sizes['class'];
		
		foreach ($photo_galleries as &$photo_gallery) {
			$photo_gallery['PhotoGallery']['id'] = (int) $photo_gallery['PhotoGallery']['id'];
			$photo_gallery['PhotoGallery']['photos_count'] = (int) $photo_gallery[0]['photos_count'];
			$photo_galleries_photo_query = "
				SELECT * FROM photo_galleries_photos AS PhotoGalleriesPhoto
				WHERE PhotoGalleriesPhoto.photo_gallery_id = :photo_gallery_id
				ORDER BY PhotoGalleriesPhoto.photo_order ASC
			";
			$photo_gallery['PhotoGalleriesPhoto'] = $this->PhotoGalleriesPhoto->query($photo_galleries_photo_query, array(
				'photo_gallery_id' => $photo_gallery['PhotoGallery']['id']
			));
			
			foreach ($photo_gallery['PhotoGalleriesPhoto'] as &$photo_galleries_photo) {
				if (!empty($photo_galleries_photo['PhotoGalleriesPhoto']['photo_id'])) {
					$photo_galleries_photo['PhotoGalleriesPhoto']['photo_cache_url'] = $this->Photo->get_photo_path($photo_galleries_photo['PhotoGalleriesPhoto']['photo_id'], $height, $width);
					$photo_galleries_photo['PhotoGalleriesPhoto']['photo_cache_class'] = $class;
				}
			}
			unset($photo_gallery[0]);
		}
//		$photo_gallery['PhotoGalleriesPhoto'] = Set::combine($photo_gallery['PhotoGalleriesPhoto'], '{n}.PhotoGalleriesPhoto.photo_id', '{n}');
		
		
		
		/*$total_photos = $this->Photo->count_total_photos();
		$max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
		$photos_left_to_add = LIMIT_MAX_FREE_PHOTOS - $total_photos;
		if (!empty($max_photo_id) && $photos_left_to_add < 0) {
//			$this->FeatureLimiter->limit_view_go($this, 'unlimited_photos');
			$this->FeatureLimiter->limit_function_403();
			return;
		}*/
		
		
		$limit = 30;
		
		// get the photo ids of the current gallery
		$photo_ids = Set::extract('PhotoGalleriesPhoto.{n}.PhotoGalleriesPhoto.photo_id', $photo_galleries[0]);
		
		
		if ($gallery_icon_size == 'small') {
			$limit = 85;
		} else if ($gallery_icon_size == 'medium') {
			$limit = 35;
		} else if ($gallery_icon_size == 'large') {
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
			$conditions['PhotoFormat.id'] = $this->PhotoFormat->get_photo_format_ids_by_ref_names(explode('|', $photo_formats));
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
			$conditions['Photo.'.$order_by.' '.$comp] = $last_photo['Photo'][$order_by];
		}
		// end sort find conditions
		
		
		$not_connected_photos = $this->Photo->find('all', array(
			'conditions' => $conditions,
			'order' => array(
				$order_by => $sort_dir
			),
			'contain' => array(
				'PhotoFormat'
			),
			'limit' => $limit
			
		));
		foreach ($not_connected_photos as &$not_connected_photo) {
			if (!empty($not_connected_photo['Photo']['id'])) {
				$not_connected_photo['Photo']['photo_cache_url'] = $this->Photo->get_photo_path($not_connected_photo['Photo']['id'], $height, $width);
				$not_connected_photo['Photo']['photo_cache_class'] = $class;
			}
		}
//		$not_connected_photos = Set::combine($not_connected_photos, '{n}.Photo.id', '{n}');
		
		
		$this->return_json(array(
			'photo_gallery' => $photo_galleries[0],
			'not_connected_photos' => $not_connected_photos,
		));
	}
	
	
	// DREW TODO - get this working
//	public function admin_mobile_index() {
////		$this->layout = 'admin/sidebar_less';
//		$curr_page = 'galleries';
//		
//		$galleries = $this->PhotoGallery->find('all', array(
//			'limit' => 100,
//			'contain' => false
//		));
//		
//		$this->set(compact('galleries', 'curr_page'));
//		$this->render('admin_index'); // the new angular page
//	}
	
	public function admin_manage() {
//		$this->layout = 'admin/sidebar_less';
		$curr_page = 'galleries';
		
		$galleries = $this->PhotoGallery->find('all', array(
			'limit' => 100,
			'contain' => false
		));
		
		$this->set(compact('galleries', 'curr_page'));
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
				$this->Session->setFlash(__('Failed to save smart settings.', true), 'admin/flashMessage/error');
				$this->PhotoGallery->major_error('Failed to save smart settings.', compact('smart_gallery'));
			} else {
				$this->Session->setFlash(__('Smart Gallery settings saved', true), 'admin/flashMessage/success');
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
				$this->Session->setFlash(__('Failed to save photo gallery', true), 'admin/flashMessage/error');
			} else {
				$this->Session->setFlash(__('Photo gallery saved', true), 'admin/flashMessage/success');
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
		
		if (isset($this->params['form']['not_in_gallery_icon_size'])) {
			$this->Session->write('not_in_gallery_icon_size', $this->params['form']['not_in_gallery_icon_size']);
		}
		if ($this->Session->check('not_in_gallery_icon_size')) {
			$not_in_gallery_icon_size = $this->Session->read('not_in_gallery_icon_size');
		} else {
			$not_in_gallery_icon_size = 'medium';
		}
		
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
	
	public function ajax_get_gallery_photos_after($gallery_id, $last_photo_id, $limit = 30, $data_layout = '') {
		$returnArr = array();
		
		$curr_gallery = $this->PhotoGallery->find('first', array(
			'conditions' => array(
				'PhotoGallery.id' => $gallery_id,
			),
			'contain' => false,
		));
		
		if ($curr_gallery['PhotoGallery']['type'] == 'smart') {
			$smart_settings = $curr_gallery['PhotoGallery']['smart_settings'];
			
			$found_photo_ids = $this->PhotoGallery->get_smart_gallery_photo_ids($smart_settings);
			$found_photos = $this->Photo->find('all', array(
				'conditions' => array(
					'Photo.id' => $found_photo_ids
				),
				'contain' => false,
				'order' => "Photo.{$smart_settings['order_by']} {$smart_settings['order_direction']}"
			));
			
			
			$photos_total_count = count($found_photo_ids);
			$photos = array();
			$start_find = false;
			$found_end = false;
			$returnArr['has_more'] = false;
			$count = 0; foreach ($found_photos as $found_photo) {
				if ($found_end) { // reached the end earlier and loop not finished
					$returnArr['has_more'] = true;
					break;
				}
				
				if ($start_find && !$found_end && $count < $limit) {
					$count++;
					$photos[] = $found_photo;

					if ($count == $limit) {
						$found_end = true;
					}
				}
				
				if ($found_photo['Photo']['id'] == $last_photo_id) {
					$start_find = true;
				}
			}
		} else {
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
		}
		
		
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
		$returnArr['photo_data_html'] = '';
		foreach ($photos as $curr_photo) {
			$photo_sellable_prints = $this->Photo->get_enabled_photo_sellable_prints($curr_photo['Photo']['id']);
			if (empty($data_layout)) {
				$data_layout = 'basic_image_data';
			}
			$returnArr['photo_data_html'] .= $this->element("gallery/image_data/data_layouts/$data_layout", array(
				'photo' => $curr_photo,
				'photo_sellable_prints' => $photo_sellable_prints,
			));
		}


		$this->return_json($returnArr);
	}
	
	/*public function admin_angular_edit_gallery_connect_photos($gallery_id, $order = 'modified', $sort_dir = 'desc', $photo_formats = null, $photos_not_in_a_gallery = false, $gallery_icon_size = 'medium') {
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
//		$order = isset($this->params['named']['order']) ? $this->params['named']['order']: 'modified';
//		$sort_dir = isset($this->params['named']['sort_dir']) ? $this->params['named']['sort_dir']: 'desc';
		
		// get params for filters
//		$photo_formats = isset($this->params['form']['photo_formats']) ? $this->params['form']['photo_formats']: null;
//		$photos_not_in_a_gallery = isset($this->params['form']['photos_not_in_a_gallery']) ? $this->params['form']['photos_not_in_a_gallery']: false;
		
//		if (isset($this->params['form']['not_in_gallery_icon_size'])) {
//			$this->Session->write('not_in_gallery_icon_size', $this->params['form']['not_in_gallery_icon_size']);
//		}
//		if ($this->Session->check('not_in_gallery_icon_size')) {
//			$not_in_gallery_icon_size = $this->Session->read('not_in_gallery_icon_size');
//		} else {
//			$not_in_gallery_icon_size = 'medium';
//		}
		
		
		
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
		
		
		$returnData = compact('not_connected_photos', 'gallery_id', 'last_photo_id', 'order', 'sort_dir', 'not_in_gallery_icon_size');
		$this->return_json($returnData);
		
//		$set_vars = compact('not_connected_photos', 'gallery_id', 'last_photo_id', 'order', 'sort_dir', 'not_in_gallery_icon_size');
//		if ($this->RequestHandler->isAjax()) {
//			$returnArr['count'] = count($not_connected_photos);
//			$returnArr['html'] = $this->element('admin/photo/photo_connect_not_in_gallery_photo_cont', $set_vars);
//			$returnArr['params'] = $set_vars;
//
//			$this->return_json($returnArr);
//		} else {
//			$this->set($set_vars);
//		}
	}*/
	
	
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
		
		if (isset($this->params['form']['not_in_gallery_icon_size'])) {
			$this->Session->write('not_in_gallery_icon_size', $this->params['form']['not_in_gallery_icon_size']);
		}
		if ($this->Session->check('not_in_gallery_icon_size')) {
			$not_in_gallery_icon_size = $this->Session->read('not_in_gallery_icon_size');
		} else {
			$not_in_gallery_icon_size = 'medium';
		}
		
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
	
	public function admin_ajax_movephoto_into_gallery($photo_id, $photo_gallery_id, $gallery_icon_size = 'medium') {
		$icon_sizes = $this->Photo->get_admin_photo_icon_size($gallery_icon_size);
		$height = $icon_sizes['height'];
		$width = $icon_sizes['width'];
		$class = $icon_sizes['class'];
		
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
		
		if (!$this->PhotoGallery->add_photo_to_gallery($photo_id, $photo_gallery_id)) {
			$this->Photo->major_error('failed to add a photo to a gallery in admin_ajax_movephoto_into_gallery');
		}
		
		// now find the photo url
		$photo_gallery_photo = $this->PhotoGalleriesPhoto->find('first', array(
			'conditions' => array(
				'PhotoGalleriesPhoto.photo_id' => $photo_id,
				'PhotoGalleriesPhoto.photo_gallery_id' => $photo_gallery_id
			),
			'contain' => false
		));
		$photo_gallery_photo['PhotoGalleriesPhoto']['photo_cache_class'] = $class;
		$photo_gallery_photo['PhotoGalleriesPhoto']['photo_cache_url'] = $this->Photo->get_photo_path($photo_id, $height, $width);;
		
		
		$returnArr['code'] = 1;
		$returnArr['message'] = 'successfully moved photo into gallery';
		$returnArr['data'] = $photo_gallery_photo;
		$this->return_json($returnArr);
	}
	
//	public function admin_edit_gallery_arrange_photos($gallery_id) {
//		$total_photos = $this->Photo->count_total_photos();
//		$max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
//		$photos_left_to_add = LIMIT_MAX_FREE_PHOTOS - $total_photos;
//		if (!empty($max_photo_id) && $photos_left_to_add < 0) {
//			$this->FeatureLimiter->limit_view_go($this, 'unlimited_photos');
//			return;
//		}
//		
//		
//		$this->data = $this->PhotoGallery->find('first', array(
//			'conditions' => array('PhotoGallery.id' => $gallery_id),
//			'contain' => array(
//				'PhotoGalleriesPhoto' => array(
//					'Photo'
//				)
//			)
//		));
//		
//		$not_in_gallery_icon_size = isset($this->params['form']['not_in_gallery_icon_size']) ? $this->params['form']['not_in_gallery_icon_size']: 'medium';
//		
//		$this->set(compact('gallery_id', 'not_in_gallery_icon_size'));
//	}
	
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
		$this->log($photo_gallery_id, 'admin_ajax_set_photo_order_in_gallery');
		$this->log($photo_id, 'admin_ajax_set_photo_order_in_gallery');
		$this->log($new_order, 'admin_ajax_set_photo_order_in_gallery');
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
			$this->Photo->major_error('Failed to delete photo gallery in admin_delete_gallery', compact('gallery_id'));
		}
		
		
		$this->redirect('/admin/photo_galleries');
	}
	 
}