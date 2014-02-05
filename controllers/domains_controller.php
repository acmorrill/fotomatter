<?php
/**
 * Description of domains_controller
 *
 * @author aholsinger
 */
class DomainsController extends Appcontroller {

	public $uses = array('GlobalCountry');
	
	public $layout = 'admin/accounts';
	
	public $components = array('FotomatterDomain', 'FotomatterBilling', 'FotomatterDomainManagement');
	
	
	public function admin_index() {
		
	}
	
	public function admin_add_profile() {
		$json_result = $this->get_json_from_input();
		$this->data = $json_result['data'];
		
		//Adam Todo consoldate logic with that in the accounts controller
       if (empty($this->data) == false) {
           try {
               $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_firstname', __('You must provide your first name.', true));
               $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_lastname', __('You must provide your last name.', true));
               $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_address', __('You must provide your address.', true));
               $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_city', __('You must provide your city.', true));
               
               $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_zip', __('You must provide your zip code.', true));
               $this->Validation->validate('valid_cc_no_type', $this->data['AuthnetProfile']['payment_cardNumber'], 'billing_cardNumber', __('Your credit card was not entered or not in a valid format.', true));
               $this->data['AuthnetProfile']['str_date'] =  $this->data['AuthnetProfile']['expiration']['month'] . '/31' . '/' . $this->data['AuthnetProfile']['expiration']['year'];
               $this->Validation->validate('date_is_future', $this->data['AuthnetProfile'], 'str_date', __('Your date provided was invalid or not in the future.', true)); //Ok in theory this should never be hit cause they are selects
               $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'payment_cardCode', __('Your csv code was either blank or invalid.', true));
           
           } catch (Exception $e) {
   			   $return['message'] = $e->getMessage();
			   $return['result'] = false;
               $this->return_json($return);
               exit();
           }
           
           $this->data['AuthnetProfile']['payment_cc_last_four'] = substr($this->data['AuthnetProfile']['payment_cardNumber'], -4, 4);
           $this->data['AuthnetProfile']['id'] = $this->FotomatterBilling->save_payment_profile($this->data);
		   
		   if ($this->data['AuthnetProfile']['id'] != false) {
			   $return['result'] = true;
			   $return['data'] = $this->data;
			   $return['message'] = "Profile saved";
			   $this->return_json($return);
			   exit();
		   } else {
			   $return['result'] = false;
			   $return['message'] = "unknown error";
			   $this->major_error('domains profile creation passed validation but failed overlord api call');
			   $this->return_json($return);
			   exit();
		   }
		   
       }
       $this->major_error('admin_ajax_save_client_billing was called without data');
       exit();
   }
   
	public function admin_purchase() {
		$inputFromClient = $this->get_json_from_input();

		//check input
		try {
			// $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_firstname', __('You must provide your first name.', true));
			$this->Validation->validate('not_empty', $inputFromClient['contact'], 'first_name', __('You must provide your first name.', true));
			$this->Validation->validate('not_empty', $inputFromClient['contact'], 'last_name', __('You must provide your last name.', true));
			$this->Validation->validate('not_empty', $inputFromClient['contact'], 'address_1', __('You must provide your address.', true));
			$this->Validation->validate('not_empty', $inputFromClient['contact'], 'country_id', __('You must provide your country.', true));
			$this->Validation->validate('not_empty', $inputFromClient['contact'], 'city', __('You must provide your city.', true));
			$this->Validation->validate('not_empty', $inputFromClient['contact'], 'zip', __('You must provide your zip.', true));
			$this->Validation->validate('not_empty', $inputFromClient['contact'], 'country_state_id', __('You must provide your state.', true));
			$this->Validation->validate('not_empty', $inputFromClient['contact'], 'phone', __('You must provide your phone.', true));
			$this->Validation->validate('not_empty', $inputFromClient['domain'], 'name', __('You must provide the domain name you want to purchase.', true)); 
		} catch(Exception $e) {
			$return['message'] = $e->getMessage();
			$return['result'] = false;
			$this->return_json($return);
			exit();
		}

		//double check domain is still avail, and check price
		$domain_list = $this->FotomatterDomain->check_availability($inputFromClient['domain']['name'], true);
		
		$domain_to_buy = array();
		foreach ($domain_list as $domain_name => $domain) {
			if ($domain_name == $inputFromClient['domain']['name']) {
				$domain_to_buy = $domain;
				$domain_to_buy['name'] = $domain_name;
				$domain_to_buy['price'] += DOMAIN_MARKUP_DOLLAR;
			}
		}
		if (empty($domain_to_buy)) {
			$return['result'] = false;
			$return['message'] = __('The domain you are trying to purchase is no longer available.', true);
			$this->return_json($return);
			exit();
		}

		$overlord_domain_charge_result = $this->FotomatterDomainManagement->charge_domain($domain_to_buy);
		if ($overlord_domain_charge_result === false) {
			$this->system_domain_fail_generic();
			exit();
		} elseif ($overlord_domain_charge_result === null) {
			$return['result'] = false;
			$return['message'] = __('Your credit card has been declined.', true);
			$this->return_json($return);
			exit();
		}
		
		if ($this->FotomatterDomain->buy_domain($inputFromClient['contact'], $domain_to_buy) === false) {
			$this->system_domain_fail_generic();
			exit();
		}

		if ($this->FotomatterDomainManagement->setupDomain($domain_to_buy) === false) {
			$this->system_domain_fail_generic();
			exit();
		}

		$return['result'] = true;
		$this->Session->setFlash(__('Your domain has been purchased, please allow 3-5 minutes for your new domain to be operational.', true), 'admin/flashMessage/success');
		$this->return_json($return);
		exit();
	}
	
	private function system_domain_fail_generic() {
		$return['result'] = false;
		$return['message'] = __('There was a problem with purchasing your domain. We have been notified about the problem and will work to correct it.', true);
		$this->return_json($return);
		exit();
	}
	
	public function admin_search() {
		$this->params['form'] = $this->get_json_from_input();
		if (isset($this->params['form']['q'])) {
			$domains = $this->FotomatterDomain->check_availability($this->params['form']['q'], true);

			foreach($domains as $key => $domain) {
				$domains[$key]['price'] += DOMAIN_MARKUP_DOLLAR;
			}
			$this->return_json($domains);
		}
		
		exit();
	}
	
	public function domain_checkout() {
		$this->layout = 'ajax';
		$countries = $this->GlobalCountry->get_available_countries();
		$this->set(compact(array('countries')));
	}
	
	public function get_account_details() {
		$return = array();
		$return['account_details'] = $this->FotomatterBilling->getAccountDetails();
		$return['domain_markup_price'] = DOMAIN_MARKUP_DOLLAR;
		$this->return_json($return);
		exit();
	}
}