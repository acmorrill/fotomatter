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
		'SiteTwoLevelMenu' => array(
			'dependent' => true
		),
		'SiteTwoLevelMenuContainerItem' => array(
			'dependent' => true
		)
	);
	public $actsAs = array('Ordered' => array('foreign_key' => false));
	
	
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
		
		if ($primary === true) {
			foreach ($results as $key => $result) {
				if ( !empty($result['PhotoGallery']['smart_settings']) && $result['PhotoGallery']['type'] == 'smart' ) {
					$results[$key]['PhotoGallery']['smart_settings'] = unserialize($result['PhotoGallery']['smart_settings']);
				}
			}
		} else {
			if ( !empty($results['smart_settings']) && $results['type'] == 'smart' ) {
				$results['smart_settings'] = unserialize($results['smart_settings']);
			}
		}
		
		return $results;
	}
	
	
	public function get_smart_gallery_photo_ids($smart_settings) {
		$found_photo_ids = null;
		
		$this->PhotosTag = ClassRegistry::init('PhotosTag');
		$this->PhotoFormat = ClassRegistry::init('PhotoFormat');
		$this->Photo = ClassRegistry::init('Photo');
		
		
		// find photos by tag
		if (!empty($smart_settings['tags'])) {
			$photos_tags = $this->PhotosTag->find('all', array(
				'conditions' => array(
					'PhotosTag.tag_id' => $smart_settings['tags']
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
			$conditions['Photo.photo_format_id'] = $photo_format_ids;
			$photos_by_format = $this->Photo->find('all', array(
				'conditions' => $conditions,
				'contain' => false
			));
			$found_photo_ids = Set::extract('/Photo/id', $photos_by_format);
		}


		// find by date added
		if (isset($smart_settings['date_added_from']) || isset($smart_settings['date_added_to'])) {
			$this->log($smart_settings['date_added_from'], 'smart_gallery_photo_id');
			$this->log($smart_settings['date_added_to'], 'smart_gallery_photo_id');
			$this->log(date('Y-m-d H:i:s', strtotime($smart_settings['date_added_from'])), 'smart_gallery_photo_id');
			$this->log(date('Y-m-d H:i:s', strtotime($smart_settings['date_added_to'])), 'smart_gallery_photo_id');
			
			$conditions = array();
			if (!empty($found_photo_ids)) {
				$conditions['Photo.id'] = $found_photo_ids;
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
			$this->log($conditions, 'smart_gallery_photo_id');
			$this->log($photos_by_date_added, 'smart_gallery_photo_id');
			$found_photo_ids = Set::extract('/Photo/id', $photos_by_date_added);
		}


		// find by date taken
		if (isset($smart_settings['date_taken_from']) || isset($smart_settings['date_taken_to'])) {
			$conditions = array();
			if (!empty($found_photo_ids)) {
				$conditions['Photo.id'] = $found_photo_ids;
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