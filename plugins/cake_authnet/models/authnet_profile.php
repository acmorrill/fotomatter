<?php

class AuthnetProfile extends CakeAuthnetAppModel {

	var $name = 'AuthnetProfile';

	// this was moved into authnet_order.php
//	public function one_time_charge($authnet_data) {
//		$change_data = array(
//			'refId' => rand(1000000, 100000000),
//			'transactionRequest' => array(
//				'transactionType' => 'authCaptureTransaction',
//				'amount' => 5,
//				'payment' => array(
//					'creditCard' => array(
//						'cardNumber' => '4111111111111111',
//						'expirationDate' => '122016',
//						'cardCode' => '999',
//					),
//				),
//				'order' => array(
//					'invoiceNumber' => '1324567890',
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
//				'shipping' => array(
//					'amount' => '4.26',
//					'name' => 'level2 tax name',
//					'description' => 'level2 tax',
//				),
//				'poNumber' => '456654',
//				'customer' => array(
//					'id' => '18',
//					'email' => 'someone@blackhole.tv',
//				),
//				'billTo' => array(
//					'firstName' => 'Ellen',
//					'lastName' => 'Johnson',
//					'company' => 'Souveniropolis',
//					'address' => '14 Main Street',
//					'city' => 'Pecan Springs',
//					'state' => 'TX',
//					'zip' => '44628',
//					'country' => 'USA',
//				),
//				'shipTo' => array(
//					'firstName' => 'China',
//					'lastName' => 'Bayles',
//					'company' => 'Thyme for Tea',
//					'address' => '12 Main Street',
//					'city' => 'Pecan Springs',
//					'state' => 'TX',
//					'zip' => '44628',
//					'country' => 'USA',
//				),
//				'customerIP' => '192.168.1.1',
//				'transactionSettings' => array(
//					'setting' => array(
//						0 => array(
//							'settingName' => 'allowPartialAuth',
//							'settingValue' => 'false'
//						),
//						1 => array(
//							'settingName' => 'duplicateWindow',
//							'settingValue' => '0'
//						),
//						2 => array(
//							'settingName' => 'emailCustomer',
//							'settingValue' => 'false'
//						),
//						3 => array(
//							'settingName' => 'recurringBilling',
//							'settingValue' => 'false'
//						),
//						4 => array(
//							'settingName' => 'testRequest',
//							'settingValue' => 'false'
//						)
//					)
//				),
//				'userFields' => array(
//					'userField' => array(
//						'name' => 'MerchantDefinedFieldName1',
//						'value' => 'MerchantDefinedFieldValue1',
//					),
//					'userField' => array(
//						'name' => 'favorite_color',
//						'value' => 'blue',
//					),
//				),
//			),
//		);
//
//
//		$authnet = $this->get_authnet_instance();
//		$authnet->createTransactionRequest($change_data);
//	}

	
	public function save_profile($authnet_data) {
		unset($authnet_data['AuthnetProfile']['created']);
		unset($authnet_data['AuthnetProfile']['modified']);
		
		if (empty($authnet_data['AuthnetProfile']['payment_cardNumber'])) {
			return true; //can't update without payment info so just store locally
		}

		if (empty($authnet_data['AuthnetProfile']['payment_expirationDate']) || empty($authnet_data['AuthnetProfile']['payment_cardCode'])) {
//                $this->authnet_error('Trying to save new profile information, cardNumber was set but exp date or code was not.', $authnet_data);
			return false;
		}


		$data_to_save = array(
//				'refId'=>$authnet_data['AuthnetProfile']['id'],
			'customerProfileId' => $authnet_data['AuthnetProfile']['customerProfileId'],
			'paymentProfile' => array(
				'billTo' => array(
					'firstName' => $authnet_data['AuthnetProfile']['billing_firstname'],
					'lastName' => $authnet_data['AuthnetProfile']['billing_lastname'],
					'address' => $authnet_data['AuthnetProfile']['billing_address'],
					'city' => $authnet_data['AuthnetProfile']['billing_city'],
					'state' => $authnet_data['AuthnetProfile']['billing_state'],
					'zip' => $authnet_data['AuthnetProfile']['billing_zip'],
					'country' => $authnet_data['AuthnetProfile']['billing_country'],
					'phoneNumber' => (isset($authnet_data['AuthnetProfile']['billing_phoneNumber'])) ? $authnet_data['AuthnetProfile']['billing_phoneNumber'] : '',
				),
				'payment' => array(
					'creditCard' => array(
						'cardNumber' => $authnet_data['AuthnetProfile']['payment_cardNumber'],
						'expirationDate' => date('Y-m', strtotime($authnet_data['AuthnetProfile']['payment_expirationDate']))
					)
				),
				'customerPaymentProfileId' => $authnet_data['AuthnetProfile']['customerPaymentProfileId'],
			),
			'validationMode' => 'liveMode' // this means that on CIM creation the card is actaully tested as well
		);


		$authnet = $this->get_authnet_instance();
		$returnArr = array();
		try {
			//   debug($data_to_save); die();
			$authnet->updateCustomerPaymentProfileRequest($data_to_save);
			if ($authnet->isError()) {
				$returnArr['success'] = false;
				$returnArr['code'] = $authnet->get_code();
				$returnArr['message'] = $authnet->get_message();
				$this->authnet_error("api request failed CIM profile edit", $authnet->get_response());
				return $returnArr;
			}
			
			$this->create();
			$this->save($authnet_data);
			return true;
		} catch (Exception $e) {
			$this->authnet_error('trying to resave profile info, unknown error', $e->getMessage());
			return false;
		}
	}

