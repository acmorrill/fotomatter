<?php

class Cart extends AppModel {
	public $useTable = false;

	
	
	
	public function add_to_cart($photo_id, $photo_print_type_id, $short_side_inches) {
		$this->Session = $this->get_session();

//		$this->Session->delete('Cart'); // DREW TODO - remove this
		
		// get data for the new cart item
		$key = 'photo_id:'.$photo_id.'|print_type_id:'.$photo_print_type_id.'|short_side_inches:'.$short_side_inches;
		
		// DREW TODO -- START HERE TOMORROW
		// 1) add price to the cart data
		// 2) validate the price data
		// 3) add the long side length to cart data
		// 4) add print type name to cart data
		// 5) create a cart validator
		// 6) create html for the cart
		
		
		
		
		
		// update the cart
		$cart_items = array();
		if ($this->Session->check('Cart.items')) {
			$cart_items = $this->Session->read('Cart.items');
		}
		if (isset($cart_items[$key])) {
			$cart_items[$key]['qty'] += 1;
		} else {
			$cart_items[$key]['qty'] = 1;
		}
		$cart_items[$key]['photo_id'] = $photo_id;
		$cart_items[$key]['photo_print_type_id'] = $photo_print_type_id;
		$cart_items[$key]['short_side_inches'] = $short_side_inches;
		$this->Session->write('Cart.items', $cart_items);
	}
	
	
	public function get_cart_data() {
		$this->Session = $this->get_session();
		
		return $this->Session->read('Cart');
	}
	
}