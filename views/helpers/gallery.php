<?php
class GalleryHelper extends AppHelper {
	
	public function get_all_galleries() {
		$this->PhotoGallery = ClassRegistry::init("PhotoGallery");
		return $this->PhotoGallery->find('all', array(
			'contain'=>false
		));
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