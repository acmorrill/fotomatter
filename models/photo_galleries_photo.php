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
	
	public function get_gallery_photos_ids_by_weight($gallery_id, $limit = null) {
		$photo_gallery_photos = $this->find('all', array(
			'conditions' => array(
				'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id,
			),
			'order' => array(
				'PhotoGalleriesPhoto.photo_order ASC'
			),
			'limit' => $limit,
			'contain' => false
		));
		
		$photo_gallery_ids = Set::extract('/PhotoGalleriesPhoto/photo_id', $photo_gallery_photos);
		
		return $photo_gallery_ids;
	}
	
}