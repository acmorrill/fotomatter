<?php
class DBCheckPhotoGalleryTestCase extends CakeTestCase {
    
    var $fixtures = array('app.server_setting', 'app.major_error', 'app.user', 'app.group', 'app.permission', 'app.groups_permission',
	'app.groups_user', 'app.site_setting');
	
	 function start() {
        parent::start();
	    require_once(ROOT . "/app/tests/model_helpers/photo_gallery.test.php");
	    $this->helper = new PhotoGalleryTestCaseHelper();
    }
	
	function test_check_order() {
		$this->assertEqual($this->helper->check_order(), true);
	}
	
	
	
}