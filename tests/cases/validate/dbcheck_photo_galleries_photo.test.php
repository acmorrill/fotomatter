<?php
class DBCheckPhotoGalleryPhotoTestCase extends CakeTestCase {
    
    public $fixtures = array('app.photo', 'app.tag', 'app.photos_tag', 'app.major_error', 'app.site_setting',
        'app.server_setting', 'app.photo_format', 'photo_cache', 'photo_galleries_photo'
    );
	
	 function start() {
		parent::start();
		require_once(ROOT . "/app/tests/model_helpers/photo_galleries_photo.test.php");
		$this->helper = new PhotoGalleriesPhotoTestCaseHelper();
		$this->PhotoGalleryPhoto = ClassRegistry::init("PhotoGallery");

		//init cloudfiles component
		App::import("Component", "CloudFiles");
		$this->CloudFiles = new CloudFilesComponent();
		
		//init testing componetn
		App::import("Component", "Testing");
		$this->Testing = new TestingComponent(); 
    }
	
	function test_check_order() {
		$this->assertEqual($this->helper->check_order(), true);
	}
	
	
	
}