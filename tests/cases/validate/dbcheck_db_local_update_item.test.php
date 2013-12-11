<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class DBCheckDbLocalUpdateItemTestCase extends fototestcase {

	function start() {
		require_once(ROOT . '/app/tests/model_helpers/db_local_update_item.test.php');
		$this->helper = new DbLocalUpdateItemTestCaseHelper();
		$this->DbLocalUpdateItem = ClassRegistry::init('DbLocalUpdateItem');
		$this->_run_validate_functions($this->helper);
	}

}