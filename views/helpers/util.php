<?php
class UtilHelper extends AppHelper {
	
	public function get_all_tags() {
		$this->Tag = ClassRegistry::init('Tag');
		
		$tags = $this->Tag->find('all', array(
			'order' => array(
				'Tag.name'
			),
			'contain' => false
		));
		
		return $tags;
	}
	public function get_not_empty_theme_setting_or(&$theme_custom_settings, $key, $default = 'nope') {
		if ($default !== 'nope') {
			return $this->get_not_empty_or($theme_custom_settings, array($key, 'current_value'), $default);
		} else {
			return $this->get_not_empty_or($theme_custom_settings, array($key, 'current_value'), $theme_custom_settings[$key]['default_value']);
		}
	}
	public function get_isset_theme_setting_or(&$theme_custom_settings, $key, $default = 'nope') {
		if ($default !== 'nope') {
			return $this->get_isset_or($theme_custom_settings, array($key, 'current_value'), $default);
		} else {
			return $this->get_isset_or($theme_custom_settings, array($key, 'current_value'), $theme_custom_settings[$key]['default_value']);
		}
	}
	
	public function get_not_empty_or(&$haystack, $keys, $default = false) {
		return $this->_get_or($haystack, $keys, $default, 'not_empty');
	}
	
	public function get_isset_or(&$haystack, $keys, $default = false) {
		return $this->_get_or($haystack, $keys, $default, 'isset');
	}
	
	
	private function _get_or(&$haystack, $keys, $default, $type) {
		$use_isset = $type == 'isset';
		
		if (is_array($keys)) {
			switch (count($keys)) {
				case 2:
					if ($use_isset) { 
						if (isset($haystack[$keys[0]][$keys[1]])) {
							return $haystack[$keys[0]][$keys[1]];
						}
					} else {
						if (!empty($haystack[$keys[0]][$keys[1]])) {
							return $haystack[$keys[0]][$keys[1]];
						}
					}
					break;
				case 3:
					if ($use_isset) { 
						if (isset($haystack[$keys[0]][$keys[1]][$keys[2]])) {
							return $haystack[$keys[0]][$keys[1]][$keys[2]];
						}
					} else {
						if (!empty($haystack[$keys[0]][$keys[1]][$keys[2]])) {
							return $haystack[$keys[0]][$keys[1]][$keys[2]];
						}
					}
					break;
				case 4:
					if ($use_isset) { 
						if (isset($haystack[$keys[0]][$keys[1]][$keys[2]][$keys[3]])) {
							return $haystack[$keys[0]][$keys[1]][$keys[2]][$keys[3]];
						}
					} else {
						if (!empty($haystack[$keys[0]][$keys[1]][$keys[2]][$keys[3]])) {
							return $haystack[$keys[0]][$keys[1]][$keys[2]][$keys[3]];
						}
					}
					break;
				default:
					if ($use_isset) { 
						if (isset($haystack[$keys[0]])) {
							return $haystack[$keys[0]];
						}
					} else {
						if (!empty($haystack[$keys[0]])) {
							return $haystack[$keys[0]];
						}
					}
					break;
			}
		} else {
			if ($use_isset) { 
				if (isset($haystack[$keys])) {
					return $haystack[$keys];
				}
			} else {
				if (!empty($haystack[$keys])) {
					return $haystack[$keys];
				}
			}
		}
		return $default;
	}
	
	public function startsWith($haystack, $needle) {
		$this->Photo = ClassRegistry::init('Photo');
		
		return $this->Photo->startsWith($haystack, $needle);
	}
	
	public function endsWith($haystack, $needle) {
		$this->Photo = ClassRegistry::init('Photo');
		
		return $this->Photo->endsWith($haystack, $needle);
	}
	
	public function uuid() {
		return substr(base64_encode(String::uuid()), 0, 25);
	}
	
	public function get_formatted_created_date($created) {
		if (date("Y", strtotime($created)) == date('Y')) {
			$created_format = "M j, g:i A";
		} else {
			$created_format = "M j Y, g:i A";
		}
		
		return date($created_format, strtotime($created));
	}
	
	public function global_cdn($file_name, $force_ssl = true) {
		if ($force_ssl || !empty($_SERVER['HTTPS'])) {
			return GLOBAL_FOTOMATTER_CONTAINER_SECURE_URL.$file_name;
		} else {
			return GLOBAL_FOTOMATTER_CONTAINER_URL.$file_name;
		}
	}
	
	public function get_count_class($count, $total) {
		if ($count == 1 && $count == $total) {
			return 'first last';
		} else if ($count == 1) {
			return 'first';
		} else if ($count == $total) {
			return 'last';
		}
		
		return '';
	}
	
	public function url_exists($url) {
		$file_headers = @get_headers($url);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			$exists = false;
		} else {
			$exists = true;
		}
		
		return $exists;
	}
}