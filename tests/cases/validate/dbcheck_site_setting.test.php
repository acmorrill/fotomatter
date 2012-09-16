<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class DBCheckSiteSettingTestCase extends fototestcase {
	
	//settings: firstname lastname company_name
	
        function start() {
            parent::start();
            $this->SiteSetting = ClassRegistry::init("SiteSetting");
	    require_once(ROOT . '/app/tests/model_helpers/site_setting.test.php');
	    $this->helper = new SiteSettingTestCaseHelper();
	}
	
	function test_check_for_container_name() {
	    $this->assertEqual($this->helper->test_check_for_container_name(), true);
	}
	
        function test_ensure_correct_url() {    
            $this->assertEqual($this->helper->ensure_correct_url(), true);
        }   
	
	function test_ensure_correct_secure_url() {
	    $this->assertEqual($this->helper->ensure_correct_secure_url(), true);
	}
}
?>