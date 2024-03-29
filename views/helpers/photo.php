<?php

class PhotoHelper extends AppHelper {

	public $helpers = array('Html');

	
	
	
	
	/**
	 * 	If can't find the function try to call on photo model
	 * 
	 * @param type $method_name
	 * @param type $args
	 * @return type 
	 */
	function __call($method_name, $args) {
		$this->Photo = ClassRegistry::init('Photo');

		return call_user_func_array(array($this->Photo, $method_name), $args);
	}

	
	public function get_photo_html_title_str($curr_photo, $curr_gallery) {
		$view_photo_title = '';
		if (!empty($curr_photo['Photo']['display_title'])) {
			$view_photo_title .= $curr_photo['Photo']['display_title'] . " &mdash; ";
		}
		if (!empty($curr_photo['Photo']['display_subtitle'])) {
			$view_photo_title .= $curr_photo['Photo']['display_subtitle'] . " &mdash; ";
		}
		if (!empty($curr_gallery['PhotoGallery']['display_name'])) {
			$view_photo_title .= $curr_gallery['PhotoGallery']['display_name'] . " &mdash; ";
		}
		
		return $view_photo_title;
	}
	
	
	public function add_photo_format(&$photos) {
		$this->Photo = ClassRegistry::init('Photo');

		return $this->Photo->add_photo_format($photos);
	}

	public function get_prev_image_web_path($photo_id, $gallery_id) {
		return $this->_get_image_neighbor_web_path($photo_id, $gallery_id, -1);
	}

	public function get_next_image_web_path($photo_id, $gallery_id) {
		return $this->_get_image_neighbor_web_path($photo_id, $gallery_id, 1);
	}

	private function _get_image_neighbor_web_path($photo_id, $gallery_id, $order_modifier) {
		if (!isset($this->PhotoGalleriesPhoto)) {
			$this->PhotoGalleriesPhoto = ClassRegistry::init('PhotoGalleriesPhoto');
		}
		if (!isset($this->Photo)) {
			$this->Photo = ClassRegistry::init('Photo');
		}


		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// return the photo gallery url if the photo is out of  the limit
		$max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
		if (!empty($max_photo_id) && $photo_id > $max_photo_id) {
			return $this->Html->url(array(
						'controller' => 'photo_galleries',
						'action' => 'view_gallery',
						$gallery_id
			));
		}


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


		////////////////////////////////////////////////////////////////////////////////////////////////////
		// FILTER OUT ANY NON VIEWABLE PHOTOS BASED ON LIMIT
		$max_photo_extra_condition = '';
		if (!empty($max_photo_id)) {
			$max_photo_extra_condition = "PhotoGalleriesPhoto.photo_id <= $max_photo_id";
		}
		if ($order_modifier > 0) { // FIND NEXT PHOTO BASED ON ORDER THAT IS NOT LIMITED
			$prev_gallery_photo = $this->PhotoGalleriesPhoto->find('first', array(
				'conditions' => array(
					'PhotoGalleriesPhoto.photo_order >' => ((int) $photo_galleries_photo['PhotoGalleriesPhoto']['photo_order']),
					'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id,
					$max_photo_extra_condition
				),
				'order' => array(
					'PhotoGalleriesPhoto.photo_order ASC'
				),
				'contain' => false
			));
		} else { // FIND PREV PHOTO BASED ON ORDER THAT IS NOT LIMITED
			$prev_gallery_photo = $this->PhotoGalleriesPhoto->find('first', array(
				'conditions' => array(
					'PhotoGalleriesPhoto.photo_order <' => ((int) $photo_galleries_photo['PhotoGalleriesPhoto']['photo_order']),
					'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id,
					$max_photo_extra_condition
				),
				'order' => array(
					'PhotoGalleriesPhoto.photo_order DESC'
				),
				'contain' => false
			));
		}

		if (!isset($prev_gallery_photo['PhotoGalleriesPhoto']['photo_id'])) {
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
//						'ps' => $dynamic_photo_size,
						$prev_gallery_photo['PhotoGalleriesPhoto']['photo_id']
			));
		}
	}


}
