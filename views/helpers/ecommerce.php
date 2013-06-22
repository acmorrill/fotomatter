<?php
class EcommerceHelper extends AppHelper {
	
	public function print_size_has_non_pano($print_type) {
		return (isset($print_type['PhotoAvailSize']['photo_format_ids']) && strpos($print_type['PhotoAvailSize']['photo_format_ids'], '1,2,3') !== false);
	}
	
	public function print_size_has_pano($print_type) {
		return (isset($print_type['PhotoAvailSize']['photo_format_ids']) && strpos($print_type['PhotoAvailSize']['photo_format_ids'], '4,5') !== false);
	}
	
	public function get_available_countries() {
		return ClassRegistry::init('GlobalCountry')->get_available_countries();
	}
	
	public function get_cart_billing_address() {
		$this->Cart = ClassRegistry::init('Cart');
		return $this->Cart->get_cart_billing_address();
	}
	
	public function get_cart_shipping_address() {
		$this->Cart = ClassRegistry::init('Cart');
		return $this->Cart->get_cart_shipping_address();
	}
	
	public function get_country_name_by_id($country_id) {
		$this->GlobalCountry = ClassRegistry::init('GlobalCountry');
		return $this->GlobalCountry->get_country_name_by_id($country_id);
	}
	
	public function get_state_name_by_id($state_id) {
		$this->GlobalCountryState = ClassRegistry::init('GlobalCountryState');
		return $this->GlobalCountryState->get_state_name_by_id($state_id);
	}
		
	
	
}