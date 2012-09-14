<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class UserTestCase extends fototestcase {
	
        public $include_these_tables = array('users');
        
    
        function start() {
            parent::start();
            $this->User = ClassRegistry::init("User");
	    require_once(ROOT . '/app/tests/model_helpers/user.test.php');
	    $this->helper = new UserTestCaseHelper();
        }
}	