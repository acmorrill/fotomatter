<?php
class DBCheckPhotoSettingTestCase extends CakeTestCase {
	
    public $fixtures = array('app.photo', 'app.photo_gallery', 'app.photo_galleries_photo', 'app.major_error', 'app.user', 
	'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.site_setting', 'app.server_setting', 'app.photo_format',
	    'app.photo_cache');

    function start() {
	parent::start();
	require_once(ROOT . "/app/tests/model_helpers/photo.test.php");
	$this->helper = new PhotoTestCaseHelper();
	$this->Photo = ClassRegistry::init("Photo");
    }
    
    public function test_check_consistent_values() {
	$this->assertEqual($this->helper->check_for_consistent_values(), true);
    }
    
    private function _create_random_string($length) {
        $lib = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $return = '';
        for ($i=0;$i < $length; $i++) {
            $return .= $lib[rand(0, strlen($lib)-1)];
        }
        return $return; 
    }
}
?>