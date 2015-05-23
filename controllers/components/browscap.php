<?php

class BrowscapComponent extends Object {

	public function initialize(&$controller, $settings = array()) {
		$this->controller = $controller;
	}

	function is_browser_supported() {
		$browser_data_apc_key = "browser_data_cache_" . $_SERVER['HTTP_USER_AGENT'];
		if (apc_exists($browser_data_apc_key)) {
			$browser_data = apc_fetch($browser_data_apc_key);
		} else {
			$browser_data = get_browser(null, true);
			apc_add($browser_data_apc_key, $browser_data);
		}


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
		}


		return false;
	}

}
