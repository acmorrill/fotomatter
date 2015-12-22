<?php

class PhotoCacheHelper extends AppHelper {

	
	
	/**
	 * 	If can't find the function try to call on photo model
	 * 
	 * @param type $method_name
	 * @param type $args
	 * @return type 
	 */
	function __call($method_name, $args) {
		$this->PhotoCache = ClassRegistry::init('PhotoCache');

		return call_user_func_array(array($this->PhotoCache, $method_name), $args);
	}
}
