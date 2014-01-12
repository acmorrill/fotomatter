<?php


class PhotoHelper extends AppHelper {
	public $helpers = array('Html');
	
	
	/**
	 *	If can't find the function try to call on photo model
	 * 
	 * @param type $method_name
	 * @param type $args
	 * @return type 
	 */
	function __call($method_name, $args) {
		$this->Photo = ClassRegistry::init('Photo');
		
		return call_user_func_array(array($this->Photo, $method_name), $args);
    }
	
	public function add_photo_format(&$photos) {
		$this->Photo = ClassRegistry::init('Photo');
		
		return $this->Photo->add_photo_format($photos);
	}
	
	public function get_dummy_error_image_path($height, $width) {
		$this->PhotoCache = ClassRegistry::init('PhotoCache');
		
		return $this->PhotoCache->get_dummy_error_image_path($height, $width);
	}
	
		
	public function get_prev_image_web_path($photo_id, $gallery_id) {
		return $this->_get_image_neighbor_web_path($photo_id, $gallery_id, -1);
	}
	
	public function get_next_image_web_path($photo_id, $gallery_id) {
		return $this->_get_image_neighbor_web_path($photo_id, $gallery_id, 1);
	}
	
	private function _get_image_neighbor_web_path($photo_id, $gallery_id, $order_modifier) {
		$this->PhotoGalleriesPhoto = ClassRegistry::init('PhotoGalleriesPhoto');
		
		$photo_galleries_photo = $this->PhotoGalleriesPhoto->find('first', array(
			'conditions' => array(
				'PhotoGalleriesPhoto.photo_id' => $photo_id,
				'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id
			), 
			'contain' => false
		));
		
		
		if (!isset($photo_galleries_photo['PhotoGalleriesPhoto']['photo_order'])) {
			return $this->Html->url(array(    
				'controller' => 'photo_galleries',    
				'action' => 'view_gallery',    
				$gallery_id
			));
		}
		
		$prev_gallery_photo = $this->PhotoGalleriesPhoto->find('first', array(
			'conditions' => array(
				'PhotoGalleriesPhoto.photo_order' => ((int)$photo_galleries_photo['PhotoGalleriesPhoto']['photo_order']) + $order_modifier,
				'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id
			), 
			'contain' => false
		));
		
		if (!isset($prev_gallery_photo['PhotoGalleriesPhoto']['photo_id'])) {
			// DREW TODO - make it so this code will return the first page for previous but the last page for next
			return $this->Html->url(array(    
				'controller' => 'photo_galleries',    
				'action' => 'view_gallery',    
				$gallery_id
			));
		} else {
			return $this->Html->url(array(    
				'controller' => 'photos',    
				'action' => 'view_photo',
				'gid' => $gallery_id,
				$prev_gallery_photo['PhotoGalleriesPhoto']['photo_id']
			));
		}
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