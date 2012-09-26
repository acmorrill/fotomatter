<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class DbLocalUpdateTestCase extends fototestcase {

	function start() {
		require_once(ROOT . '/app/tests/model_helpers/db_local_update.test.php');
		$this->helper = new DbLocalUpdateTestCaseHelper();
		$this->DbLocalUpdateTestCase = ClassRegistry::init('DbLocalUpdateTestCase');
		$this->_run_validate_functions($this->helper);
	}

}