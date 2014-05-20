<?php
/**
 * Description of domains_controller
 *
 * @author aholsinger
 */
class DomainsController extends Appcontroller {

	public $uses = array('GlobalCountry', 'AccountDomain');
	
	public $layout = 'admin/accounts';
	
	public $components = array('FotomatterDomain', 'FotomatterBilling', 'FotomatterDomainManagement');
	
	public $paginate = array(
		'contain'=>false,
		'limit'=>20,
		'order'=>'AccountDomain.created DESC'
	);
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// DOMAIN TESTING FUNCTIONS
	public function admin_hello() {
		print_r("<pre>".print_r($this->FotomatterDomain->hello(), true)."</pre>");
		exit();
	}
	public function admin_get_account() {
		print_r("<pre>".print_r($this->FotomatterDomain->get_account(), true)."</pre>");
		exit();
	}
	public function admin_list_domains() {
		print_r("<pre>".print_r($this->FotomatterDomain->list_domains(), true)."</pre>");
		exit();
	}
	public function admin_domain_get($domain_name) {
		print_r("<pre>".print_r($this->FotomatterDomain->domain_get($domain_name), true)."</pre>");
		exit();
	}
	
	
	
	public function admin_index() {
		$domains = $this->paginate('AccountDomain');
		$primary_domain = $this->AccountDomain->find('first', array(
			'conditions'=>array(
				'AccountDomain.is_primary' => '1'
			),
			'contain'=>false
		));
		$primary_domain_id = '';
		if(empty($primary_domain) === false) {
			$primary_domain_id = $primary_domain['AccountDomain']['id'];
		}
		
		$this->set(compact(array('domains', 'primary_domain_id')));
	}
	
