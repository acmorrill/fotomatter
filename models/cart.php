<?php

class Cart extends AppModel {
	public $useTable = false;

	
	
	
	public function add_to_cart($photo_id, $photo_print_type_id, $short_side_inches) {
		$this->Session = $this->get_session();
		
//		$this->Session->delete('Cart'); // DREW TODO - remove this
		// 4) add print type name to cart data
		// 5) create a cart validator
		// 6) create html for the cart
		
		

		$this->Photo = ClassRegistry::init('Photo');
		$extra_print_data = $this->Photo->get_extra_print_data($photo_id, $photo_print_type_id, $short_side_inches);
		$long_side_inches = $extra_print_data['CurrentPrintData']['long_side_feet_inches'];
		$price = $extra_print_data['CurrentPrintData']['price'];
		$shipping_price = $extra_print_data['CurrentPrintData']['shipping_price'];
		$photo_print_type_name = $extra_print_data['PhotoPrintType']['print_name'];
		
		
		// get data for the new cart item
		$key = $this->get_cart_key($photo_id, $photo_print_type_id, $short_side_inches);
		
		
		// update the cart
		$cart_items = array();
		if ($this->Session->check('Cart.items')) {
//			$this->log('came here 1', 'cart_error');
			$cart_items = $this->Session->read('Cart.items');
		} else {
//			$this->log('came here 2', 'cart_error');
//			$this->log($this->Session->read('Cart'), 'cart_error');
		}
		if (isset($cart_items[$key])) {
			$cart_items[$key]['qty'] += 1;
		} else {
			$cart_items[$key]['qty'] = 1;
		}
		$cart_items[$key]['photo_id'] = $photo_id;
		$cart_items[$key]['photo_print_type_id'] = $photo_print_type_id;
		$cart_items[$key]['short_side_inches'] = $short_side_inches;
		$cart_items[$key]['long_side_inches'] = $long_side_inches;
		$cart_items[$key]['price'] = $price;
		$cart_items[$key]['shipping_price'] = $shipping_price;
		$cart_items[$key]['photo_print_type_name'] = $photo_print_type_name;
		$this->Session->write('Cart.items', $cart_items);
	}
	
	public function create_fake_cart_items() {
		$this->Session = $this->get_session();
		$this->Session->delete('Cart');
		$this->add_to_cart(21, 1, 11);
		$this->add_to_cart(21, 1, 11);
		$this->add_to_cart(23, 1, 11);
		$this->add_to_cart(24, 1, 11);
		$this->add_to_cart(27, 1, 11);
	}
	
	public function create_fake_cart_items_laptop() {
		$this->Session = $this->get_session();
		$this->Session->delete('Cart');
		$this->add_to_cart(32, 2, 2.5);
		$this->add_to_cart(32, 2, 2.5);
		$this->add_to_cart(29, 2, 2.5);
		$this->add_to_cart(25, 2, 2.5);
		$this->add_to_cart(27, 2, 2.5);
	}
	
	public function get_cart_key($photo_id, $photo_print_type_id, $short_side_inches) {
		return 'photo_id:'.$photo_id.'|print_type_id:'.$photo_print_type_id.'|short_side_inches:'.$short_side_inches;
	}
	
	
	public function get_cart_data() {
		$this->Session = $this->get_session();
		
		return $this->Session->read('Cart');
	}
	
	public function get_cart_items() {
		$cart_data = $this->get_cart_data();
		
		if (isset($cart_data['items'])) {
			return $cart_data['items'];
		} else {
			return array();
		}
	}
	
	public function get_cart_line_total($qty, $price) {
		return $qty * $price;
	}
	
	public function get_cart_total($cart_items = null) {
		if (!isset($cart_items)) {
			$cart_items = $this->get_cart_items();
		}
		
		$total = 0;
		$total += $this->get_cart_subtotal($cart_items);
		$total += $this->get_cart_shipping_total($cart_items);
		
		return $total;
	}
	
	public function get_cart_subtotal($cart_items = null) {
		if (!isset($cart_items)) {
			$cart_items = $this->get_cart_items();
		}
		
		$total = 0;
		$total += $this->get_cart_items_total($cart_items);
		
		return $total;
	}
	
	public function get_cart_shipping_total($cart_items = null) {
		if (!isset($cart_items)) {
			$cart_items = $this->get_cart_items();
		}
		
		$shipping_total = 0;
		foreach ($cart_items as $cart_item) {
			$shipping_total += $cart_item['qty'] * $cart_item['shipping_price'];
		}
		
		return $shipping_total;
	}
	
	public function get_cart_items_total($cart_items = null) {
		if (!isset($cart_items)) {
			$cart_items = $this->get_cart_items();
		}
		
		$items_total = 0;
		foreach ($cart_items as $cart_item) {
			$items_total += $cart_item['qty'] * $cart_item['price'];
		}
		
		return $items_total;
	}
	
	public function set_cart_address_data($billing_data, $shipping_data) {
		$this->Session = $this->get_session();
		
		$this->Session->write('Cart.billing_address', $billing_data);
		$this->Session->write('Cart.shipping_address', $shipping_data);
	}
	
	public function get_cart_billing_address() {
		$this->Session = $this->get_session();
		
		if ($this->Session->check('Cart.billing_address')) {
			$billing_address = $this->Session->read('Cart.billing_address');
			
			$billing_address['country_name'] = '';
			if (!empty($billing_address['country_id'])) {
				$this->GlobalCountry = ClassRegistry::init('GlobalCountry');
				$country = $this->GlobalCountry->find('first', array(
					'conditions' => array(
						'GlobalCountry.id' => $billing_address['country_id'],
					),
					'contain' => false,
				));
				$billing_address['country_name'] = $country['GlobalCountry']['country_name'];
			}
			
			$billing_address['state_name'] = '';
			if (!empty($billing_address['state_id'])) {
				$this->GlobalCountryState = ClassRegistry::init('GlobalCountryState');
				$state = $this->GlobalCountryState->find('first', array(
					'conditions' => array(
						'GlobalCountryState.id' => $billing_address['state_id'],
					),
					'contain' => false,
				));
				$billing_address['state_name'] = $state['GlobalCountryState']['state_name'];
			}
			
			return $billing_address;
		} else {
			return array();
		}
	}
	
	public function get_cart_shipping_address() {
		$this->Session = $this->get_session();
		
		if ($this->Session->check('Cart.shipping_address')) {
			$shipping_address = $this->Session->read('Cart.shipping_address');
			
			$shipping_address['country_name'] = '';
			if (!empty($shipping_address['country_id'])) {
				$this->GlobalCountry = ClassRegistry::init('GlobalCountry');
				$country = $this->GlobalCountry->find('first', array(
					'conditions' => array(
						'GlobalCountry.id' => $shipping_address['country_id'],
					),
					'contain' => false,
				));
				$shipping_address['country_name'] = $country['GlobalCountry']['country_name'];
			}
			
			$shipping_address['state_name'] = '';
			if (!empty($shipping_address['state_id'])) {
				$this->GlobalCountryState = ClassRegistry::init('GlobalCountryState');
				$state = $this->GlobalCountryState->find('first', array(
					'conditions' => array(
						'GlobalCountryState.id' => $shipping_address['state_id'],
					),
					'contain' => false,
				));
				$shipping_address['state_name'] = $state['GlobalCountryState']['state_name'];
			}
			
			
			return $shipping_address;
		} else {
			return array();
		}
	}
	
	public function cart_empty() {
		$cart_items = $this->get_cart_items();
		
		if (empty($cart_items)) {
			return true;
		} else {
			return false;
		}
	}
	
}