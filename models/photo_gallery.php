<?php
class PhotoGallery extends AppModel {
	public $name = 'PhotoGallery';
	public $hasAndBelongsToMany = array(
		'Photo'
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
}