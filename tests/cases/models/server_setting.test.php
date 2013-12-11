<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class ServerSettingTestCase extends fototestcase {
    
    function start() {
	parent::start();
	require_once(ROOT . "/app/tests/model_helpers/server_setting.test.php");
	$this->helper = new ServerSettingTestCaseHelper();
	$this->ServerSetting = ClassRegistry::init("ServerSetting");
    }
    
    public function test_check_for_container_name() {
	$this->assertEqual($this->helper->test_check_for_container_name(), true);
    }
    

    public function test_rackspace_creds_exist() {
	$this->assertEqual($this->helper->rackspace_creds_exist(), true);
    }
	
    public function test_get_val() {
	$api_username = $this->ServerSetting->getVal('rackspace_api_username');
	$this->assertEqual(empty($api_username), false);
    }
    
    public function test_get_val_nonexist() {
	$api_username = $this->ServerSetting->getVal('rrackspace_api_username'); //notice the two rs, does not exist
	$this->assertEqual(empty($api_username), true);
    }
    
    public function test_get_val_nonexist_returnnonfalse() {
	$api_username = $this->ServerSetting->getVal('rrackspace_api_username', 'test_value'); //notice the two rs, does not exist
	$this->assertEqual($api_username, 'test_value');
    }
    
    public function test_set_value() {
	$test_value = $this->_create_random_string(10);
	$test_name = $this->_create_random_string(10);
	$this->assertEqual($this->ServerSetting->setVal($test_name, $test_value), true);
	
	$value_from_db = $this->ServerSetting->query("select value from server_settings where name='$test_name'");
	$this->assertEqual($value_from_db[0]['server_settings']['value'], $test_value);
    }
    
    public function test_set_value_already_exists() {
	$before_count = $this->ServerSetting->find('count');
	$this->assertEqual($this->ServerSetting->setVal('rackspace_api_username', 'a'), true);
	
	$value_from_db = $this->ServerSetting->query("select value from server_settings where name='rackspace_api_username'");
	$this->assertEqual($value_from_db[0]['server_settings']['value'], 'a');
	$after_count = $this->ServerSetting->find('count');
	$this->assertEqual($before_count, $after_count);
    }
    
    public function test_save_return_false() {
	$table = $this->ServerSetting->table;
	$this->ServerSetting->table = NULL;
	Configure::write('debug', 0);
	$this->assertEqual($this->ServerSetting->setVal('rackspace_api_username', 'a'), false);
	Configure::write('debug', 2);
	$this->ServerSetting->table = $table;
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