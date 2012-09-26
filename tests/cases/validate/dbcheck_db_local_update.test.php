<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class DBCheckDbLocalUpdateTestCase extends fototestcase {

	function start() {
		require_once(ROOT . '/app/tests/model_helpers/db_local_update.test.php');
		$this->helper = new DbLocalUpdateTestCaseHelper();
		$this->DbLocalUpdate = ClassRegistry::init('DbLocalUpdate');
		$this->_run_validate_functions($this->helper);
	}

}