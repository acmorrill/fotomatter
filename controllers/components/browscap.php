<?php

class BrowscapComponent extends Object {

	public function initialize(&$controller, $settings = array()) {
		$this->controller = $controller;

		// get mobile detector
//		App::import('Vendor', 'Browscap', array('file' => 'browscap-php' . DS . 'src' . DS . 'phpbrowscap' . DS . 'Browscap.php'));
//		$this->Browscap = new phpbrowscap\Browscap( ROOT . DS . APP_DIR . DS . 'vendors' . DS . 'browscap-php' . DS . 'cache_dir' );
//		$this->Browscap->doAutoUpdate = false;
	}
	
	function get_browser_data() {
		return array();
	}

//	function __call($method_name, $args) {
//		return call_user_func_array(array($this->Browscap, $method_name), $args);
//	}

}
