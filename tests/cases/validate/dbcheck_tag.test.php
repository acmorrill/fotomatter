<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class DBCheckTagTestCase extends fototestcase {
    
    function start() {
	parent::start();
	require_once(ROOT . "/app/tests/model_helpers/tag.test.php");
	$this->helper = new TagTestCaseHelper();
	$this->Photo = ClassRegistry::init("Photo");
    }
}
?>