<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class RackspaceTestCase extends fototestcase {
    
	function start() {
                //can't instantiate rackspace obj directly as its not a component
		App::Import("Component", "CloudFiles");
		$this->CloudFiles = new CloudFilesComponent();
                parent::start();
	}
        
        public function test_credentials_missing() { 
            $this->ServerSetting = ClassRegistry::init("ServerSetting");
            $this->ServerSetting->deleteAll(array(
                'ServerSetting.name'=>array('rackspace_api_username', 'rackspace_api_key')
            ));
            $this->assertEqual($this->CloudFiles->test_authenticate(), false);
            
            //check for major error
            $this->MajorError = ClassRegistry::init("MajorError");
            $error = $this->MajorError->find('first', array(
                'conditions'=>array(
                    'MajorError.description'=>'Rackspace credentials missing.'
                )
            ));
            $this->assertEqual(empty($error), false);
         }
         
         public function test_credentials_wrong() {
             $this->ServerSetting = ClassRegistry::init("ServerSetting");
             $this->ServerSetting->query("UPDATE server_settings set value = 'a' where name = 'rackspace_api_username'");
              $this->assertEqual($this->CloudFiles->test_authenticate(), false);
            
            //check for major error
            $this->MajorError = ClassRegistry::init("MajorError");
            $error = $this->MajorError->find('first', array(
                'conditions'=>array(
                    'MajorError.description'=>'Rackspace credentials invalid'
                )
            ));
            $this->assertEqual(empty($error), false);
         }
        
        public function test_auth_no_problems() {
            $this->assertEqual($this->CloudFiles->test_authenticate(), true);
        }
        
        
        public function test_api_not_authenticated() {
            unset($this->CloudFiles); //cause reauthenticate but fail...
            $this->CloudFiles = new CloudFilesComponent();
            $this->ServerSetting = ClassRegistry::init("ServerSetting");
            $this->ServerSetting->query("UPDATE server_settings set value = 'a' where name = 'rackspace_api_username'");
            $this->list = $this->CloudFiles->list_objects("MichelleCellPhone");
            $this->assertEqual($this->list==false, true);
        }
         
        public function test_api_call() {
            $this->list = $this->CloudFiles->list_objects("MichelleCellPhone");
            $this->assertEqual(empty($this->list), false);
            
            unset($this->CloudFiles); //we need to try the same method not authenticated
            $this->CloudFiles = new CloudFilesComponent();
            $this->list = $this->CloudFiles->list_objects("MichelleCellPhone");
            $this->assertEqual(empty($this->list), false);
        }
        
        public function test_detail() {
            $result = $this->CloudFiles->detail_object($this->list[0]['name'], 'MichelleCellPhone');
            $this->assertEqual(empty($result), false);
        }
        
        public function test_detail_notAuthenticated() {
            unset($this->CloudFiles); //cause reauthenticate but fail...
            $this->CloudFiles = new CloudFilesComponent();
            $this->ServerSetting = ClassRegistry::init("ServerSetting");
            $this->ServerSetting->query("UPDATE server_settings set value = 'a' where name = 'rackspace_api_username'");
            $result = $this->CloudFiles->detail_object($this->list[0]['name'], 'MichelleCellPhone');
            $this->assertEqual(empty($result), true);
        }
        
        public function test_detail_notAuthenticated_will_work() {
            unset($this->CloudFiles); //cause reauthenticate but fail...
            $this->CloudFiles = new CloudFilesComponent();
            $result = $this->CloudFiles->detail_object($this->list[0]['name'], 'MichelleCellPhone');
            $this->assertEqual(empty($result), false);
        }
        
        public function test_put_action() {
            //need to test something that requires extra http headers
            $image = $this->CloudFiles->get_object($this->list[0]['name'], 'MichelleCellPhone');
            file_put_contents(TEMP_IMAGE_PATH .DS. 'test_image', $image);
            $temp_container = $this->_create_random_string(10);
            $result =  $this->CloudFiles->create_container($temp_container);
            $this->assertEqual($result, true);
            $result = $this->CloudFiles->put_object('test_image', TEMP_IMAGE_PATH.DS.'test_image', 'image/jpeg', $temp_container);
            $this->assertEqual($result, true);
            
            $this->CloudFiles->delete_container($temp_container);
            $this->assertEqual($result, true);
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