	public function process_new_profile($authnet_data) {
		unset($authnet_data['AuthnetProfile']['created']);
		unset($authnet_data['AuthnetProfile']['modified']);
		
		$this->User = ClassRegistry::init('User');
		$actual_user = $this->User->find('first', array(
			'conditions' => array(
				'User.id' => $authnet_data['AuthnetProfile']['user_id'],
			),
			'contain' => false,
		));
		
		
		// get the photographers info so we can path that along as well (for tracking)
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$site_domain = $this->SiteSetting->getVal('site_domain', 'not_found');
		

		$data_to_save = array(
			'profile' => array(
//					'merchantCustomerId' => substr($authnet_data['AuthnetProfile']['user_id'], 0, 20), // can't use this because only 20 characters long
				'description' => "frontendsale_{$site_domain}.fotomatter.net_" . $authnet_data['AuthnetProfile']['user_id'],
				'email' => $actual_user['User']['email_address'],
				'paymentProfiles' => array(
					'billTo' => array(
						'firstName' => $authnet_data['AuthnetProfile']['billing_firstname'],
						'lastName' => $authnet_data['AuthnetProfile']['billing_lastname'],
						'address' => $authnet_data['AuthnetProfile']['billing_address'],
						'city' => $authnet_data['AuthnetProfile']['billing_city'],
						'state' => $authnet_data['AuthnetProfile']['billing_state'],
						'zip' => $authnet_data['AuthnetProfile']['billing_zip'],
						'country' => $authnet_data['AuthnetProfile']['billing_country'],
						'phoneNumber' => (isset($authnet_data['AuthnetProfile']['billing_phoneNumber'])) ? $authnet_data['AuthnetProfile']['billing_phoneNumber'] : '',
					),
					'payment' => array(
						'creditCard' => array(
							'cardNumber' => $authnet_data['AuthnetProfile']['payment_cardNumber'],
							'expirationDate' => date('Y-m', strtotime($authnet_data['AuthnetProfile']['payment_expirationDate'])),
							'cardCode' => $authnet_data['AuthnetProfile']['payment_cardCode']
						),
					),
				),
				'shipToList' => array(
					'firstName' => $authnet_data['AuthnetProfile']['shipping_firstname'],
					'lastName' => $authnet_data['AuthnetProfile']['shipping_lastname'],
					'address' => $authnet_data['AuthnetProfile']['shipping_address'],
					'city' => $authnet_data['AuthnetProfile']['shipping_city'],
					'state' => $authnet_data['AuthnetProfile']['shipping_state'],
					'zip' => $authnet_data['AuthnetProfile']['shipping_zip'],
					'country' => $authnet_data['AuthnetProfile']['shipping_country'],
				),
			),
			'validationMode' => 'liveMode' // this means that on CIM creation the card is actually checked as well
		);
		$authnet = $this->get_authnet_instance();
		$returnArr = array();
		try {
			$authnet->createCustomerProfileRequest($data_to_save);
			if ($authnet->isError()) {
				$returnArr['success'] = false;
				$returnArr['code'] = $authnet->get_code();
				$returnArr['message'] = $authnet->get_message();
				$this->authnet_error("api request failed CIM profile creation", $authnet->get_response());
				return $returnArr;
			}

			$response = $authnet->get_response();
			$this->log($response, 'success_response');


			if (!isset($response->customerProfileId) || !isset($response->customerPaymentProfileIdList->numericString)) {
				$returnArr['success'] = false;
				$returnArr['code'] = 0;
				$returnArr['message'] = "Expected fields not set";
				$this->authnet_error("Expected fields not set on CIM profile creation");
				return $returnArr;
			}

			$authnet_data['AuthnetProfile']['customerProfileId'] = (string) $response->customerProfileId;
			$authnet_data['AuthnetProfile']['customerPaymentProfileId'] = (string) $response->customerPaymentProfileIdList->numericString;
			$authnet_data['AuthnetProfile']['customerShippingAddressId'] = (string) $response->customerShippingAddressIdList->numericString;
			$this->create();
			$this->save($authnet_data);
		} catch (Exception $e) {
			$returnArr['success'] = false;
			$returnArr['code'] = 0;
			$this->authnet_error("An unexpected error occured on CIM profile creation.");
			return $returnArr;
		}
	}

	public function beforeDelete($cascade) {
		$authnet = $this->get_authnet_instance();
		$this->data = $this->find('first', array(
			'conditions' => array(
				'AuthnetProfile.id' => $this->id
			),
			'contain' => false
		));

		$authnet->deleteCustomerProfileRequest(array(
			'customerProfileId' => $this->data['AuthnetProfile']['customerProfileId']
		));
		$response = $authnet->get_response();
		$this->log($response, 'response');
		
		
		if ($authnet->isError()) {
			return false;
		}
		return true;
	}

}

?>