<?php
class HashUtilComponent extends Object {
	
	public function initialize(& $controller) {
		$this->controller = $controller;
	}
	
	public function set_new_hash($name_space='general') {
		$this->Hash = ClassRegistry::init("Hash");
		$new_hash = $this->Hash->generate_and_return_hash($name_space);
		if ($new_hash === false) {
			return false;
		}
		$this->controller->set('current_locking_hash', $new_hash);
		$this->controller->set('current_locking_hash_namespace', $name_space);
		return true;
	}
	
	public function check_this_hash($hash, $name_space) {
		return ClassRegistry::init("Hash")->check_this_hash($hash, $name_space);
	}
	
	

}