<?php

class BrowscapComponent extends Object {

	public function initialize(&$controller, $settings = array()) {
		$this->controller = $controller;
	}

	public function is_browser_supported() {
		$browser_data = $this->get_browser_data();

		if (empty($browser_data['browser']) || empty($browser_data['version'])) {
			return false;
		}

		$version = 30000;
		if (!empty($browser_data['version'])) {
			$version = intval($browser_data['version']);
		}
		if ($version == 0) {
			$version = 30000;
		}
		
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
				return false;
		}


		return true; // DREW TODO - change this back to false whene browscap is updated on a cron
	}
	
	public function is_mobile() {
		$browser_data = $this->get_browser_data();
		
		if (!empty($browser_data['ismobiledevice'])) {
			return true;
		}
		
		return false;
	}
	
	public function is_tablet() {
		$browser_data = $this->get_browser_data();
		
		if (!empty($browser_data['istablet'])) {
			return true;
		}
		
		return false;
	}
	
	private function get_browser_data() {
		// DREW TODO - also make this cache into a file so the cache can be restored after apc is cleared
		$user_agent = 'undefined';
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
		}
		$browser_data_apc_key = "browser_data_cache_" . $user_agent;
		if (apc_exists($browser_data_apc_key)) {
			$browser_data = apc_fetch($browser_data_apc_key);
		} else {
			$browser_data = get_browser(null, true);
			apc_add($browser_data_apc_key, $browser_data);
		}
		
		return $browser_data;
	}

}
