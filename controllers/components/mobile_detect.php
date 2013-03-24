<?php

class MobileDetectComponent extends Object {
	
	public function initialize(&$controller, $settings=array()) {
		$this->controller = $controller;
		
		// get mobile detector
		App::import('Vendor', 'Mobile_Detect', array('file' => 'Mobile-Detector'.DS.'Mobile_Detect.php'));
		$this->Mobile_Detect = new Mobile_Detect();
		
	}
	
	function __call($method_name, $args) {
		return call_user_func_array(array($this->Mobile_Detect, $method_name), $args);
    }
	
}