<?php

/**
 * TESTING NOTES 
 *	--- there shouldn't be any values for gallery or photos that don't exist
 *	--- the order in relation to a gallery should always be valid (no gaps) -- even on delete of photos or galleries
 *  --- there should be no orders with value of 0
 *  --- order should always start with 1
 */
class PhotoGalleriesPhoto extends AppModel {
	public $name = 'PhotoGalleriesPhoto';
	public $belongsTo = array(
		'Photo',
		'PhotoGallery'
	);
	public $actsAs = array(
		'Ordered' => array(
			'field' => 'photo_order',
			'foreign_key' => 'photo_gallery_id'
		)
	);
	
	public function get_gallery_photos_ids_by_weight($gallery_id, $limit = null, $actually_grab_photos = false) {
		$max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
		$max_photo_extra_condition = '';
		if (!empty($max_photo_id)) {
			$max_photo_extra_condition = "PhotoGalleriesPhoto.photo_id <= $max_photo_id";
		}
		
		$contain_arr = false;
		if ($actually_grab_photos == true) {
			$contain_arr = array(
				'Photo'
			);
		}
		
		$photo_gallery_photos = $this->find('all', array(
			'conditions' => array(
				'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id,
				$max_photo_extra_condition
			),
			'order' => array(
				'PhotoGalleriesPhoto.photo_order ASC'
			),
			'limit' => $limit,
			'contain' => $contain_arr // DREW TODO - maybe need to make this more efficient
		));
		
		if ($actually_grab_photos == true) {
			$this->Photo->add_photo_format($photo_gallery_photos);
			return $photo_gallery_photos;
		}
		
		$photo_gallery_ids = Set::extract('/PhotoGalleriesPhoto/photo_id', $photo_gallery_photos);
		
		return $photo_gallery_ids;
	}
	
}