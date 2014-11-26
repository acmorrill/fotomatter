<?php

class BrowscapComponent extends Object {

	public function initialize(&$controller, $settings = array()) {
		$this->controller = $controller;
	}
	
	function is_browser_supported() {
		$browser_data = get_browser();
		print_r($browser_data);
		exit();
		
		return array();
	}
}
