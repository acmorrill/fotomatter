<?php
class PhotoGallery extends AppModel {
	public $name = 'PhotoGallery';
	public $hasMany = array(
		'PhotoGalleriesPhoto' => array(
			'order' => array(
				"PhotoGalleriesPhoto.photo_order" => 'asc'
			),
			'dependent' => true
		),
	);
	public $actsAs = array('Ordered' => array('foreign_key' => false));

	public function beforeDelete($cascade = true) {
		parent::beforeDelete($cascade);
		
		$gallery_id = $this->id;
		
		$delete_one_level_menu_query = "DELETE FROM site_one_level_menus WHERE external_id = :gallery_id AND external_model = 'PhotoGallery'";
		if (!$this->query($delete_one_level_menu_query, array('gallery_id' => $gallery_id))) {
			$this->major_error('Failed to delete one level menu connection on photo gallery delete', compact('gallery_id'));
			return false;
		}
		
		$delete_two_level_menu_query = "DELETE FROM site_two_level_menus WHERE external_id = :gallery_id AND external_model = 'PhotoGallery'";
		if (!$this->query($delete_two_level_menu_query, array('gallery_id' => $gallery_id))) {
			$this->major_error('Failed to delete two level menu connection on photo gallery delete', compact('gallery_id'));
			return false;
		}
		
		$delete_two_level_menu_container_item_query = "DELETE FROM site_two_level_menu_container_items WHERE external_id = :gallery_id AND external_model = 'PhotoGallery'";
		if (!$this->query($delete_two_level_menu_container_item_query, array('gallery_id' => $gallery_id))) {
			$this->major_error('Failed to delete two level menu container item connection on photo gallery delete', compact('gallery_id'));
			return false;
		}
		
		return true;
	}
	
	public function get_gallery_photo() {
		
	}
	
	public function get_first_gallery_by_weight() {
		$first_gallery = $this->find('first', array(
			'order' => array(
				'PhotoGallery.weight'
			),
			'contain' => false
		));
		
		return $first_gallery;
	}
	
	public function add_photo_to_gallery($the_photo, $gallery_id) {
		$exist = $this->PhotoGalleriesPhoto->find('first', array(
			'conditions' => array(
				'PhotoGalleriesPhoto.photo_id' => $the_photo,
				'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id
			),
			'contain' => false
		));
		
		if ($exist) {
			return true;
		}
		
		$photo_gallery_photo['PhotoGalleriesPhoto'] = array(
			'photo_id' => $the_photo,
			'photo_gallery_id' => $gallery_id
		);

		$this->PhotoGalleriesPhoto->create();
		return $this->PhotoGalleriesPhoto->save($photo_gallery_photo);
	}

	
	public function afterFind($results, $primary = false) {
		parent::afterFind($results, $primary);
		
		if (isset($results['type']) && $results['type'] == 'smart') {
			if (!empty($results['smart_settings'])) {
				$results['smart_settings'] = unserialize($results['smart_settings']);
			} else {
				$this->fill_default_smart_settings($results['smart_settings']);
			}
		} else {
			foreach ($results as &$result) {
				if (is_array($result) && isset($result['PhotoGallery']['type']) && $result['PhotoGallery']['type'] == 'smart') {
					if (!empty($result['PhotoGallery']['smart_settings'])) {
						$result['PhotoGallery']['smart_settings'] = unserialize($result['PhotoGallery']['smart_settings']);
					} else {
						$this->fill_default_smart_settings($result['PhotoGallery']['smart_settings']);
					}
				}
			} unset($result);
		}

		return $results;
	}
	
