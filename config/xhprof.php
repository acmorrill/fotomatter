<?php
class xhprof_object {
	public function __construct() {
		xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
	}
	
	public function __destruct() {
		
		$xhprof_data = xhprof_disable();

		//
		// Saving the XHProf run
		// using the default implementation of iXHProfRuns.
		//
		//am I specifying a new key on the session?
		$key = $_SERVER['REQUEST_URI'];
		if (isset($_GET['xhprof_key'])) {
			$key = $_GET['xhprof_key'];
			$_SESSION['xhprof_key'] = $key;
		} elseif (isset($_SESSION['xhprof_key'])) {
			$key = $_SESSION['xhprof_key'];
		}
		
		include_once ROOT . "/xhprof/xhprof_lib/utils/xhprof_lib.php";
		include_once ROOT . "/xhprof/xhprof_lib/utils/xhprof_runs.php";
		$xhprof_runs = new XHProfRuns_Default();
		$name_space = str_replace("/", "_", $key);
		//debug($xhprof_data);
		$run_id = $xhprof_runs->save_run($xhprof_data, $name_space);
		
		$this->XhprofProfile = ClassRegistry::init("XhprofProfile");
		$profile['XhprofProfile']['request_uri'] = $_SERVER['REQUEST_URI'];
		$profile['XhprofProfile']['xhprof_id'] = $run_id;
		$profile['XhprofProfile']['http_accept'] = $_SERVER['HTTP_ACCEPT'];
		$profile['XhprofProfile']['name_space'] = $name_space;
		$profile['XhprofProfile']['nano_seconds'] = $xhprof_data['main()']['wt'] / 1000;
		$this->XhprofProfile->create();
		$this->XhprofProfile->save($profile);
		session_write_close();
	}
}
global $xhprof_obj;
$xhprof_obj = new xhprof_object();