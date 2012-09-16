<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class DBCheckPhotoGalleryTestCase extends fototestcase {
    
	 function start() {
        parent::start();
	    require_once(ROOT . "/app/tests/model_helpers/photo_gallery.test.php");
	    $this->helper = new PhotoGalleryTestCaseHelper();
    }
	
	function test_check_order() {
		//$this->assertEqual($this->helper->check_order(), true);
	}
	
	
	
}