<?php
class SettingTestCase extends CakeTestCase {
	
	public $fixtures = array('app.setting');
	
	function testGetValue() {
		$this->setting_model = ClassRegistry::init('Setting');
                $all_settings = $this->setting_model->find('all', array(
                    'contain'=>false
                ));
                
                foreach ($all_settings as $setting) {
                    $test_value = $this->setting_model->getValue($setting['Setting']['name']);
                    $this->assertEqual($test_value, $setting['Setting']['value']);
                }
	}
}

?>