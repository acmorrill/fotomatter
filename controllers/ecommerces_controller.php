<?php
class EcommercesController extends AppController {
	public $name = 'Ecommerces';
	public $uses = array('PhotoAvailSize', 'PhotoFormat', 'PhotoPrintType', 'PhotoAvailSizesPhotoPrintType', 'Cart', 'Photo', 'User', 'cake_authnet.AuthnetProfile', 'cake_authnet.AuthnetOrder');
	public $layout = 'admin/ecommerces';


	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('view_cart', 'add_to_cart', 'checkout_login_or_guest', 'checkout_get_address', 'get_available_states_for_country_options', 'checkout_finalize_payment'));
		
//		$this->front_end_auth = array('checkout_get_address');
	}

	public function admin_index() {

	}
	
	public function admin_reset_print_sizes() {
		$this->HashUtil->set_new_hash('ecommerce');
		
		$this->PhotoAvailSize->restore_avail_photo_size_defaults();
		
		$this->Session->setFlash('Available print sizes reset.');
		
		$this->redirect('/admin/ecommerces/manage_print_sizes');
	}
	 
	public function admin_delete_print_size($photo_avail_size_id) {
		$this->HashUtil->set_new_hash('ecommerce');
		
		if (!$this->PhotoAvailSize->delete($photo_avail_size_id)) {
			$this->Session->setFlash('Failed to delete available photo size.');
			$this->major_error('Failed to delete available photo size.', array($photo_avail_size_id));
		}
		
		$this->redirect('/admin/ecommerces/manage_print_sizes');
	}
	
	public function admin_add_print_size($photo_avail_size_id = null) {
		$this->HashUtil->set_new_hash('ecommerce');
		
		if (!empty($this->data)) {
			if ( !isset($this->data['PhotoAvailSize']['photo_format_ids']) ) {
				$this->Session->setFlash('Please choose photo formats to apply the print size to.');
			} else if ( !isset($this->data['PhotoAvailSize']['short_side_length']) ) {
				$this->Session->setFlash('Please choose a short side length.');
			} else {
				$this->data['PhotoAvailSize']['photo_format_ids'] = implode(',', $this->data['PhotoAvailSize']['photo_format_ids']);

				$this->PhotoAvailSize->create();
				if (!$this->PhotoAvailSize->save($this->data)) {
					$this->Session->setFlash('Failed to add available photo size.');
					$this->major_error('Failed to save available photo size.', array($this->data));
				} else {
					$this->redirect('/admin/ecommerces/manage_print_sizes');
				}
			}
		} else if (isset($photo_avail_size_id)) {
			$this->data = $this->PhotoAvailSize->find('first', array(
				'conditions' => array(
					'PhotoAvailSize.id' => $photo_avail_size_id
				),
				'contain' => false
			));
		}

		$used_short_side_dimensions = $this->PhotoAvailSize->get_used_short_side_values();

		$short_side_values = $this->PhotoAvailSize->valid_short_side_values();

		$this->set(compact('short_side_values', 'used_short_side_dimensions'));
	}
	 
	public function admin_manage_print_sizes() {
		$this->HashUtil->set_new_hash('ecommerce');
		
		$photo_avail_sizes = $this->PhotoAvailSize->find('all', array(
		'contain' => false,
		'order' => array(
			'PhotoAvailSize.short_side_length ASC'
		)
		));

		$photo_formats = $this->PhotoFormat->find('all', array(
			'contain' => false
		));
		$photo_formats = Set::combine($photo_formats, '{n}.PhotoFormat.id', '{n}');

		foreach ($photo_avail_sizes as &$photo_avail_size) {
			$format_ids = explode(',', $photo_avail_size['PhotoAvailSize']['photo_format_ids']);

			foreach ($format_ids as $format_id) {
				$photo_avail_size['PhotoFormat'][] = $photo_formats[$format_id]['PhotoFormat'];
			}
		}


		$this->set(compact('photo_avail_sizes', 'photo_formats'));
	}

	public function admin_manage_print_types_and_pricing() {
		$this->HashUtil->set_new_hash('ecommerce');
		
		$photo_print_types = $this->PhotoPrintType->find('all', array(
			'order' => array(
				'PhotoPrintType.order ASC'
			),
			'contain' => false
		));
		
		$this->set(compact('photo_print_types'));
	}
	
	
	public function admin_ajax_set_print_type_order($photo_print_type_id, $new_order) {
		$this->HashUtil->set_new_hash('ecommerce');
		
		$returnArr = array();
		
		if ($this->PhotoPrintType->moveto($photo_print_type_id, $new_order)) {
			$returnArr['code'] = 1;
			$returnArr['message'] = 'photo print type order changed successfully';
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = $this->PhotoPrintType->major_error('failed to change photo print type order', compact('photo_print_type_id', 'new_order'));
		}
		
		$this->return_json($returnArr);
	}
	
	public function admin_delete_print_type($photo_print_type_id) {
		$this->HashUtil->set_new_hash('ecommerce');
		
		if (!$this->PhotoPrintType->delete($photo_print_type_id)) {
			$this->Session->setFlash('Failed to delete photo print type.');
			$this->major_error('Failed to delete photo print type.', compact('photo_print_type_id'));
		}
		
		$this->redirect('/admin/ecommerces/manage_print_types_and_pricing');
	}
		
		
	
	public function admin_add_print_type_and_pricing($photo_print_type_id = 0) {
		$this->HashUtil->set_new_hash('ecommerce');
		
		if (!empty($this->data)) { 
			///////////////////////////////////////////////
			// do validation on the data
			$passed_validation = true;
			$print_type_id = !empty($this->data['PhotoPrintType']['id']) ? $this->data['PhotoPrintType']['id'] : null ;
			$print_name = !empty($this->data['PhotoPrintType']['print_name']) ? $this->data['PhotoPrintType']['print_name'] : null ;
			$turnaround_time = !empty($this->data['PhotoPrintType']['turnaround_time']) ? $this->data['PhotoPrintType']['turnaround_time'] : '' ;
			
			if ($passed_validation && !isset($print_name)) {
				$this->Session->setFlash("Print name must be set.");
				$passed_validation = false;
			}
			

			if ($passed_validation) {
				// create the new photo type
				$new_photo_type = array();
				$new_photo_type['PhotoPrintType']['id'] = $print_type_id;
				$new_photo_type['PhotoPrintType']['print_name'] = $print_name;
				$new_photo_type['PhotoPrintType']['turnaround_time'] = $turnaround_time;
				$this->PhotoPrintType->create();
				if (!$this->PhotoPrintType->save($new_photo_type)) {
					$this->Session->setFlash("Failed to save photo print type.");
					$this->PhotoPrintType->major_error('Failed to save photo print type', compact('new_photo_type'));
				} else {
					// add into the PhotoPrintSizesPhotoPrintType join table
					$save_error = false; 
					if (!empty($this->data['PhotoAvailSizesPhotoPrintType'])) {
						foreach ($this->data['PhotoAvailSizesPhotoPrintType'] as $count => $curr_join_data) {
//							$this->log($curr_join_data, 'curr_join_data');
							
							if (!isset($curr_join_data['photo_avail_size_id'])) { // means we need to remove the join table entry instead
								$this->PhotoAvailSizesPhotoPrintType->deleteAll(array(
									'PhotoAvailSizesPhotoPrintType.id' => $curr_join_data['id']
								), true, true);
							} else { // save the join table entry
								$new_join_table_data = array();
								if (empty($curr_join_data['id'])) {
									$curr_join_data['id'] = null;
								}
								$new_join_table_data['id'] = $curr_join_data['id'];
								$new_join_table_data['photo_avail_size_id'] = $curr_join_data['photo_avail_size_id'];
								$new_join_table_data['photo_print_type_id'] = $this->PhotoPrintType->id;
								$new_join_table_data['non_pano_available'] = isset($curr_join_data['non_pano_available']) ? 1 : 0;
								if ($new_join_table_data['non_pano_available'] === 1) {
									$new_join_table_data['non_pano_price'] = !empty($curr_join_data['non_pano_price']) ? $curr_join_data['non_pano_price'] : '0.00';
									$new_join_table_data['non_pano_shipping_price'] = !empty($curr_join_data['non_pano_shipping_price']) ? $curr_join_data['non_pano_shipping_price'] : '0.00';
									$new_join_table_data['non_pano_custom_turnaround'] = !empty($curr_join_data['non_pano_custom_turnaround']) ? $curr_join_data['non_pano_custom_turnaround'] : '';
									$new_join_table_data['non_pano_global_default'] = isset($curr_join_data['non_pano_global_default']) ? 1 : 0;
									$new_join_table_data['non_pano_force_settings'] = isset($curr_join_data['non_pano_force_settings']) ? 1 : 0;
								} else {
									$new_join_table_data['non_pano_price'] = '0.00';
									$new_join_table_data['non_pano_shipping_price'] = '0.00';
									$new_join_table_data['non_pano_custom_turnaround'] = '';
									$new_join_table_data['non_pano_global_default'] = 0;
									$new_join_table_data['non_pano_force_settings'] = 0;
								}
								$new_join_table_data['pano_available'] = isset($curr_join_data['pano_available']) ? 1 : 0;
								if ($new_join_table_data['pano_available'] === 1) {
									$new_join_table_data['pano_price'] = !empty($curr_join_data['pano_price']) ? $curr_join_data['pano_price'] : '0.00';
									$new_join_table_data['pano_shipping_price'] = !empty($curr_join_data['pano_shipping_price']) ? $curr_join_data['pano_shipping_price'] : '0.00';
									$new_join_table_data['pano_custom_turnaround'] = !empty($curr_join_data['pano_custom_turnaround']) ? $curr_join_data['pano_custom_turnaround'] : '';
									$new_join_table_data['pano_global_default'] = isset($curr_join_data['pano_global_default']) ? 1 : 0;
									$new_join_table_data['pano_force_settings'] = isset($curr_join_data['pano_force_settings']) ? 1 : 0;
								} else {
									$new_join_table_data['pano_price'] = '0.00';
									$new_join_table_data['pano_shipping_price'] = '0.00';
									$new_join_table_data['pano_custom_turnaround'] = '';
									$new_join_table_data['pano_global_default'] = 0;
									$new_join_table_data['pano_force_settings'] = 0;
								}


								$this->PhotoAvailSizesPhotoPrintType->create();
								if (!$this->PhotoAvailSizesPhotoPrintType->save($new_join_table_data)) {
									$this->Session->setFlash("Failed to connect photo print type to photo print size.");
									$this->PhotoPrintType->major_error('Failed to connect photo print type to photo print size', compact('new_join_table_data'));
									$save_error = true;
									break;
								} 
							}
						}
					}
					if ($save_error === false) {
						$this->redirect('/admin/ecommerces/manage_print_types_and_pricing/');
					}
				}
			}
			
			
			
		}
		
		
		$photo_avail_sizes_query = "
			SELECT * FROM photo_avail_sizes AS PhotoAvailSize
				LEFT JOIN photo_avail_sizes_photo_print_types AS PhotoAvailSizesPhotoPrintType
					ON (PhotoAvailSizesPhotoPrintType.photo_avail_size_id = PhotoAvailSize.id AND PhotoAvailSizesPhotoPrintType.photo_print_type_id = :photo_print_type_id )
			ORDER BY PhotoAvailSize.short_side_length ASC
		";
		
		
		$photo_avail_sizes = $this->PhotoAvailSize->query($photo_avail_sizes_query, array(
			'photo_print_type_id' => $photo_print_type_id
		));
		
		$photo_print_type = $this->PhotoPrintType->find('first', array(
			'conditions' => array(
				'PhotoPrintType.id' => $photo_print_type_id
			),
			'contain' => false
		));
		
		
		
		$this->set(compact('photo_avail_sizes', 'photo_print_type'));
	}
	
	public function add_to_cart() {
		if ($this->Session->check('Cart')) {
			$this->log('came here 3', 'cart_error');
		} else {
			$this->log('came here 4', 'cart_error');
		}
		
		///////////////////////////////////////////////
		// make sure ids are valid
			if (!isset($this->data['PhotoPrintType']['id']) || !isset($this->data['Photo']['id']) || !isset($this->data['Photo']['short_side_inches'])) {
				$this->major_error("photo_print_type_id or photo_id or short_side_inches not set in add to cart", array('data' => $this->data, 'params' => $this->params));
				$this->Session->setFlash('Error adding item to cart.');
				$this->redirect($this->referer());
				exit();
			}

			$photo_print_type_id = $this->data['PhotoPrintType']['id'];
			$photo_id = $this->data['Photo']['id'];
			$short_side_inches = $this->data['Photo']['short_side_inches'];
			
			
			

			$photo_exists = $this->Photo->find('first', array(
				'conditions' => array(
					'Photo.id' => $photo_id
				),
				'contain' => false
			));
			if (empty($photo_exists)) {
				$this->major_error("photo_id not connected to real photo", array('data' => $this->data, 'params' => $this->params));
				$this->Session->setFlash('Error adding item to cart.');
				$this->redirect($this->referer());
				exit();
			}
			$print_type_exists = $this->PhotoPrintType->find('first', array(
				'conditions' => array(
					'PhotoPrintType.id' => $photo_print_type_id
				),
				'contain' => false
			));
			if (empty($print_type_exists)) {
				$this->major_error("photo_print_type_id not connected to real print type", array('data' => $this->data, 'params' => $this->params));
				$this->Session->setFlash('Error adding item to cart.');
				$this->redirect($this->referer());
				exit();
			}
			
			// DREW TODO - validate the short side inches 
			// $short_side_inches
			
			// DREW TODO - validate the long side inches
			
			
			// DREW TODO - add in the price to the calculation
			// validate the price againts the print type and size
			
		// end validation
			
		
			
		$this->Cart->add_to_cart($photo_id, $photo_print_type_id, $short_side_inches);
		$this->redirect('/ecommerces/view_cart/');
	}
	
	public function view_cart() {
		$this->Cart->create_fake_cart_items(); // DREW TODO - delete this line
		
		$this->ThemeRenderer->render($this);
	}
	
	public function cart_empty_redirect() { 
		$this->redirect('/ecommerces/view_cart');
		exit();
	}
		
	
	public function checkout_login_or_guest() {
		if ($this->Cart->cart_empty()) {
			 $this->cart_empty_redirect();
		}
		
		
		$logged_in = $this->is_logged_in();
		if ($logged_in) {
//			if ($no_addresses) { // DREW TODO
//				redirect to collect address
//			}
			
			$this->redirect('/ecommerces/checkout_finalize_payment');
		}

		$this->ThemeRenderer->render($this);
	}
	
	public function checkout_get_address() {
		if ($this->Cart->cart_empty()) {
			 $this->cart_empty_redirect();
		}
		
//		if ($logged_in) { // DREW TODO
			// get logged in user info to popuplate the cart address data with
//		}
		
		
		if (!empty($this->data)) {
			// validate the data
				try {
					// validate billing address
					$this->Validation->validate('not_empty', $this->data, 'BillingAddress', __('Billing address must be passed.', true));
					$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'firstname', __('Billing first name is required.', true));
					$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'lastname', __('Billing last name is required.', true));
					$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'address1', __('Billing address is required.', true));
					$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'city', __('Billing city is required.', true));
					$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'zip', __('Billing zip code is required.', true));
					$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'country_id', __('Billing country is required.', true));
					if (isset($this->data['BillingAddress']['state_id']) && $this->data['BillingAddress']['state_id'] !== 'no_state') {
						$this->Validation->validate($this, 'not_empty', $this->data['BillingAddress'], 'state_id', __('Billing state is required.', true));
					}

					// validate shipping address
					if (!isset($this->data['ShippingAddress']['same_as_billing'])) {
						$this->Validation->validate('not_empty', $this->data, 'ShippingAddress', __('Shipping address must be passed.', true));
						$this->Validation->validate('not_empty', $this->data['ShippingAddress'], 'firstname', __('Shipping first name is required.', true));
						$this->Validation->validate('not_empty', $this->data['ShippingAddress'], 'lastname', __('Shipping last name is required.', true));
						$this->Validation->validate('not_empty', $this->data['ShippingAddress'], 'address1', __('Shipping address is required.', true));
						$this->Validation->validate('not_empty', $this->data['ShippingAddress'], 'city', __('Shipping city is required.', true));
						$this->Validation->validate('not_empty', $this->data['ShippingAddress'], 'zip', __('Shipping zip code is required.', true));
						$this->Validation->validate('not_empty', $this->data['ShippingAddress'], 'country_id', __('Shipping country is required.', true));
						if (isset($this->data['ShippingAddress']['state_id']) && $this->data['ShippingAddress']['state_id'] !== 'no_state') {
							$this->Validation->validate($this, 'not_empty', $this->data['ShippingAddress'], 'state_id', __('Shipping state is required.', true));
						}
					}
				} catch (Exception $e) {
					$this->Session->setFlash($e->getMessage());
					$this->ThemeRenderer->render($this);
					return;
				}
				
			// save the data into the cart session
			$billing_data = $this->data['BillingAddress'];
			if (isset($this->data['ShippingAddress']['same_as_billing'])) {
				$shipping_data = $billing_data;
				$shipping_data['same_as_billing'] = true;
			} else {
				$shipping_data = $this->data['ShippingAddress'];
				$shipping_data['same_as_billing'] = false;
			}
			$this->Cart->set_cart_address_data($billing_data, $shipping_data);
			
			$this->redirect('/ecommerces/checkout_finalize_payment');
		}
		
		
		
		
		$this->ThemeRenderer->render($this);
	}
	
	public function checkout_finalize_payment() {
		if ($this->Cart->cart_empty()) {
			 $this->cart_empty_redirect();
		}
		
//		if ($shipping_data_empty) {
//			redirect to get shipping
//		}
		
		
//		if ($logged_in) { // DREW TODO
			// get logged in user info to popuplate address and cc info
//		}
		
		
		if (!empty($this->data)) {
			// validate the data
			try {
				// validate create account data
				if (!empty($this->data['CreateAccount']['email_address'])) {
					$this->Validation->validate('valid_email', $this->data['CreateAccount'], 'email_address', __('Email address invalid.', true));
					$this->Validation->validate('not_empty', $this->data['CreateAccount'], 'password', __('Please enter an account password.', true));
					$this->Validation->validate('not_empty', $this->data['CreateAccount'], 'repeat_password', __('Please reenter the password.', true));
					$this->Validation->validate('valid_password', $this->data['CreateAccount'], 'password', __('Account password must be at least 8 characters long.', true));
					$this->Validation->validate('password_match', $this->data['CreateAccount']['password'], $this->data['CreateAccount']['repeat_password'], __('Account repeat password does not match', true));
					
					// check to see if the user email address is available
					$exists = $this->User->find('first', array(
						'conditions' => array('User.email_address' => $this->data['CreateAccount']['email_address']),
						'contain' => false,
					));
					
					if (!empty($exists)) {
						throw new Exception('Email address already taken.');
					}
				}
				
				// validate cc info
				$this->Validation->validate('not_empty', $this->data['Payment'], 'name_on_card', __('Name on card cannot be empty.', true));
				$this->Validation->validate('in_array', $this->data['Payment']['credit_card_method'], array('visa', 'mastercard', 'discover', 'amex'), __('Invalid payment method.', true));
				$this->Validation->validate('valid_cc', $this->data['Payment']['card_number'], $this->data['Payment']['credit_card_method'], __('Invalid credit card number.', true));
				// make sure the expiration is in the future
				$expiration_timestamp = mktime(0, 0, 0, (int)$this->data['Payment']['expiration_month'], 1, (int)$this->data['Payment']['expiration_year']);
				$expiration_str = date('Y-m-d H:i:s', $expiration_timestamp);
				if (time() > $expiration_timestamp) {
					throw new Exception('Invalid card expiration.');
				}
				$this->Validation->validate('valid_cc_code', $this->data['Payment'], 'security_code', __('Invalid security code.', true));
				
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
				$this->ThemeRenderer->render($this);
				return;
			}
			
			
			/////////////////////////////////////////////////////
			// create a user if need be 
			// also create a CIM account for the user - and charge
			// amount to CIM account
			// otherwise just charge straight to authorize.net
			if (!empty($this->data['CreateAccount']['email_address'])) {
				$new_user_id = $this->User->create_user($this->data['CreateAccount']['email_address'], $this->data['CreateAccount']['password'], false);
			
			
				// try and save the credit card data to authorize.net CIM
				$billing_address = $this->Cart->get_cart_billing_address();
				$shipping_address = $this->Cart->get_cart_shipping_address();
				$authnet_data = array(
					'AuthnetProfile' => array(
						'user_id' => $new_user_id,
						'billing_firstname' => $billing_address['firstname'],
						'billing_lastname' => $billing_address['lastname'],
						'billing_address' => $billing_address['address1']." ".$billing_address['address2'],
						'billing_city' => $billing_address['city'],
						'billing_state' => $billing_address['state_name'],
						'billing_zip' => $billing_address['zip'],
						'billing_country' => $billing_address['country_name'],
						'billing_phoneNumber' => isset($billing_address['phoneNumber']) ? $billing_address['phoneNumber'] : '' ,
						'payment_cardNumber' => $this->data['Payment']['card_number'],
						'payment_expirationDate' => $expiration_str,
						'payment_cardCode' => $this->data['Payment']['security_code'],
						'shipping_firstname' => $shipping_address['firstname'],
						'shipping_lastname' => $shipping_address['lastname'],
						'shipping_address' => $shipping_address['address1']." ".$shipping_address['address2'],
						'shipping_city' => $shipping_address['city'],
						'shipping_state' => $shipping_address['state_name'],
						'shipping_zip' => $shipping_address['zip'],
						'shipping_country' => $shipping_address['country_name'],
						'payment_cc_last_four' => substr($this->data['Payment']['card_number'], -4, 4),
					),
				);

				$this->AuthnetProfile->create();
				$authnet_result = $this->AuthnetProfile->save($authnet_data);


				if ($authnet_result === false) {
					$this->Session->setFlash('Failed to save credit card info. Please contact Fotomatter support.');
					$this->major_error('Failed to save credit card info. Please contact Fotomatter support.');
					$this->ThemeRenderer->render($this);
					return;
				}


				// actually charge for the order
				if (!$this->AuthnetOrder->charge_cart_to_cim($this->AuthnetProfile->id)) {
					$this->Session->setFlash('Failed to charge credit card.');
					$this->major_error('Failed to charge credit card.');
					$this->ThemeRenderer->render($this);
					return;
				}
			} else {
				// DREW TODO - charge straight to authorize.net without the CIM
			}
		}
		
		
		$this->ThemeRenderer->render($this);
	}
	
	
	public function get_available_states_for_country_options($country_id, $state_id = null) {
		$state_option_html = '';
		$data = array();
		$this->GlobalCountryState = ClassRegistry::init('GlobalCountryState');

		$states = $this->GlobalCountryState->get_states_by_country($country_id);

		$data['count'] = count($states);
		if ($data['count'] > 0) {
			$state_option_html .= "<option value=''>".__('Choose a State', true)."</option>";
		} else {
			$state_option_html .= "<option value='no_state'>&nbsp;</option>";
		}
		foreach ($states as $state) {
			$selected = '';
			if (isset($state_id) && $state_id == $state['GlobalCountryState']['id']) {
				$selected = 'selected="selected"';
			}

			$state_option_html .= "<option value='{$state['GlobalCountryState']['id']}' $selected >{$state['GlobalCountryState']['state_name']}</option>";
		}
		$data['html'] = $state_option_html;
		
		
		$this->return_json($data);
	}
	
}