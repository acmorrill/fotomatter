<?php
class DBCheckTagTestCase extends CakeTestCase {
	
    public $fixtures = array('app.photo', 'app.photo_gallery', 'app.photo_galleries_photo', 'app.major_error', 'app.user', 
	'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.site_setting', 'app.server_setting', 'app.photo_format',
	    'app.photo_cache');

    function start() {
	parent::start();
	require_once(ROOT . "/app/tests/model_helpers/tag.test.php");
	$this->helper = new TagTestCaseHelper();
	$this->Photo = ClassRegistry::init("Photo");
    }
}
?>