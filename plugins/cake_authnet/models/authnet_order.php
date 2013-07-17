<?php
class AuthnetOrder extends CakeAuthnetAppModel {

	public $name = 'AuthnetOrder';
	public $belongsTo = array('CakeAuthnet.AuthnetProfile');
	

	public $hasMany = array(
		'AuthnetLineItem',
	);
	
	private $transaction_data;
    
	
	public function get_authnet_transaction_data($transaction_id) {
		// transaction statuses
//		authorizedPendingCapture
//		capturedPendingSettlement
//		communicationError
//		refundSettledSuccessfully
//		refundPendingSettlement
//		approvedReview
//		declined
//		couldNotVoid
//		expired
//		generalError
//		pendingFinalSettlement
//		pendingSettlement
//		failedReview
//		settledSuccessfully
//		settlementError
//		underReview
//		updatingSettlement
//		voided
//		FDSPendingReview
//		FDSAuthorizedPendingRevi
//		ew
//		returnedItem
//		chargeback
//		chargebackReversal
//		authorizedPendingRelease
		
		// saving the data for the current request (to avoid multiple authnet api request per page request)
		if (!isset($this->transaction_data[$transaction_id])) {
			$authnet = $this->get_authnet_instance();
		
			$authnet->getTransactionDetailsRequest(array(
				'transId' => $transaction_id,
			));

			$response = $authnet->get_response();


			if ($authnet->isError() == true) {
				return false;
			}
			
			$this->transaction_data[$transaction_id] = $response->transaction;

			$transaction_data = $response->transaction;
		} else {
			$transaction_data = $this->transaction_data[$transaction_id];
		}
		
		
		$this->log($transaction_data, 'transaction_data');
		
		
		return $transaction_data;
	}
	
