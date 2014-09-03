<?php
class GalleryHelper extends AppHelper {
	
	function __call($method_name, $args) {
		$this->PhotoGallery = ClassRegistry::init('PhotoGallery');
		
		return call_user_func_array(array($this->PhotoGallery, $method_name), $args);
    }
	
	public function get_all_galleries() {
		$this->PhotoGallery = ClassRegistry::init("PhotoGallery");
		return $this->PhotoGallery->find('all', array(
			'contain' => false
		));
	}
	
	public function count_gallery_photos($photo_gallery) {
		$count = 0;
		
		if ($photo_gallery['PhotoGallery']['type'] == 'standard') {
			$this->PhotoGalleriesPhoto = ClassRegistry::init("PhotoGalleriesPhoto");
			$count = $this->PhotoGalleriesPhoto->find('count', array(
				'conditions' => array(
					'PhotoGalleriesPhoto.photo_gallery_id' => $photo_gallery['PhotoGallery']['id']
				),
				'contain' => false
			));
		} else if ($photo_gallery['PhotoGallery']['type'] == 'smart') {
			$this->PhotoGallery = ClassRegistry::init("PhotoGallery");
			
			$photo_ids = $this->PhotoGallery->get_smart_gallery_photo_ids($photo_gallery['PhotoGallery']['smart_settings']);
			
			$count = count($photo_ids);
		}
		
		return $count;
	}
	
	public function get_gallery_landing_image($gallery_id) { 
		$this->PhotoGalleriesPhoto = ClassRegistry::init("PhotoGalleriesPhoto");
		$this->Photo = ClassRegistry::init("Photo");
		
		
		// DREW TODO - make it so the user can choose the gallery landing photo
		
		
		// find the first photo in the gallery
		$PhotoGalleriesPhoto = $this->PhotoGalleriesPhoto->find('first', array(
			'conditions' => array(
				'PhotoGalleriesPhoto.photo_gallery_id' => $gallery_id
			),
			'contain' => array(
				'Photo'
			),
			'order' => 'PhotoGalleriesPhoto.photo_order ASC',
		));
		
		
		
		// so just find the first photo amount photos
		if (empty($PhotoGalleriesPhoto)) {
			$this->Photo = ClassRegistry::init("Photo");
			$PhotoGalleriesPhoto = $this->Photo->find('first', array(
				'contain' => false
			));
		}
		
		
		// means there are no images on the system
		if (empty($PhotoGalleriesPhoto)) {
			// DREW TODO - return a blank image in this case
		}
		
		
		return $PhotoGalleriesPhoto;
	}
	
	public function get_first_gallery() {
		$this->PhotoGallery = ClassRegistry::init("PhotoGallery");
		return $this->PhotoGallery->find('first', array(
			'contain' => false,
			'order' => array(
				'PhotoGallery.weight'
			)
		));
	}
	
	public function get_gallery_photos($photo_gallery_id, $limit) {
		$this->PhotoGalleriesPhoto = ClassRegistry::init("PhotoGalleriesPhoto");
		
		
		$get_photos_query = "
			SELECT *
			FROM photo_galleries_photos AS PhotoGalleriesPhoto
			LEFT JOIN
				photos AS Photo
				ON Photo.id = PhotoGalleriesPhoto.photo_id
			LEFT JOIN
				photo_formats AS PhotoFormat
				ON PhotoFormat.id = Photo.photo_format_id
			WHERE 
				PhotoGalleriesPhoto.photo_gallery_id = '$photo_gallery_id'
			ORDER BY
				PhotoGalleriesPhoto.photo_order
			LIMIT $limit
		";
		$photos = $this->PhotoGalleriesPhoto->query($get_photos_query);
		
		foreach ($photos as $key => $photo) {
			$photos[$key]['Photo']['PhotoFormat'] = $photos[$key]['PhotoFormat'];
			unset($photos[$key]['PhotoFormat']);
		}

		return $photos;
	}
}