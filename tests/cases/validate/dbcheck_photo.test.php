<?php
class DBCheckPhotoSettingTestCase extends CakeTestCase {
	
    public $fixtures = array('app.photo', 'app.tag', 'app.photos_tag', 'app.major_error', 'app.site_setting',
        'app.server_setting', 'app.photo_format', 'photo_cache', 'photo_galleries_photo'
    );

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