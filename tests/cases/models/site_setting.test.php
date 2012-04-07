<?php
class SiteSettingTestCase extends CakeTestCase {
	
	public $fixtures = array('app.site_setting', 'app.server_setting', 'app.major_error');
        
        function start() {
            parent::start();
            $this->SiteSetting = ClassRegistry::init("SiteSetting");
	    require_once(ROOT . '/app/tests/model_helpers/site_setting.test.php');
	    $this->helper = new SiteSettingTestCaseHelper();
        }
	
	function testGetValue() {
		$this->setting_model = ClassRegistry::init('SiteSetting');
                $all_settings = $this->setting_model->find('all', array(
                    'contain'=>false
                ));
                
                foreach ($all_settings as $setting) {
                    $test_value = $this->setting_model->getVal($setting['SiteSetting']['name']);
                    $this->assertEqual($test_value, $setting['SiteSetting']['value']);
                }
	}
        
        function test_get_val_false() {
            $should_be_false = $this->SiteSetting->getVal('value_that_does_not_exist', 'return_value');
            $this->assertEqual($should_be_false == 'return_value', true);
        }
	
	
        function test_get_image_url() {
            $current_url = $this->SiteSetting->query("select value from site_settings as SiteSetting where name = 'image-container-url'");
	    if (empty($current_url)) {
		debug("The site setting, 'image-container-url' was not found in the settings table.");
	    }
            $test_current_url = $this->SiteSetting->getImageContainerUrl();
            $this->assertEqual($current_url[0]['SiteSetting']['value'] == $test_current_url, true);
        }
	
        function test_ensure_correct_url() {    
             $this->assertEqual($this->helper->ensure_correct_url(), true);
        }   
	
	function test_get_image_url_secure() {
            $current_url = $this->SiteSetting->query("select value from site_settings as SiteSetting where name = 'image-container-secure_url'");
	    if (empty($current_url)) {
		debug("The site setting, 'image-container-url' was not found in the settings table.");
	    }
	    $test_current_url = $this->SiteSetting->getImageContainerSecureUrl();
            $this->assertEqual($current_url[0]['SiteSetting']['value'] == $test_current_url, true);
        }
	
	function test_ensure_correct_secure_url() {
	    $this->assertEqual($this->helper->ensure_correct_secure_url(), true);
	}
	
	function test_empty_values() {
	    $this->SiteSetting->deleteAll(array(
		'SiteSetting.name'=>array(
		    'image-container-url',
		    'image-container-secure_url'
		)
	    ));
	    $this->assertEqual($this->SiteSetting->getImageContainerUrl()=='', true);
	    $this->assertEqual($this->SiteSetting->getImageContainerSecureUrl()=='', true);
	}
	
	function test_set_val() {
	    $result = $this->SiteSetting->setVal('test_name', 'test_value');
	    $this->assertEqual($result, true);
	    
	    $query = $this->SiteSetting->query("select value from site_settings where name = 'test_name'");
	    $this->assertEqual($query[0]['site_settings']['value']=='test_value', true);
	}
	
	function test_set_val_existing() {
	    $count = $this->SiteSetting->find('count');
	    $result = $this->SiteSetting->setVal("image-container-url", "test_value");
	    $this->assertEqual($result, true);
	    
	    $query = $this->SiteSetting->query("select value from site_settings where name = 'image-container-url'");
	    $this->assertEqual($query[0]['site_settings']['value']=='test_value', true);
	    $this->assertEqual($count == $this->SiteSetting->find('count'), true);
	}
	
	function test_set_val_return_false() {
	    $table_name = $this->SiteSetting->table;
	    $this->SiteSetting->table = null;
	    Configure::write("debug", 0);
	    $result = @$this->SiteSetting->setVal('test_name', 'test_value');
	    Configure::write("debug", 2);
	    $this->assertEqual($result, false);
	    $this->SiteSetting->table = $table_name;
	}
}
?>