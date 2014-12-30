<?php

class SiteSettingHelper extends AppHelper {

	/**
	 * 	If can't find the function try to call on model
	 * 
	 * @param type $method_name
	 * @param type $args
	 * @return type 
	 */
	function __call($method_name, $args) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');

		return call_user_func_array(array($this->SiteSetting, $method_name), $args);
	}

}
