<?php

class BrowscapComponent extends Object {

	public function initialize(&$controller, $settings = array()) {
		$this->controller = $controller;
	}

	function is_browser_supported() {
		$browser_data = get_browser(null, true);


		if (empty($browser_data['browser']) || empty($browser_data['version'])) {
			return false;
		}

		$version = intval($browser_data['version']);
		
		switch ($browser_data['browser']) {
			case "Firefox":
				if ($version >= 31) {
					return true;
				} else {
					return false;
				}
				break;
			case "Chrome":
				if ($version >= 30) {
					return true;
				} else {
					return false;
				}
				break;
			case "IE":
				if ($version >= 9) {
					return true;
				} else {
					return false;
				}
				break;
			case "Safari":
				if ($version >= 7) {
					return true;
				} else {
					return false;
				}
				break;
		}


		return false;
	}

}
