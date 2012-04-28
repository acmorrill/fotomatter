<?php
class PhotoGalleriesPhotoTestCase extends CakeTestCase {
	
	public $fixtures = array('app.photo', 'app.photo_gallery', 'app.photo_galleries_photo', 'app.major_error', 'app.user', 
	'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.site_setting', 'app.server_setting', 'app.photo_format',
	    'app.photo_cache');
	
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