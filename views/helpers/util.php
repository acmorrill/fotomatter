<?php
class UtilHelper extends AppHelper {
	
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
			$created_format = "F j, g:i A";
		} else {
			$created_format = "F j Y, g:i A";
		}
		
		return date($created_format, strtotime($created));
	}
}