	private function fill_default_smart_settings(&$smart_settings) { 
		if (empty($smart_settings)) {
			$smart_settings['tags'] = array();
			$smart_settings['date_added_from'] = null;
			$smart_settings['date_added_to'] = null;
			$smart_settings['date_taken_from'] = null;
			$smart_settings['date_taken_to'] = null;
			$smart_settings['photo_format'] = array();
			$smart_settings['order_by'] = 'created';
			$smart_settings['order_direction'] = 'desc';
		}
		
		return true;
	}
	
	
	public function get_smart_gallery_photo_ids($smart_settings) {
		$found_photo_ids = null;
		
		$this->PhotosTag = ClassRegistry::init('PhotosTag');
		$this->PhotoFormat = ClassRegistry::init('PhotoFormat');
		$this->Photo = ClassRegistry::init('Photo');
		
		$max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
		
		// find photos by tag
		if (!empty($smart_settings['tags'])) {
			$max_photo_extra_condition = '';
			if (!empty($max_photo_id)) {
				$max_photo_extra_condition = "PhotosTag.photo_id <= $max_photo_id";
			}
			$photos_tags = $this->PhotosTag->find('all', array(
				'conditions' => array(
					'PhotosTag.tag_id' => $smart_settings['tags'],
					$max_photo_extra_condition
				),
				'group' => array(
					'PhotosTag.photo_id'
				),
				'contain' => false
			));
			$found_photo_ids = Set::extract('/PhotosTag/photo_id', $photos_tags);
		}


		// find by photo_format
		if (!empty($smart_settings['photo_format'])) {
			$photo_formats = $this->PhotoFormat->find('all', array(
				'conditions' => array(
					'PhotoFormat.ref_name' => $smart_settings['photo_format']
				),
				'contain' => false
			));
			$photo_format_ids = Set::extract('/PhotoFormat/id', $photo_formats);
			$conditions = array();
			if (!empty($found_photo_ids)) {
				$conditions['Photo.id'] = $found_photo_ids;
			}
			if (!empty($max_photo_id)) {
				$conditions['Photo.id <='] = $max_photo_id;
			}
			
			$conditions['Photo.photo_format_id'] = $photo_format_ids;
			$photos_by_format = $this->Photo->find('all', array(
				'conditions' => $conditions,
				'contain' => false
			));
			$found_photo_ids = Set::extract('/Photo/id', $photos_by_format);
		}


		// find by date added
		if (isset($smart_settings['date_added_from']) || isset($smart_settings['date_added_to'])) {
//			$this->log($smart_settings['date_added_from'], 'smart_gallery_photo_id');
//			$this->log($smart_settings['date_added_to'], 'smart_gallery_photo_id');
//			$this->log(date('Y-m-d H:i:s', strtotime($smart_settings['date_added_from'])), 'smart_gallery_photo_id');
//			$this->log(date('Y-m-d H:i:s', strtotime($smart_settings['date_added_to'])), 'smart_gallery_photo_id');
			
			$conditions = array();
			if (!empty($found_photo_ids)) {
				$conditions['Photo.id'] = $found_photo_ids;
			}
			if (!empty($max_photo_id)) {
				$conditions['Photo.id <='] = $max_photo_id;
			}
			if (isset($smart_settings['date_added_from'])) {
				$conditions['Photo.created >='] = date('Y-m-d H:i:s', strtotime($smart_settings['date_added_from']));
			}
			if (isset($smart_settings['date_added_to'])) {
				$conditions['Photo.created <='] = date('Y-m-d H:i:s', strtotime($smart_settings['date_added_to']));
			}
			$photos_by_date_added = $this->Photo->find('all', array(
				'conditions' => $conditions,
				'contain' => false
			));
//			$this->log($conditions, 'smart_gallery_photo_id');
//			$this->log($photos_by_date_added, 'smart_gallery_photo_id');
			$found_photo_ids = Set::extract('/Photo/id', $photos_by_date_added);
		}


		// find by date taken
		if (isset($smart_settings['date_taken_from']) || isset($smart_settings['date_taken_to'])) {
			$conditions = array();
			if (!empty($found_photo_ids)) {
				$conditions['Photo.id'] = $found_photo_ids;
			}
			if (!empty($max_photo_id)) {
				$conditions['Photo.id <='] = $max_photo_id;
			}
			if (isset($smart_settings['date_taken_from'])) {
				$conditions['Photo.date_taken >='] = date('Y-m-d H:i:s', strtotime($smart_settings['date_taken_from']));
			}
			if (isset($smart_settings['date_taken_to'])) {
				$conditions['Photo.date_taken <='] = date('Y-m-d H:i:s', strtotime($smart_settings['date_taken_to']));
			}
			$photos_by_date_taken = $this->Photo->find('all', array(
				'conditions' => $conditions,
				'contain' => false
			));
			$found_photo_ids = Set::extract('/Photo/id', $photos_by_date_taken);
		}
		
		return $found_photo_ids;
	}
	
}