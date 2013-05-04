<?php
class EcommerceHelper extends AppHelper {
	
	public function print_size_has_non_pano($print_type) {
		return (isset($print_type['PhotoAvailSize']['photo_format_ids']) && strpos($print_type['PhotoAvailSize']['photo_format_ids'], '1,2,3') !== false);
	}
	
	public function print_size_has_pano($print_type) {
		return (isset($print_type['PhotoAvailSize']['photo_format_ids']) && strpos($print_type['PhotoAvailSize']['photo_format_ids'], '4,5') !== false);
	}
	
	public function get_available_countries() {
		$apc_key = 'available_countries';
		apc_clear_cache('user');
		if (apc_exists($apc_key)) {
			$countries = apc_fetch($apc_key);
		} else {
			$this->GlobalCountry = ClassRegistry::init("GlobalCountry");
		
			$countries = $this->GlobalCountry->find('all', array(
				'contain' => false
			));
			apc_store($apc_key, $countries, 60*60*24*7); // store for one week
		}
		
		return $countries;
	}
	
	public function get_cart_billing_address() {
		$this->Cart = ClassRegistry::init('Cart');
		return $this->Cart->get_cart_billing_address();
	}
	
	public function get_cart_shipping_address() {
		$this->Cart = ClassRegistry::init('Cart');
		return $this->Cart->get_cart_shipping_address();
	}
		
	
	
}