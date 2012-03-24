<?php
class DBCheckServerSettingsTestCase extends CakeTestCase {
    
    public function test_rackspace_creds_exist() {
        $this->ServerSetting = ClassRegistry::init("ServerSetting");
        $username = $this->ServerSetting->getVal('rackspace_api_username', false);
        $this->assertEqual(empty($username), false);
        
        $key = $this->ServerSetting->getVal('rackspace_api_key', false);
        $this->assertEqual(empty($key), false);
    }
}