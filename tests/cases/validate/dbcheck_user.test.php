<?php
class DBCheckUserTestCase extends CakeTestCase {
	
    
        public $fixtures = array('app.user', "app.group_permission");
    
        function start() {
            parent::start();
            $this->User = ClassRegistry::init("User");
	    require_once(ROOT . '/app/tests/model_helpers/user.test.php');
	    $this->helper = new UserTestCaseHelper();
	}
}
?>