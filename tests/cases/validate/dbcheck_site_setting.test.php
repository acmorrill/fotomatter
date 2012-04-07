<?php
class DBCheckSiteSettingTestCase extends CakeTestCase {
	
	public $fixtures = array('app.site_setting', 'app.server_setting', 'app.major_error');
        
        function start() {
            parent::start();
            $this->SiteSetting = ClassRegistry::init("SiteSetting");
	    require_once(ROOT . '/app/tests/model_helpers/site_setting.test.php');
	    $this->helper = new SiteSettingTestCaseHelper();
	}
	
        function test_ensure_correct_url() {    
            $this->assertEqual($this->helper->ensure_correct_url(), true);
        }   
	
	function test_ensure_correct_secure_url() {
	    $this->assertEqual($this->helper->ensure_correct_secure_url(), true);
	}
}
?>