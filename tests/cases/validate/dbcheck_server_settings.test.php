<?php
class DBCheckServerSettingsTestCase extends CakeTestCase {
    
    var $fixtures = array('app.server_setting');
    
    /* 
     * Test case... 
     * 1. For everyphoto if there is a cdn filename, there should be a cdn_filename for cache, pixel width, pixel height, for cache height, for cache width
     * 2. If it has a cdn filename... make sure there is a corresponding entry in cloud files
     * 3. For photo caches... should always have a cdn_filename... all dimensions null or set and not 0
     * 4. If the status is qued or processing and its older than a day... fail
     * 5. photocaches === cloud files
     * 6. gallery... ordering make sure consitent (maybe there might be a validate)
     * 7. 
     */
    
    public function test_rackspace_creds_exist() {
        $this->ServerSetting = ClassRegistry::init("ServerSetting");
        $username = $this->ServerSetting->getVal('rackspace_api_username', false);
        $this->assertEqual(empty($username), false);
        
        $key = $this->ServerSetting->getVal('rackspace_api_key', false);
        $this->assertEqual(empty($key), false);
    }
}