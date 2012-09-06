<?php
class DBCheckTagTestCase extends CakeTestCase {
	
    public $fixtures = array('app.photo', 'app.tag', 'app.photos_tag', 'app.major_error', 'app.site_setting',
        'app.server_setting', 'app.photo_format', 'photo_cache', 'photo_galleries_photo'
    );

    function start() {
	parent::start();
	require_once(ROOT . "/app/tests/model_helpers/tag.test.php");
	$this->helper = new TagTestCaseHelper();
	$this->Photo = ClassRegistry::init("Photo");
    }
}
?>