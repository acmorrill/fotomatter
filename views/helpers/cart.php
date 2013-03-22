<?php
class CartHelper extends AppHelper {
	/**
	 *	If can't find the function try to call on cart model
	 * 
	 * @param type $method_name
	 * @param type $args
	 * @return type 
	 */
	function __call($method_name, $args) {
		$this->Cart = ClassRegistry::init('Cart');
		
		return call_user_func_array(array($this->Cart, $method_name), $args);
    }
	
}