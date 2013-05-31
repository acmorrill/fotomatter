<?php
class AuthnetOrder extends CakeAuthnetAppModel {

	public $name = 'AuthnetOrder';
	public $belongsTo = array('CakeAuthnet.AuthnetProfile');
       
	
	public function one_time_charge($authnet_data) {
		$this->Cart = ClassRegistry::init('Cart');
		
		
		$order = array(
			'authnet_profile_id' => 0,
			'total' => $this->Cart->get_cart_total(),
			'foreign_model' => 'User',
			'foreign_key' => 0,
//			'tax' => array( // DREW TODO - add tax here when we have tax
//				'amount' => '',
//				'name' => '',
//				'description' => '',
//			),
			'shipping' => array(
				'amount' => $this->Cart->get_cart_shipping_total(),
				'name' => '',
				'description' => '',
			),
			'line_items' => array(),
		);
		$items = $this->Cart->get_cart_items();
		foreach ($items as $key => $item) {
			$order['line_items'][] = array(
				'unit_cost' => $item['price'],
				'name' => $item['photo_id'].'|'.$item['photo_print_type_id'].'|'.$item['short_side_inches'],
				'description' => $key,
				'quantity' => $item['qty'],
				'foreign_model' => 'Photo',
				'foreign_key' => $item['photo_id'],
				'authnet_line_item_type_id' => 1,
			);
		}
		
		if ($this->createOrderForProfile($order, false) === true) {
			$charge_data = array(
				'refId' => $this->id,
				'transactionRequest' => array(
					'transactionType' => 'authCaptureTransaction',
					'amount' => $this->Cart->get_cart_total(),
					'payment' => array(
						'creditCard' => array(
							'cardNumber' => $authnet_data['AuthnetProfile']['payment_cardNumber'],
							'expirationDate' => date('m/Y', strtotime($authnet_data['AuthnetProfile']['payment_expirationDate'])),
							'cardCode' => $authnet_data['AuthnetProfile']['payment_cardCode'],
						),
					),
	//				'order' => array(
	//					'invoiceNumber' => $order['AuthnetOrder'],
	//					'description' => 'this is a test transaction',
	//				),
	//				'lineItems' => array(
	//					'lineItem' => array(
	//						0 => array(
	//							'itemId' => '1',
	//							'name' => 'vase',
	//							'description' => 'Cannes logo',
	//							'quantity' => '18',
	//							'unitPrice' => '45.00'
	//						),
	//						1 => array(
	//							'itemId' => '2',
	//							'name' => 'desk',
	//							'description' => 'Big Desk',
	//							'quantity' => '10',
	//							'unitPrice' => '85.00'
	//						)
	//					)
	//				),
	//				'tax' => array(
	//					'amount' => '4.26',
	//					'name' => 'level2 tax name',
	//					'description' => 'level2 tax',
	//				),
	//				'duty' => array(
	//					'amount' => '8.55',
	//					'name' => 'duty name',
	//					'description' => 'duty description',
	//				),
					'shipping' => array(
						'amount' => $this->Cart->get_cart_shipping_total(),
						'name' => '',
						'description' => '',
					),
					'billTo' => array(
						'firstName' => $authnet_data['AuthnetProfile']['billing_firstname'],
						'lastName' => $authnet_data['AuthnetProfile']['billing_lastname'],
						'address' => $authnet_data['AuthnetProfile']['billing_address'],
						'city' => $authnet_data['AuthnetProfile']['billing_city'],
						'state' => $authnet_data['AuthnetProfile']['billing_state'],
						'zip' => $authnet_data['AuthnetProfile']['billing_zip'],
						'country' => $authnet_data['AuthnetProfile']['billing_country'],
					),
					'shipTo' => array(
						'firstName' => $authnet_data['AuthnetProfile']['shipping_firstname'],
						'lastName' => $authnet_data['AuthnetProfile']['shipping_lastname'],
						'address' => $authnet_data['AuthnetProfile']['shipping_address'],
						'city' => $authnet_data['AuthnetProfile']['shipping_city'],
						'state' => $authnet_data['AuthnetProfile']['shipping_state'],
						'zip' => $authnet_data['AuthnetProfile']['shipping_zip'],
						'country' => $authnet_data['AuthnetProfile']['shipping_country'],
					),
					'customerIP' => $_SERVER['REMOTE_ADDR'],
				),
			);

			// DREW TODO - get the below working - not a high priority though
//			$count = 1; 
//			foreach ($items as $key => $item) {
//				$charge_data['lineItems']['lineItem'][] = array(
//					'itemId' => $count,
//					'name' => $item['photo_id'].'+'.$item['photo_print_type_id'].'+'.$item['short_side_inches'],
//					'description' => $key,
//					'quantity' => $item['qty'],
//					'unitPrice' => $item['price'],
//				);
//				$count++;
//			}
			
			
			$this->log($charge_data, 'one_time_charge');
			
			
			$authnet = $this->get_authnet_instance();
			$result = $authnet->createTransactionRequest($charge_data);
			
			
			// START HERE TOMORROW - get process the results below and respond - after that make the finalize payment show just last four for logged in with data
			$result_data = array();
			$result_data['resultCode'] = $authnet->messages->resultCode;
			$result_data['code'] = $authnet->messages->message->code;
			$result_data['isSuccessful'] = ($authnet->isSuccessful()) ? 'yes' : 'no';
			$result_data['isError'] = ($authnet->isError()) ? 'yes' : 'no';
			$result_data['authCode'] = $authnet->transactionResponse->authCode;
			$result_data['transId'] = $authnet->transactionResponse->transId;
			
			$this->log($result_data, 'result_data_one_time_charge');
		}
		
		
		
		
	}
	
	
	public function charge_cart_to_cim($authnet_profile_id) {
		$this->Cart = ClassRegistry::init('Cart');
		
		$profile = $this->AuthnetProfile->find('first', array(
			'conditions' => array(
				'AuthnetProfile.id' => $authnet_profile_id,
			),
			'contain' => false,
		));
		
		if (empty($profile)) {
			// DREW TODO - put in major error here
			return false;
		}
		
		
		$order = array(
			'authnet_profile_id' => $authnet_profile_id,
			'total' => $this->Cart->get_cart_total(),
			'foreign_model' => 'User',
			'foreign_key' => $profile['AuthnetProfile']['user_id'],
//			'tax' => array( // DREW TODO - add tax here when we have tax
//				'amount' => '',
//				'name' => '',
//				'description' => '',
//			),
			'shipping' => array(
				'amount' => $this->Cart->get_cart_shipping_total(),
				'name' => '',
				'description' => '',
			),
			'line_items' => array(),
		);
		$items = $this->Cart->get_cart_items();
		foreach ($items as $key => $item) {
			$order['line_items'][] = array(
				'unit_cost' => $item['price'],
				'name' => $item['photo_id'].'|'.$item['photo_print_type_id'].'|'.$item['short_side_inches'],
				'description' => $key,
				'quantity' => $item['qty'],
				'foreign_model' => 'Photo',
				'foreign_key' => $item['photo_id'],
				'authnet_line_item_type_id' => 1,
			);
		}
		
		
		return $this->createOrderForProfile($order);
	}
	
	
	/**
		* Save should not be called directly, only createOrder
		* 
		* @order <array> Data used to create the order, Your order should look something like this
		* 
		* $order = array(
		*    'authnet_profile_id'=>'{id of the profile to charge this order to, for now this is the only method of payment}',
		*    'total'=>{'order_total'}
		*    'foreign_model'=>'{(optional) the model that this order should belong to, such as a users table}',
		*    'foreign_key'=>'{{required if foreign_model is specified}, id of the foreign_model}',
		*    'tax'=>array(
		*          'amount'=>'',
		*          'name'=>'',
		*          'description
		*      ),
		*      'shipping'=>array(
		*          'amount',
		*          'name',
		*          'description',
		*      ),
		*    'line_items'=>array(
		*          array(
		*              'unit_cost'=>'{item cost}',
		*              'name'=>'{name}',
		*              'description'=>'{descrtiption},
		*              'foreign_model'=>{the model that corresponds to the item in the db that they just bought},
		*              'foreign_key'=>{id of the item},
		*              'authnet_line_item_type_id'=>'specify the type of line item'
		*          )
		*     )
		* )
		*/
	public function createOrderForProfile($order, $validate = true) {
		//make sure all required items are there
		if ($validate === true && $this->_validate_order($order) === false) {
			return false;
		}
		
		$this->log('made it here 3', 'one_time_charge');

		$this->AuthnetProfile = ClassRegistry::init("AuthnetProfile");
		$profile_to_use = $this->AuthnetProfile->find('first', array(
			'conditions'=>array(
				'AuthnetProfile.id'=>$order['authnet_profile_id']
			),
			'contain'=>false
		));

		//build the order
		$api_order = array(); //data to be sent to authnet
		$order_save_db = array(); //data to be saved in order table

		$api_order['amount'] = $order['total'];
		$order_save_db['AuthnetOrder']['total'] = $order['total'];

		//tax?
		if (isset($order['tax'])) {
			$api_order['tax'] = $order['tax'];
			$order_save_db['AuthnetOrder']['tax'] = $order['tax']['amount'];
		}

		//shipping?
		if (isset($order['shipping'])) {
			$api_order['shipping'] = $order['shipping'];
			$order_save_db['AuthnetOrder']['shipping'] = $order['shipping']['amount'];
		}

		foreach ($order['line_items'] as $line_item) {

			$attach_to_order['itemId'] = $line_item['foreign_key'];
			$attach_to_order['name'] = $line_item['name'];
			$attach_to_order['description'] = $line_item['description'];
			$attach_to_order['quantity'] = $line_item['quantity'];
			$attach_to_order['unitPrice'] = $line_item['unit_cost'];

			$api_order['lineItems'][] = $attach_to_order;
		}

		$api_order['customerProfileId'] = $profile_to_use['AuthnetProfile']['customerProfileId'];
		$api_order['customerPaymentProfileId'] = $profile_to_use['AuthnetProfile']['customerPaymentProfileId'];
		$data_to_send['transaction']['profileTransAuthCapture'] = $api_order;

		$this->log('made it here 6', 'one_time_charge');
		$authnet = $this->get_authnet_instance();
		try {
//			$authnet->createCustomerProfileTransactionRequest($data_to_send);
//			if ($authnet->isError()) {
//				$returnArr['success'] = false;
//				$returnArr['code'] = $authnet->get_code();
//				$returnArr['message'] = $authnet->get_message();
//				$this->authnet_error("request failed", $authnet->get_response());
//				return $returnArr;
//			}
			$this->log('made it here 7', 'one_time_charge');

			if (isset($order['foreign_model'])) {
				$order_save_db['AuthnetOrder']['foreign_model'] = $order['foreign_model'];
			}

			if (isset($order['foreign_key'])) {
				$order_save_db['AuthnetOrder']['foreign_key'] = $order['foreign_key'];
			}
			$order_save_db['AuthnetOrder']['authnet_profile_id'] = $order['authnet_profile_id'];

			$this->create();
			if ($this->save($order_save_db) == false) {
				$this->authnet_error('Could not save order', $order);
				return false;
			}
			$this->log('made it here 4', 'one_time_charge');
			$this->AuthnetLineItem = ClassRegistry::init("AuthnetLineItem");

			foreach ($order['line_items'] as $item) {
				$item_save['AuthnetLineItem']['unit_cost'] = $item['unit_cost'];
				$item_save['AuthnetLineItem']['name'] = $item['name'];
				$item_save['AuthnetLineItem']['description'] = $item['description'];
				$item_save['AuthnetLineItem']['quantity'] = $item['quantity'];
				$item_save['AuthnetLineItem']['foreign_model'] = $item['foreign_model'];
				$item_save['AuthnetLineItem']['foreign_key'] = $item['foreign_key'];
				$item_save['AuthnetLineItem']['authnet_order_id'] = $this->id;
				$item_save['AuthnetLineItem']['authnet_line_item_type_id'] = $item['authnet_line_item_type_id'];
				$this->AuthnetLineItem->create();
				if ($this->AuthnetLineItem->save($item_save) == false) {
					$this->authnet_error('could not save line item');
					return false;
				}
			}

			return true;
		} catch (Exception $e) {
			$this->authnet_error('an exception has occurred', $e->getMessage());
			return false;
		}
	}
        
