<?php
class GalleryHelper extends AppHelper {
	public function get_all_galleries() {
		$this->PhotoGallery = ClassRegistry::init("PhotoGallery");
		return $this->PhotoGallery->find('all', array(
			'contain'=>false
		));
	}
}