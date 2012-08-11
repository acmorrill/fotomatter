<?php
require_once(ROOT.DS.'app'.DS.'tests'.DS.'model_helpers'.DS.'model_helper_obj.php');
class PhotoGalleriesPhotoTestCaseHelper extends ModelHelperObj {
    
    function __construct() {
		$this->PhotoGalleriesPhoto = ClassRegistry::init("PhotoGalleriesPhoto");
    }
	
	function check_order() {
		$all_photos = $this->PhotoGalleriesPhoto->find('all', array(
			'contain'=>false,
			'group'=>'PhotoGalleriesPhoto.photo_gallery_id'
		));
		$galleries_checked = array();
		foreach ($all_photos as $photo) {
			$photos_for_gallery = $this->PhotoGalleriesPhoto->find('all', array(
				'contain'=>false,
				'conditions'=>array(
					'PhotoGalleriesPhoto.photo_gallery_id'=>$photo['PhotoGalleriesPhoto']['photo_gallery_id']
				)
			));
			$gallery_orders = Set::extract("/PhotoGalleriesPhoto/photo_order", $photos_for_gallery);
			foreach ($gallery_orders as $key=>$value) {
				if ($key != ($value - 1)) {
					$this->_record_real_error("Order for PhotoGalleries are not correct", $photo);
					return false;
				}
			}
		}
		return true;
	}
}