<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class PhotoGalleryTestCase extends fototestcase {
    
	 function start() {
		parent::start();
		require_once(ROOT . "/app/tests/model_helpers/photo_gallery.test.php");
		$this->helper = new PhotoGalleryTestCaseHelper();
		$this->PhotoGallery = ClassRegistry::init("PhotoGallery");

		//init cloudfiles component
		App::import("Component", "CloudFiles");
		$this->CloudFiles = new CloudFilesComponent();
		
		//init testing componetn
		App::import("Component", "Testing");
		$this->Testing = new TestingComponent();
    }
	
	function test_add_photo_to_gallery() {
		$this->PhotoGallery->query("truncate table photo_galleries");
		$this->PhotoGallery->query("truncate table photo_galleries_photos");
		$this->PhotoGallery->query("truncate table photo_caches");
		$this->PhotoGallery->query("truncate table photos");
		
		$this->Testing->give_me_images(6);
		$this->Photo = ClassRegistry::init("Photo");
		$this->assertEqual($this->Photo->find('count'), 6);
		
		
		$this->Testing->give_me_gallery(2);
		$this->assertEqual($this->PhotoGallery->find('count'), 2);
		$all_photos = $this->Photo->find('all', array(
			'contain'=>false
		));
		
		foreach ($all_photos as $photo) {
			$gallery_id = rand(1,2);
			$this->PhotoGallery->add_photo_to_gallery($photo['Photo']['id'], $gallery_id);
		}
		
		require_once(ROOT . "/app/tests/model_helpers/photo_galleries_photo.test.php");
		$this->helperPhoto = new PhotoGalleriesPhotoTestCaseHelper();
		$this->assertEqual($this->helperPhoto->check_order(), true);
		
	}
}