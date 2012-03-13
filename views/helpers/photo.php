<?php
class PhotoHelper extends AppHelper {
	
	public function get_photo_path($photo_id, $height = null, $width = null) {
		// make sure height/width values are valid
		if ($height <= 0) {
			$height = null;
		}
		if ($width <= 0) {
			$width = null;
		}
		$returnPath = '';
		$this->Photo = ClassRegistry::init('Photo');
		$this->PhotoCache = ClassRegistry::init('PhotoCache');
		$masterCacheSize = 1500; // TODO this should be a global setting
		
		$heightSet = isset($height);
		$widthSet = isset($width);
		
		
		/////////////////////////////////
		// get the photo
		$the_photo = $this->Photo->find('first', array(
			'conditions' => array('Photo.id' => $photo_id),
			'contain' => false
		));
		
		
		
		// check to make sure the photo has a file attached 
		if ( empty($the_photo['Photo']['cdn-filename-forcache']) || empty($the_photo['Photo']['cdn-filename']) ) {
			return $this->PhotoCache->get_dummy_error_image_path($height, $width);
		}
		

		$bothEmpty = empty($height) && empty($width);
		$onlyWidth = !empty($width) && empty($height);
		$onlyHeight = empty($width) && !empty($height);
		$bothSet = !empty($width) && !empty($height);
		
		// return the full photo path
		if ($bothEmpty) {
			$returnPath = $this->Photo->get_full_path($photo_id);
		} 
		// get a cache smaller than width
		else if ($onlyWidth) {
			$conditions = array(
				'PhotoCache.photo_id' => $photo_id,
				'PhotoCache.max_width' => $width,
				'PhotoCache.max_height IS NULL'
			);
		} 
		// get a cache smaller than height
		else if ($onlyHeight) {
			$conditions = array(
				'PhotoCache.photo_id' => $photo_id,
				'PhotoCache.max_height' => $height,
				'PhotoCache.max_width IS NULL'
			);
		} 
		// get a cache smaller than width and height
		else if ($bothSet) {
			$conditions = array(
				'PhotoCache.photo_id' => $photo_id,
				'PhotoCache.max_height' => $height,
				'PhotoCache.max_width' => $width
			);
		}
		
		
		$photoCache = $this->PhotoCache->find('first', array(
			'conditions' => $conditions
		));

		
		if ( $photoCache && $photoCache['PhotoCache']['status'] == 'ready' ) {
			return $this->PhotoCache->get_full_path($photoCache['PhotoCache']['id']);
		} else if ( $photoCache && ( $photoCache['PhotoCache']['status'] == 'queued' ||  $photoCache['PhotoCache']['status'] == 'processing' ) ) {
			// TODO - maybe return the prepare path if the status is queued and some time has passed
			return $this->PhotoCache->get_dummy_processing_image_path($height, $width);
		} else {
			return $this->PhotoCache->prepare_new_cachesize($photo_id, $height, $width);
		}
		
	}
		
	
}