	public function admin_add_profile() {
		$json_result = $this->get_json_from_input();
		$this->data = $json_result['data'];

		if (empty($this->data) === false) {
			try {
			   $this->validatePaymentProfile();
			} catch (Exception $e) {
			   $return['message'] = $e->getMessage();
			   $return['result'] = false;
			   $this->return_json($return);
			   exit();
			}
		}

		//Adam Todo consoldate logic with that in the accounts controller
		if (empty($this->data) == false) {
			   try {
				   $this->validatePaymentProfile();

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
		ignore_user_abort(true);
		set_time_limit(1200);
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
		$domain_data = $this->FotomatterDomain->check_availability($inputFromClient['domain']['name']);
		
		$domain_to_buy = array();
		foreach ($domain_data['domain_list'] as $domain_name => $domain) {
			if ($domain_name == $inputFromClient['domain']['name'] && $domain['avail'] == 1) {
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
			$this->major_error('failed to charge for domain on overlord', compact('inputFromClient'), 'high');
			$this->system_domain_fail_generic();
			exit();
		} elseif ($overlord_domain_charge_result === null) {
			$return['result'] = false;
			$return['message'] = __('Your credit card has been declined.', true);
			$this->return_json($return);
			exit();
		}
		
		if ($this->FotomatterDomain->buy_domain($inputFromClient['contact'], $domain_to_buy) === false) {
			$this->major_error('failed to buy domain on overlord', compact('inputFromClient'), 'high');
			$this->system_domain_fail_generic();
			exit();
		}

		$domain_setup_overlord_info = $this->FotomatterDomainManagement->setupDomain($domain_to_buy);
		if ($domain_setup_overlord_info === false) {
			$this->major_error('failed to setup domain on overlord', compact('inputFromClient'), 'high');
			$this->system_domain_fail_generic();
			exit();
		}
		$this->AccountDomain = ClassRegistry::init("AccountDomain");
		$this->AccountDomain->create();
		$domain_setup_overlord_info['AccountDomain']['expires'] = date('y-m-d H:i:s', strtotime('+1 year'));
		
		//if we have no other AccountDomains saved then mark as primary
		$current_domain_count = $this->AccountDomain->find('count');
		if (empty($current_domain_count)) {
			$domain_setup_overlord_info['AccountDomain']['is_primary'] = true;
		}
		
		$this->AccountDomain->save($domain_setup_overlord_info);
		
		$this->AccountSubDomain = ClassRegistry::init("AccountSubDomain");
		$this->AccountSubDomain->create();
		$domain_setup_overlord_info['AccountSubDomain']['account_domain_id'] = $this->AccountDomain->id;
		$this->AccountSubDomain->save($domain_setup_overlord_info);
		
		$return['result'] = true;
		$this->Session->setFlash(__('Your domain has been purchased, please allow 3-5 minutes for your new domain to be operational.', true), 'admin/flashMessage/success');
		$this->return_json($return);
		exit();
	}
	
	
	public function admin_search() {
		$this->params['form'] = $this->get_json_from_input();
		if (isset($this->params['form']['domain']) && isset($this->params['form']['tld'])) {
			// sanitize the search
			$domain_name = strtolower(preg_replace('/[^a-zA-Z-]/', '', $this->params['form']['domain']));
			$tld = strtolower(preg_replace('/[^a-zA-Z]/', '', $this->params['form']['tld']));
			
			
			$domain_data = $this->FotomatterDomain->check_availability($domain_name, $tld);

			foreach($domain_data['domain_list'] as $key => &$domain) {
				$domain['price'] += DOMAIN_MARKUP_DOLLAR;
			}
			$this->return_json($domain_data);
		}
		
		exit();
	}
	
	public function admin_domain_checkout() { 
		$this->layout = 'ajax';
		$countries = $this->GlobalCountry->get_available_countries();
		$this->set(compact(array('countries')));
	}
	
	public function admin_domain_renew_checkout($account_domain_id) { 
		$this->layout = 'ajax';
		
		$account_domain = $this->AccountDomain->find('first', array(
			'conditions' => array(
				'AccountDomain.id' => $account_domain_id,
			),
			'contain' => false,
		));
		
		if (empty($account_domain['AccountDomain']['url'])) {
			$this->major_error('tried to add renew domain  to checkout with bad id', compact('account_domain_id'), 'high');
			exit();
		}
		
		$extra_domain_data = $this->FotomatterDomain->domain_get($account_domain['AccountDomain']['url']);
		if (empty($extra_domain_data['addons']['domain/renew']['price']) || empty($extra_domain_data['expire_date'])) {
			$this->major_error('tried to add renew domain but failed to grab renew price', compact('extra_domain_data', 'account_domain', 'account_domain_id'), 'high');
			exit();
		}
		$account_domain['AccountDomain']['renew_price'] = $extra_domain_data['addons']['domain/renew']['price'];
		$account_domain['AccountDomain']['actual_expires'] = $extra_domain_data['expire_date'];
		$account_domain['AccountDomain']['renew_expires'] = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($extra_domain_data['expire_date'])));
		// START HERE TOMORROW
		$countries = $this->GlobalCountry->get_available_countries();
		$this->set(compact(array('countries'), 'account_domain'));
	}
	
	public function admin_get_account_details() {
		$return = array();
		$return['account_details'] = $this->FotomatterBilling->getAccountDetails();
		$return['domain_markup_price'] = DOMAIN_MARKUP_DOLLAR;
		$this->return_json($return);
		exit();
	}
	
	public function admin_set_as_primary() {
		$data_posted = $this->get_json_from_input();
		if (empty($data_posted['primary_domain_id']) === false) {
			$new_primary_domain = $this->AccountDomain->find('first', array(
				'conditions'=>array(
					'AccountDomain.id' => $data_posted['primary_domain_id']
				),
				'contain'=>false
			));
			if (empty($new_primary_domain)) {
				$this->major_error("Tried to set primary domain with id that doesn't exist, should never happen.", $data_posted);
				$this->return_json(array('code'=>false));
				exit();
			}
			
			$remove_primary_query = "
				UPDATE account_domains SET is_primary = 0
			";
			if ($this->AccountDomain->query($remove_primary_query) === false) {
				$this->major_error('Failed remove all domains primary', $new_primary_domain);
			}
			
			
			
			$new_primary_domain['AccountDomain']['is_primary'] = 1;
			$this->AccountDomain->create();
			if ($this->AccountDomain->save($new_primary_domain) === false) {
				$this->major_error('Tried to set new domain as primary but the save failed', $new_primary_domain);
				$this->return_json(array('code'=>false));
				exit();
			}
			$result['code'] = true;
			$result['message'] = 'New Domain set as primary';
			$this->return_json($result);
			exit();
		}
		header('HTTP/1.0 500 Internal Server Error');
		exit();
	}
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// PRIVATE FUNCTIONS
	private function filter_var_domain($domain) {
		if(stripos($domain, 'http://') === 0) {
			$domain = substr($domain, 7); 
		}
		if(stripos($domain, 'https://') === 0) {
			$domain = substr($domain, 8); 
		}

		///Not even a single . this will eliminate things like abcd, since http://abcd is reported valid
		if(!substr_count($domain, '.')) {
			return false;
		}

		if(stripos($domain, 'www.') === 0) {
			$domain = substr($domain, 4); 
		}

		$again = 'http://' . $domain;
		return filter_var ($again, FILTER_VALIDATE_URL);
	}
	
	private function system_domain_fail_generic() {
		$return['result'] = false;
		$return['message'] = __('There was a problem with purchasing your domain. We have been notified about the problem and will work to correct it.', true);
		$this->return_json($return);
		exit();
	}
}