	public function transaction_voidable($transaction_id) {
		$details = $this->get_authnet_transaction_data($transaction_id);
		
		$voidable_statuses = array(
			'authorizedPendingCapture',
			'capturedPendingSettlement',
			'authorizedPendingRelease',
		);
		
		if (!empty($details) && in_array($details->transactionStatus, $voidable_statuses)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function transaction_voided($transaction_id) {
		$details = $this->get_authnet_transaction_data($transaction_id);
		
		$voided_status = array(
			'voided',
		);
		
		if (!empty($details) && in_array($details->transactionStatus, $voided_status)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function transaction_refunded($transaction_id, $authnet_order_id) {
		$authnet_order = $this->find('first', array(
			'conditions' => array(
				'AuthnetOrder.id' => $authnet_order_id,
			),
			'contain' => false,
		));
		
		if (!empty($authnet_order['AuthnetOrder']['refund_transaction_id'])) {
			return true;
		} else {
			return false;
		}
//		$details = $this->get_authnet_transaction_data($transaction_id);
//		
//		
//		$refunded_status = array(
//			'returnedItem',
//		);
//		
//		if (!empty($details) && in_array($details->transactionStatus, $refunded_status)) {
//			return true;
//		} else {
//			return false;
//		}
	}
	
	public function transaction_refundable($transaction_id, $authnet_order_id) {
		// if already refunded then not refundable
		if ($this->transaction_refunded($transaction_id, $authnet_order_id) === true) {
			return false;
		}

		
		$details = $this->get_authnet_transaction_data($transaction_id);
		
		
		$refundable_statuses = array(
			'settledSuccessfully',
		);
		
		if (!empty($details) && in_array($details->transactionStatus, $refundable_statuses)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function void_transaction($transaction_id, $authnet_profile_id) {
		$authnet = $this->get_authnet_instance();

		// turn this back on later if necessary
//		$this->AuthnetProfile = ClassRegistry::init('AuthnetProfile');
//		$authnet_profile = $this->AuthnetProfile->find('first', array(
//			'conditions' => array(
//				'AuthnetProfile.id' => $authnet_profile_id,
//			),
//			'contain' => false,
//		));
//		
//		if (empty($authnet_profile)) {
//			$this->major_error('Failed to void a transaction because the authnet profile id was incorrect.', compact('authnet_profile_id', 'transaction_id'));
//			return false;
//		}
		
		$authnet->createCustomerProfileTransactionRequest(array(
			'transaction' => array(
				'profileTransVoid' => array(
//					'customerProfileId' => $authnet_profile['AuthnetProfile']['customerProfileId'],
//					'customerPaymentProfileId' => $authnet_profile['AuthnetProfile']['customerPaymentProfileId'],
//					'customerShippingAddressId' => $authnet_profile['AuthnetProfile']['customerShippingAddressId'],
					'transId' => $transaction_id,
				)
			),
//			'extraOptions' => '<![CDATA[x_customer_ip=100.0.0.1]]>'
		));
		
		
		if ($authnet->isError()) {
			$response = $authnet->get_response();
			$this->major_error('Failed to void order', compact('response'), 'high');
			
			return false;
		}
		
		
		return true;
	}
	
	public function refund_transaction($authnet_order_id) {
		$authnet = $this->get_authnet_instance();

		$authnet_order = $this->find('first', array(
			'conditions' => array(
				'AuthnetOrder.id' => $authnet_order_id,
			),
			'contain' => array(
				'AuthnetProfile',
			),
		));
		
		if (empty($authnet_order)) {
			$this->major_error('Failed to refund a transaction because the authnet_order_id was incorrect.', compact('authnet_order_id', 'authnet_profile_id'));
			return false;
		}
		
		$refund_data = array(
			'transaction' => array(
				'profileTransRefund' => array(
					'amount' => $authnet_order['AuthnetOrder']['total'],
//					'tax' => array(
//						'amount' => '1.00',
//						'name' => 'WA state sales tax',
//						'description' => 'Washington state sales tax'
//					),
//					'shipping' => array(
//						'amount' => '2.00',
//						'name' => 'ground based shipping',
//						'description' => 'Ground based 5 to 10 day shipping'
//					),
//					'lineItems' => array(
//						'lineItem' => array(
//							0 => array(
//								'itemId' => '1',
//								'name' => 'vase',
//								'description' => 'Cannes logo',
//								'quantity' => '18',
//								'unitPrice' => '45.00'
//							),
//							1 => array(
//								'itemId' => '2',
//								'name' => 'desk',
//								'description' => 'Big Desk',
//								'quantity' => '10',
//								'unitPrice' => '85.00'
//							)
//						)
//					),
//					'customerProfileId' => '5427896',
//					'customerPaymentProfileId' => '4796541',
//					'customerShippingAddressId' => '4907537',
					'creditCardNumberMasked' => 'XXXX'.$authnet_order['AuthnetProfile']['payment_cc_last_four'],
//					'order' => array(
//						'invoiceNumber' => 'INV000001',
//						'description' => 'description of transaction',
//						'purchaseOrderNumber' => 'PONUM000001'
//					),
					'transId' => $authnet_order['AuthnetOrder']['transaction_id'],
				)
			),
//			'extraOptions' => '<![CDATA[x_customer_ip=100.0.0.1]]>'
		);
		$authnet->createCustomerProfileTransactionRequest($refund_data);
		
		
		if ($authnet->isError()) {
			$response = $authnet->get_response();
//			$this->log($response, 'response');
			$this->major_error('Failed to refund order', compact('response'), 'high');
			
			return false;
		}
		
		
		$parsed_response = $authnet->get_parsed_response();
		$authnet_order['AuthnetOrder']['return_transaction_id'] = $parsed_response['transaction_id'];
		$this->save($authnet_order);
		
		return true;
	}
	
	public function one_time_charge($authnet_data) {
		$this->Cart = ClassRegistry::init('Cart');
		
		
		$order = array(
			'authnet_profile_id' => 0,
			'one_time_charge' => 1,
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
		
		$return_arr = array();
		$return_arr['success'] = true;
		$return_arr['code'] = '';
		$return_arr['declined'] = false;
		
		
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
			

			
			$authnet = $this->get_authnet_instance();
			$authnet->createTransactionRequest($charge_data);
			
			if ($authnet->isError()) {
				$return_arr['code'] = $authnet->messages->message->code[0];
				if ($authnet->messages->message->code[0] == 'I00002') {
					$return_arr['declined'] = true;
				} else {
					$this->major_error('Authorize.net one time charge failed', compact('charge_data'), 'high');
				}
				$this->delete($this->id);
				$return_arr['success'] = false;
			}
			
			
			return $return_arr;
		} else {
			$return_arr['success'] = false;
			$return_arr['code'] = 'I00003';
			$return_arr['declined'] = false;
			
			return $return_arr;
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
	public function createOrderForProfile($order) {
		//make sure all required items are there
		if ($this->_validate_order($order) === false) {
			return false;
		}
                
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
		
		// one time charge
		if (isset($order['one_time_charge'])) {
			$order_save_db['AuthnetOrder']['one_time_charge'] = $order['one_time_charge'];
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

		$authnet = $this->get_authnet_instance();
		try {
			$authnet->createCustomerProfileTransactionRequest($data_to_send);
			$full_parsed_result = $authnet->get_parsed_response();
//			$this->log($full_parsed_result, 'full_parsed_result');
			if ($authnet->isError()) {
				$returnArr['success'] = false;
				$returnArr['code'] = $authnet->get_code();
				$returnArr['message'] = $authnet->get_message();
				$this->authnet_error("request failed", compact('full_parsed_result'));
				return $returnArr;
			}
			
			

			if (isset($order['foreign_model'])) {
				$order_save_db['AuthnetOrder']['foreign_model'] = $order['foreign_model'];
			}

			if (isset($order['foreign_key'])) {
				$order_save_db['AuthnetOrder']['foreign_key'] = $order['foreign_key'];
			}
			$order_save_db['AuthnetOrder']['authnet_profile_id'] = $order['authnet_profile_id'];

			
			// add in extra order data (Andrew)
			$order_save_db['AuthnetOrder']['full_response'] = print_r($full_parsed_result, true);
			$order_save_db['AuthnetOrder']['transaction_id'] = $full_parsed_result['transaction_id'];
			
			
			
			$this->create();
			if ($this->save($order_save_db) == false) {
				$this->authnet_error('Could not save order', $order);
				return false;
			}
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