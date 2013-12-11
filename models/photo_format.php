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
		
		$format = $this->find('first', array(
			'conditions' => array('PhotoFormat.ref_name' => $ref_name),
			'contain' => false
		));
		
		if ($format) {
			return $format['PhotoFormat']['id'];
		} else {
			return false;
		}
	}
}