	/**
		* Make sure that we have good data passed to createOrder
		* @param type $order - data passed to createOrder
		* @return boolean
		*/
	private function _validate_order($order) {
		if (empty($order['authnet_profile_id'])) {
			$this->authnet_error('Tried to create an order without specifing a profile to charge it', $order);
			return false;
		}
		

		if (empty($order['foreign_model']) == false && empty($order['foreign_key'])) {
			$this->authnet_error('Trying to create an order, specified foreign_model but did not specify key.', $order);
			return false;
		}

		if (empty($order['line_items']) || is_array($order['line_items']) == false) {
			$this->authnet_error('Trying to create an order, but did no specify line items', $order);
			return false;
		}

		$this->AuthnetLineItemType = ClassRegistry::Init("AuthnetLineItemType");
		$line_items = $this->AuthnetLineItemType->find('list', array(
			'fields'=>array(
				'AuthnetLineItemType.id'
			) 
		));
		$check_cost = 0;
		if (isset($order['tax'])) {
			$check_cost += $order['tax']['amount'];
		}

		if (isset($order['shipping'])) {
			$check_cost += $order['shipping']['amount'];
		}

		foreach ($order['line_items'] as $line_item) {
			if (in_array($line_item['authnet_line_item_type_id'], $line_items) == false) {
				$this->authnet_error('Could not find line item type for this item', $line_item);
				return false;
			}

			if (empty($line_item['unit_cost'])) {
				$this->authnet_error('No cost for this item', $line_item);
				return false;
			}

			if (empty($line_item['name'])) {
				$this->authnet_error('No name provided', $line_item);
				return false;
			}

			if (empty($line_item['description'])) {
				$this->authnet_error('no description provided', $line_item);
				return false;
			}

			if (empty($line_item['quantity'])) {
				$this->authnet_error('no quantity specified', $line_item);
				return false;
			}
			$check_cost += $line_item['quantity'] * $line_item['unit_cost'];

			if (empty($line_item['foreign_model'])) {
				$this->authnet_error('did not specify the model to attach to a line item', $line_item);
				return false;
			}

			if (empty($line_item['foreign_key'])) {
				$this->authnet_error('did not specify the id to attach to a line item', $line_item);
				return false;
			}
		}

		if ($order['total'] != $check_cost) {
			$this->authnet_error('Tried to create order, calculated cost did not equal cost of line items. Total should be' . $check_cost, $order);
			return false;
		}

		$this->AuthnetProfile = ClassRegistry::init("AuthnetProfile");
		$profile_exists = $this->AuthnetProfile->find('count', array(
			'conditions'=>array(
				'AuthnetProfile.id'=>$order['authnet_profile_id']
			),
			'contain'=>false
		));
		if($profile_exists == false) {
			$this->authnet_error('Could not find profile for payment.', $line_item);
			return false;
		}
		return true;    
	}
        
        
}