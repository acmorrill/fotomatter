<?php

class TagHelper extends AppHelper {

	
	/**
	 * 	If can't find the function try to call on photo model
	 * 
	 * @param type $method_name
	 * @param type $args
	 * @return type 
	 */
	function __call($method_name, $args) {
		$this->Tag = ClassRegistry::init('Tag');

		return call_user_func_array(array($this->Tag, $method_name), $args);
	}
	
}
