<?php
class UtilHelper extends AppHelper {
	
	public function startsWith($haystack, $needle) {
		$this->Photo = ClassRegistry::init('Photo');
		
		return $this->Photo->startsWith($haystack, $needle);
	}
}