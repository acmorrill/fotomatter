<?php
class AuthnetOrder extends CakeAuthnetAppModel {

	public $name = 'AuthnetOrder';
	public $belongsTo = array('CakeAuthnet.AuthnetProfile');
	

	public $hasMany = array(
		'AuthnetLineItem',
	);
	
	private $transaction_data;
	private $order_data;
    
	
	private function get_authnet_order($authnet_order_id) {
		if (!isset($this->order_data[$authnet_order_id])) {
			$authnet_order = $this->find('first', array(
				'conditions' => array(
					'AuthnetOrder.id' => $authnet_order_id,
				),
				'contain' =>  false,
			));

			$this->order_data[$authnet_order_id] = $authnet_order;
		}

		if (empty($this->order_data[$authnet_order_id])) {
			return false;
		}
		
		return $this->order_data[$authnet_order_id];
	}
	
	public function order_status($authnet_order_id) {
		$authnet_order = $this->get_authnet_order($authnet_order_id);
			
		if (empty($authnet_order)) {
			return false;
		}
		
		return $authnet_order['AuthnetOrder']['order_status'];
	}
	
	public function get_authnet_transaction_data($authnet_order_id) {
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
		if (!isset($this->transaction_data[$authnet_order_id])) {
			$authnet = $this->get_authnet_instance();
		
			$authnet_order = $this->get_authnet_order($authnet_order_id);
			
			$authnet->getTransactionDetailsRequest(array(
				'transId' => $authnet_order['AuthnetOrder']['transaction_id'],
			));

			$response = $authnet->get_response();


			if ($authnet->isError() == true) {
				return false;
			}
			
			$this->transaction_data[$authnet_order_id] = $response->transaction;

			$transaction_data = $response->transaction;
		} else {
			$transaction_data = $this->transaction_data[$authnet_order_id];
		}
		
		
//		$this->log($transaction_data, 'transaction_data');
		
		
		return $transaction_data;
	}
	
