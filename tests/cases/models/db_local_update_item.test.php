<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class DbLocalUpdateItemTestCase extends fototestcase {

	function start() {
		require_once(ROOT . '/app/tests/model_helpers/db_local_update_item.test.php');
		$this->helper = new DbLocalUpdateItemTestCaseHelper();
		$this->DbLocalUpdateItemTestCase = ClassRegistry::init('DbLocalUpdateItemTestCase');
		$this->_run_validate_functions($this->helper);
	}

}