<?php
class PhotoFormat extends AppModel {
	public $name = 'PhotoFormat';
	public $displayField = 'display_name';
	
	public function get_photo_format_id($height, $width) {
		$ref_name = 'landscape';
		$pano_ratio = 2;
		
		if ( $width > ($height*$pano_ratio) ) {
			$ref_name = 'panoramic';
		} else if ( $width > $height ) {
			$ref_name = 'landscape';
		} else if ( $height > ($width*$pano_ratio) ) {
			$ref_name = 'vertical_panoramic';
		} else if ( $height > $width ) {
			$ref_name = 'portrait';
		} else if ( $height == $width ) {
			$ref_name = 'square';
		}
		
		$photo_formats = $this->get_photo_formats_keyed_by_ref_name();
		if (!empty($photo_formats[$ref_name]['PhotoFormat']['id'])) {
			return $photo_formats[$ref_name]['PhotoFormat']['id'];
		} else {
			return false;
		}
	}
	
	
	public function get_photo_formats() {
		$apc_key = "global_app_photo_formats";
		if (apc_exists($apc_key)) {
			return apc_fetch($apc_key);
		}
		
		$photo_formats = $this->find('all', array(
			'contain' => false
		));
		apc_store($apc_key, $photo_formats, 604800);
		
		return $photo_formats;
	}
	
	public function get_photo_formats_keyed_by_ref_name() { 
		$photo_formats = $this->get_photo_formats();
		return Set::combine($photo_formats, '{n}.PhotoFormat.ref_name', '{n}');
	}
	
	public function get_photo_format_ids_by_ref_names($ref_names) {
		$photo_formats = $this->get_photo_formats_keyed_by_ref_name();
		$photo_format_ids = array();
		foreach ($ref_names as $ref_name) {
			$photo_format_ids[] = $photo_formats[$ref_name]['PhotoFormat']['id'];
		}
		return $photo_format_ids;
	}
}