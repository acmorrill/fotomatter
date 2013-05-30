<?php
class AuthnetProfile extends CakeAuthnetAppModel {

	var $name = 'AuthnetProfile';
        
	
	/**
		* send profiles to be created
		* Date missing from shipping details will be filled in with billing details. Make sure this is intended behavior for your application
		* 
		* The save is expecting payment_cardNumber to create the profile
		* 
		* TODO:
		*    edit profiles
		*    
		*    */
	public function afterSave($created) {
		if ($created) {
			$this->process_new_profile();
		} else {
			$this->save_profile();
		}
	}


	private function save_profile() {
		if (empty($this->data['AuthnetProfile']['payment_cardNumber'])) {
			return true; //can't update without payment info so just store locally
		}

		if (empty($this->data['AuthnetProfile']['payment_expirationDate']) || empty($this->data['AuthnetProfile']['payment_cardCode'])) {
//                $this->authnet_error('Trying to save new profile information, cardNumber was set but exp date or code was not.', $this->data);
			return false;
		}


		$data_to_save = array(
//				'refId'=>$this->data['AuthnetProfile']['id'],
			'customerProfileId' => $this->data['AuthnetProfile']['customerProfileId'],
			'paymentProfile' => array(
				'billTo' => array(
					'firstName' => $this->data['AuthnetProfile']['billing_firstname'],
					'lastName' => $this->data['AuthnetProfile']['billing_lastname'],
					'address' => $this->data['AuthnetProfile']['billing_address'],
					'city' => $this->data['AuthnetProfile']['billing_city'],
					'state' => $this->data['AuthnetProfile']['billing_state'],
					'zip' => $this->data['AuthnetProfile']['billing_zip'],
					'country' => $this->data['AuthnetProfile']['billing_country'],
					'phoneNumber' => (isset($this->data['AuthnetProfile']['billing_phoneNumber'])) ? $this->data['AuthnetProfile']['billing_phoneNumber'] : '' ,
				),
				'payment' => array(
					'creditCard' => array(
						'cardNumber' => $this->data['AuthnetProfile']['payment_cardNumber'],
						'expirationDate' => date('Y-m', strtotime($this->data['AuthnetProfile']['payment_expirationDate']))
					) 
				),
				'customerPaymentProfileId' => $this->data['AuthnetProfile']['customerPaymentProfileId'],
			)
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
			return true;
		} catch (Exception $e) {
			$this->authnet_error('trying to resave profile info, unknown error', $e->getMessage());
			return false;
		}
	}

	private function process_new_profile() {
		$data_to_save = array(
				'profile' => array(
					'merchantCustomerId' => $this->id,
					//'email' => $email, // DREW TODO - maybe don't include this
					'paymentProfiles' => array(
						'billTo' => array(
							'firstName' => $this->data['AuthnetProfile']['billing_firstname'],
							'lastName' => $this->data['AuthnetProfile']['billing_lastname'],
							'address' => $this->data['AuthnetProfile']['billing_address'],
							'city' => $this->data['AuthnetProfile']['billing_city'],
							'state' => $this->data['AuthnetProfile']['billing_state'],
							'zip' => $this->data['AuthnetProfile']['billing_zip'],
							'country' => $this->data['AuthnetProfile']['billing_country'],
							'phoneNumber' => (isset($this->data['AuthnetProfile']['billing_phoneNumber'])) ? $this->data['AuthnetProfile']['billing_phoneNumber'] : '' ,
						),
						'payment' => array(
							'creditCard' => array(
								'cardNumber' => $this->data['AuthnetProfile']['payment_cardNumber'],
								'expirationDate' => date('Y-m', strtotime($this->data['AuthnetProfile']['payment_expirationDate'])),
								'cardCode' => $this->data['AuthnetProfile']['payment_cardCode']
							),
						),
					),
					'shipToList' => array(
						'firstName' => $this->data['AuthnetProfile']['shipping_firstname'],
						'lastName' => $this->data['AuthnetProfile']['shipping_lastname'],
						'address' => $this->data['AuthnetProfile']['shipping_address'],
						'city' => $this->data['AuthnetProfile']['shipping_city'],
						'state' => $this->data['AuthnetProfile']['shipping_state'],
						'zip' => $this->data['AuthnetProfile']['shipping_zip'],
						'country' => $this->data['AuthnetProfile']['shipping_country'],
					), 
				),
				'validationMode' => 'testMode' //Adam TODO what is this?
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


				if (!isset($response->customerProfileId) || !isset($response->customerPaymentProfileIdList->numericString)) {
						$returnArr['success'] = false;
						$returnArr['code'] = 0;
						$returnArr['message'] = "Expected fields not set";
						$this->authnet_error("Expected fields not set on CIM profile creation");
						return $returnArr;
				}

				$this->data['AuthnetProfile']['customerProfileId'] = (string) $response->customerProfileId;
				$this->data['AuthnetProfile']['customerPaymentProfileId'] = (string) $response->customerPaymentProfileIdList->numericString;
				$this->data['AuthnetProfile']['customerShippingAddressId'] = (string) $response->customerShippingAddressIdList->numericString;
				$this->save();

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
				'conditions'=>array(
					'AuthnetProfile.id'=>$this->id
				),
				'contain'=>false
			));

			$authnet->deleteCustomerProfileRequest(array(
				'customerProfileId'=>$this->data['AuthnetProfile']['customerProfileId']
			));
			$response = $authnet->get_response();
			if ($authnet->isError()) {
				return false;
			}
			return true;
	}

}
?>