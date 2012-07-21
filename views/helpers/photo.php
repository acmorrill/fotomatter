<?php


class PhotoHelper extends AppHelper {
	
	public function get_dummy_error_image_path($height, $width) {
		$this->PhotoCache = ClassRegistry::init('PhotoCache');
		
		return $this->PhotoCache->get_dummy_error_image_path($height, $width);
	}
	
	public function get_photo_path($photo_id, $height, $width) {
		$this->Photo = ClassRegistry::init('Photo');
		
		return $this->Photo->get_photo_path($photo_id, $height, $width);
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