	public function transaction_voidable($authnet_order_id) {
		$order_status = $this->order_status($authnet_order_id);
		if ($order_status !== 'new') {
			return false;
		}
		
		$details = $this->get_authnet_transaction_data($authnet_order_id);
		
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
	
	public function transaction_voided($authnet_order_id) {
		$details = $this->get_authnet_transaction_data($authnet_order_id);
		
		$voided_status = array(
			'voided',
		);
		
		if (!empty($details) && in_array($details->transactionStatus, $voided_status)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function transaction_refunded($authnet_order_id) {
		$authnet_order = $this->get_authnet_order($authnet_order_id);
		
		if (!empty($authnet_order['AuthnetOrder']['refund_transaction_id'])) {
			return true;
		} else {
			return false;
		}
//		$details = $this->get_authnet_transaction_data($authnet_order_id);
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
	
	public function transaction_refundable($authnet_order_id) {
		$order_status = $this->order_status($authnet_order_id);
		if ($order_status !== 'new') {
			return false;
		}
		
		// if already refunded then not refundable
		if ($this->transaction_refunded($authnet_order_id) === true) {
			return false;
		}

		
		$details = $this->get_authnet_transaction_data($authnet_order_id);
		
		
		$refundable_statuses = array(
			'settledSuccessfully',
		);
		
		if (!empty($details) && in_array($details->transactionStatus, $refundable_statuses)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function void_transaction($authnet_order_id) {
		$order_status = $this->order_status($authnet_order_id);
		if ($order_status !== 'new') {
			return false;
		}
		
		if ($this->transaction_voidable($authnet_order_id) === false) {
			return false;
		}
		
		
		$authnet = $this->get_authnet_instance();

		$authnet_order = $this->get_authnet_order($authnet_order_id);
		
		if ($authnet_order['AuthnetOrder']['one_time_charge'] == '0') {
			$authnet->createCustomerProfileTransactionRequest(array(
				'transaction' => array(
					'profileTransVoid' => array(
//						'customerProfileId' => $authnet_profile['AuthnetProfile']['customerProfileId'],
//						'customerPaymentProfileId' => $authnet_profile['AuthnetProfile']['customerPaymentProfileId'],
//						'customerShippingAddressId' => $authnet_profile['AuthnetProfile']['customerShippingAddressId'],
						'transId' => $authnet_order['AuthnetOrder']['transaction_id'],
					)
				),
//				'extraOptions' => '<![CDATA[x_customer_ip=100.0.0.1]]>'
			));
		} else {
			$authnet->createTransactionRequest(array(
//				'refId' => rand(1000000, 100000000),
				'transactionRequest' => array(
					'transactionType' => 'voidTransaction',
					'refTransId' => $authnet_order['AuthnetOrder']['transaction_id'],
				),
			));
			
			$one_time_void_response = $authnet->get_response();
			// DREW TODO - maybe we should add an explicit voided field in the order table?? (so don't have to query from now on to see status)
//			$this->log($one_time_void_response, 'one_time_void_response');
		}
		
		
		if ($authnet->isError()) {
			$response = $authnet->get_response();
			$this->major_error('Failed to void order', compact('response'), 'high');
			
			return false;
		}
		
		
		return true;
	}
	
	public function refund_transaction($authnet_order_id) {
		$order_status = $this->order_status($authnet_order_id);
		if ($order_status !== 'new') {
			return false;
		}
		
		if ($this->transaction_refundable($authnet_order_id) === false) {
			return false;
		}
		
		$authnet = $this->get_authnet_instance();

		$authnet_order = $this->get_authnet_order($authnet_order_id);
		
		
		if (empty($authnet_order)) {
			$this->major_error('Failed to refund a transaction because the authnet_order_id was incorrect.', compact('authnet_order_id'));
			return false;
		}
		
		if ($authnet_order['AuthnetOrder']['one_time_charge'] == '0') {
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
				$this->major_error('Failed to refund order 1', compact('response', 'refund_data'), 'high');

				return false;
			}
			
			
			$parsed_response = $authnet->get_parsed_response();
		} else {
			$one_time_refund_data = array(
				'transactionRequest' => array(
					'transactionType' => 'refundTransaction',
					'amount' => $authnet_order['AuthnetOrder']['total'],
					'payment' => array(
						'creditCard' => array(
							'cardNumber' => $authnet_order['AuthnetOrder']['payment_cc_last_four'], 
							'expirationDate' => $authnet_order['AuthnetOrder']['expiration_date'],
						)
					),
					'refTransId' => $authnet_order['AuthnetOrder']['transaction_id'],
				),
			);
			
			$authnet->createTransactionRequest($one_time_refund_data);
			
			if ($authnet->isError()) {
				$response = $authnet->get_response();
				$this->major_error('Failed to refund order 2', compact('response', 'one_time_refund_data'), 'high');

				return false;
			}
			
			$parsed_response = $authnet->get_one_time_parsed_response();
//			$this->log($parsed_response, 'one_time_refund_response');  
		}
		
		
		////////////////////////////////////////////////////////
		// save the refund transaction_id
		$authnet_order['AuthnetOrder']['refund_transaction_id'] = $parsed_response['transaction_id'];
		if (!$this->save($authnet_order)) {
			$this->major_error('Failed to record new refund_transaction_id', compact('parsed_response'), 'high');
			return false;
		}
		
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
			$error_response = $authnet->get_response();
			if ($authnet->messages->message->code[0] == 'I00002') {
				$return_arr['declined'] = true;
			} else {
				$this->major_error('Authorize.net one time charge failed', compact('charge_data', 'return_arr', 'error_response'), 'high');
			}
			$return_arr['success'] = false;
			
			return $return_arr;
		}

		
		// add the one time order trasaction_id to the order
		$one_time_response = $authnet->get_one_time_parsed_response();
		$one_time_response['expiration_date'] = date('mY', strtotime($authnet_data['AuthnetProfile']['payment_expirationDate']));
//		$this->log($response, 'get_one_time_parsed_response');
		
//		[response_code] => 1
//		[authorization_code] => G6CI52
//		[avs_response] => Y
//		[credit_card_validation_code_response] => P
//		[cavvResultCode] => 2
//		[transaction_id] => 2195858988
//		[transaction_hash] => 1924C07E36CB7CB39439C762A3560FD5
//		[test_request] => 0
//		[account_number] => XXXX3135
//		[account_type] => Visa
//		[expiration_date] => Visa

		
		///////////////////////////////////
		// start old create order
			$this->AuthnetProfile = ClassRegistry::init("AuthnetProfile");

			//build the order
			$order_save_db = array(); //data to be saved in order table

			$order_save_db['AuthnetOrder']['total'] = $order['total'];

			//tax?
			if (isset($order['tax'])) {
				$order_save_db['AuthnetOrder']['tax'] = $order['tax']['amount'];
			}

			// one time charge
			if (isset($order['one_time_charge'])) {
				$order_save_db['AuthnetOrder']['one_time_charge'] = $order['one_time_charge'];
			}

			//shipping?
			if (isset($order['shipping'])) {
				$order_save_db['AuthnetOrder']['shipping'] = $order['shipping']['amount'];
			}

			foreach ($order['line_items'] as $line_item) {
				$attach_to_order['itemId'] = $line_item['foreign_key'];
				$attach_to_order['name'] = $line_item['name'];
				$attach_to_order['description'] = $line_item['description'];
				$attach_to_order['quantity'] = $line_item['quantity'];
				$attach_to_order['unitPrice'] = $line_item['unit_cost'];
			}


			try {
				if (isset($order['foreign_model'])) {
					$order_save_db['AuthnetOrder']['foreign_model'] = $order['foreign_model'];
				}

				if (isset($order['foreign_key'])) {
					$order_save_db['AuthnetOrder']['foreign_key'] = $order['foreign_key'];
				}
				$order_save_db['AuthnetOrder']['authnet_profile_id'] = $order['authnet_profile_id'];


				// add in extra one time order data (Andrew)
				//		[response_code] => 1
				//		[authorization_code] => G6CI52
				//		[avs_response] => Y
				//		[credit_card_validation_code_response] => P
				//		[cavvResultCode] => 2
				//		[transaction_id] => 2195858988
				//		[transaction_hash] => 1924C07E36CB7CB39439C762A3560FD5
				//		[test_request] => 0
				//		[account_number] => XXXX3135
				//		[account_type] => Visa
				$order_save_db['AuthnetOrder']['full_response'] = print_r($one_time_response, true);
				if (isset($one_time_response['transaction_id'])) {
					$order_save_db['AuthnetOrder']['transaction_id'] = $one_time_response['transaction_id'];
				}
				if (isset($one_time_response['account_number'])) {
					$order_save_db['AuthnetOrder']['payment_cc_last_four'] = $one_time_response['account_number'];
				}
				if (isset($one_time_response['authorization_code'])) {
					$order_save_db['AuthnetOrder']['one_time_authorization_code'] = $one_time_response['authorization_code'];
				}
				if (isset($one_time_response['expiration_date'])) {
					$order_save_db['AuthnetOrder']['expiration_date'] = $one_time_response['expiration_date'];
				}




				$this->create();
				if ($this->save($order_save_db) == false) {
					$this->authnet_error('Could not save order', compact('order_save_db'));
					$return_arr['success'] = false;
					$return_arr['code'] = 'I00003';
					$return_arr['declined'] = false;

					return $return_arr;
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
						$return_arr['success'] = false;
						$return_arr['code'] = 'I00003';
						$return_arr['declined'] = false;

						return $return_arr;
					}
				}

			} catch (Exception $e) {
				$this->authnet_error('an exception has occurred', $e->getMessage());
				$return_arr['success'] = false;
				$return_arr['code'] = 'I00003';
				$return_arr['declined'] = false;

				return $return_arr;
			}
		// end old create order
		/////////////////////////////////////
		
		
		return $return_arr;
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
	public function createOrderForProfile($order, $validate_order = true, $createCIMProfile = true, $one_time_data = null) {
		//make sure all required items are there
		if ($validate_order === true && $this->_validate_order($order) === false) {
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

		try {
			if ($createCIMProfile === true) {
				$authnet = $this->get_authnet_instance();
				$authnet->createCustomerProfileTransactionRequest($data_to_send);
				if ($authnet->isError()) {
					$parsed_result = $authnet->get_response();
					$returnArr['success'] = false;
					$returnArr['code'] = $authnet->get_code();
					$returnArr['message'] = $authnet->get_message();
					$this->authnet_error("request failed", compact('parsed_result'));
					return $returnArr;
				}
				$full_parsed_result = $authnet->get_parsed_response();
			}
			
			

			if (isset($order['foreign_model'])) {
				$order_save_db['AuthnetOrder']['foreign_model'] = $order['foreign_model'];
			}

			if (isset($order['foreign_key'])) {
				$order_save_db['AuthnetOrder']['foreign_key'] = $order['foreign_key'];
			}
			$order_save_db['AuthnetOrder']['authnet_profile_id'] = $order['authnet_profile_id'];

			
			// add in extra one time order data (Andrew)
			//		[response_code] => 1
			//		[authorization_code] => G6CI52
			//		[avs_response] => Y
			//		[credit_card_validation_code_response] => P
			//		[cavvResultCode] => 2
			//		[transaction_id] => 2195858988
			//		[transaction_hash] => 1924C07E36CB7CB39439C762A3560FD5
			//		[test_request] => 0
			//		[account_number] => XXXX3135
			//		[account_type] => Visa
			$order_save_db['AuthnetOrder']['full_response'] = isset($full_parsed_result) ? print_r($full_parsed_result, true) : '';
			$order_save_db['AuthnetOrder']['transaction_id'] = isset($full_parsed_result['transaction_id']) ? $full_parsed_result['transaction_id'] : 0;
			if (isset($one_time_data['transaction_id'])) {
				$order_save_db['AuthnetOrder']['transaction_id'] = $one_time_data['transaction_id'];
			}
			if (isset($one_time_data['account_number'])) {
				$order_save_db['AuthnetOrder']['payment_cc_last_four'] = $one_time_data['account_number'];
			}
			if (isset($one_time_data['authorization_code'])) {
				$order_save_db['AuthnetOrder']['one_time_authorization_code'] = $one_time_data['authorization_code'];
			}
			if (isset($one_time_data['expiration_date'])) {
				$order_save_db['AuthnetOrder']['expiration_date'] = $one_time_data['expiration_date'];
			}
			
			
			
			
			$this->create();
			if ($this->save($order_save_db) == false) {
				$this->authnet_error('Could not save order', compact('order_save_db'));
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
	
	
	public function approve_order($authnet_order_id) {
		$authnet_order = $this->find('first', array(
			'conditions' => array(
				'AuthnetOrder.id' => $authnet_order_id,
			),
			'contain' => array(
				'AuthnetProfile',
				'AuthnetLineItem',
			),
		));
		
		$this->Photo = ClassRegistry::init('Photo');
		foreach ($authnet_order['AuthnetLineItem'] as &$line_item) {
			$extra_data = explode("|", $line_item['name']);
			$line_item['photo_id'] = $extra_data[0];
			$line_item['print_type_id'] = $extra_data[1];
			$line_item['short_side_inches'] = $extra_data[2];
			$line_item['extra_data'] = $this->Photo->get_extra_print_data($line_item['photo_id'], $line_item['print_type_id'], $line_item['short_side_inches']);
		}
		
		
		//////////////////////////////////////////////////////////
		// go through each line item and group them all by fulfillment type
		$items_by_fulfillment_type = array();
		foreach ($authnet_order['AuthnetLineItem'] as $a_line_item) {
			$fulfillment_type = ucwords($a_line_item['extra_data']['PhotoPrintType']['print_fulfillment_type']).'Fulfillment';
		
			$items_by_fulfillment_type[$fulfillment_type][] = $a_line_item;
		}
		
		
		//////////////////////////////////////////////////////////
		// go through each line item and approve each
		$approval_date = date('Y-m-d H:i:s');
		foreach ($items_by_fulfillment_type as $fulfillment_type => $line_items) {
			// DREW TODO - maybe make this more efficient? - but probobly ok since is just done occasionally
			// grab the filfillment component needed
			App::import('Component', $fulfillment_type);
			$new_obj_name = $fulfillment_type.'Component';
			$fulfillment_obj = new $new_obj_name();
			
			
			// call approve order for the line items of current type
			if (!$fulfillment_obj->approve_order_line_items($line_items, $approval_date)) {
				$this->major_error('Failed to approve an order', compact('authnet_order'), 'high');
				return false;
			}
		}
		
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// mark the order as approved  - assumes all order line items are approved (based on the above code working)
		// ie - if made it here then order is ok to be approved
		$authnet_order['AuthnetOrder']['order_status'] = 'approved';
		$authnet_order['AuthnetOrder']['approval_date'] = $approval_date;
		if (!$this->save($authnet_order)) {
			$this->major_error('Failed to set order status as approved', compact('authnet_order'), 'high');
			return false;
		}
		
		
		return true;
	}
	
	public function get_payable_orders() {
		/////////////////////
		// pay_out_statuses
		// -----------------------------
		// not_payed
		// processing
		// payed
		// failed
		
		$payable_orders = $this->find('all', array(
			'conditions' => array(
				'AuthnetOrder.order_status' => 'approved',
				'AuthnetOrder.pay_out_status' => 'not_payed',
				'AuthnetOrder.approval_date IS NOT NULL',
			),
			'contain' => false,
		));
		
		return $payable_orders;
	}
	
	public function verify_order_status($authnet_order_id) {
		// DREW TODO - finish this function if needed
	}
	
        
	/**
		* Make sure that we have good data passed to createOrder
		* @param type $order - data passed to createOrder
		* @return boolean
		*/
	private function _validate_order($order) {
		return true;
		
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