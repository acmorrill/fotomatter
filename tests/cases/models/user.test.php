<?php
class UserTestCase extends CakeTestCase {
	
	public $fixtures = array('app.user', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user');
        
        function start() {
            parent::start();
            $this->User = ClassRegistry::init("User");
	    require_once(ROOT . '/app/tests/model_helpers/user.test.php');
	    $this->helper = new UserTestCaseHelper();
        }
}	