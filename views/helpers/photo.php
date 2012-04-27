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
		$this->Photo = ClassRegistry::init('Photo');
		$this->PhotoCache = ClassRegistry::init('PhotoCache');
		$masterCacheSize = LARGE_MASTER_CACHE_SIZE;
		
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
			return $this->Photo->get_full_path($photo_id);
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
		
		$initLocked = $this->Photo->query("SELECT GET_LOCK('finish_create_cache_".$photo_id."', 8)");
		if ($initLocked['0']['0']["GET_LOCK('finish_create_cache_".$photo_id."', 8)"] == 0 || $initLocked['0']['0']["GET_LOCK('finish_create_cache_".$photo_id."', 8)"] == null) {
			return $this->PhotoCache->get_dummy_processing_image_path($height, $width);
		}
		$photoCache = $this->PhotoCache->find('first', array(
			'conditions' => $conditions
		));

		$return_url = '';
		if ( $photoCache && $photoCache['PhotoCache']['status'] == 'ready' ) {
			$return_url = $this->PhotoCache->get_full_path($photoCache['PhotoCache']['id']);
		} else if ( $photoCache && $photoCache['PhotoCache']['status'] == 'processing' ) {
			$return_url = $this->PhotoCache->get_dummy_processing_image_path($height, $width);
		} else if ( $photoCache && $photoCache['PhotoCache']['status'] == 'queued' ) {
			// TODO - maybe return the prepare path if the status is queued and some time has passed
			// I don't think I need to do the TODO now that I've added locking to the finish create cache and this helper
			$return_url = $this->PhotoCache->get_existing_cache_create_url($photoCache['PhotoCache']['id']);
		} else {
			$return_url = $this->PhotoCache->prepare_new_cachesize($photo_id, $height, $width);
		}
		
		$releaseLock = $this->Photo->query("SELECT RELEASE_LOCK('finish_create_cache_".$photo_id."')");
		return $return_url;
	}
	
	public function get_admin_photo_icon_size($not_in_gallery_icon_size) {
		// figure out icon sizes
		$height = 110;
		$width = 110;
		if ($not_in_gallery_icon_size == 'small') {
			$height = 60;
			$width = 60;
		} else if ($not_in_gallery_icon_size == 'medium') {
			$height = 110;
			$width = 110;
		} else if ($not_in_gallery_icon_size == 'large') {
			$height = 155;
			$width = 155;
		}
		
		return array(
			'height' => $height,
			'width' => $width
		);
	}
		
	
}