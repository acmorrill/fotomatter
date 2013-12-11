<?php
require_once(ROOT . '/app/tests/fototestcase.php');
class DBCheckPhotoCacheTestCase extends fototestcase {

	function start() {
		require_once(ROOT . '/app/tests/model_helpers/photo_cache.test.php');
		$this->helper = new PhotoCacheTestCaseHelper();
		$this->PhotoCache = ClassRegistry::init('PhotoCache');
		$this->_run_validate_functions($this->helper);
	}

}