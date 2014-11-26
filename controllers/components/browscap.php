<?php

class BrowscapComponent extends Object {

	public function initialize(&$controller, $settings = array()) {
		$this->controller = $controller;
	}
	
	function is_browser_supported() {
		$browser_data = get_browser(null, true);

		
		if (empty($browser_data['browser']) || empty($browser_data['majorver'])) {
			return false;
		}
		
		switch($browser_data['browser']) {
			case "Firefox":
				if ($browser_data['majorver'] >= 31) {
					return true;
				} else {
					return false;
				}
				break;
			case "Chrome":
				if ($browser_data['majorver'] >= 30) {
					return true;
				} else {
					return false;
				}
				break;
			case "IE":
				if ($browser_data['majorver'] >= 9) {
					return true;
				} else {
					return false;
				}
				break;
			case "Safari":
				if ($browser_data['majorver'] >= 7) {
					return true;
				} else {
					return false;
				}
				break;
		}

		
		return false;
	}
}
