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
}