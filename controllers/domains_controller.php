<?php

/**
 * Description of domains_controller
 *
 * @author aholsinger
 */
class DomainsController extends Appcontroller {

	public $uses = array('GlobalCountry', 'AccountDomain');
	public $layout = 'admin/accounts';
	public $components = array('NameCom', 'FotomatterBilling', 'FotomatterDomainManagement');
	public $paginate = array(
		'contain' => false,
		'limit' => 20,
		'order' => 'AccountDomain.created DESC'
	);

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// DOMAIN TESTING FUNCTIONS
	public function admin_hello() {
		print_r("<pre>" . print_r($this->NameCom->hello(), true) . "</pre>");
		exit();
	}

	public function admin_get_account() {
		print_r("<pre>" . print_r($this->NameCom->get_account(), true) . "</pre>");
		exit();
	}

	public function admin_list_domains() {
		print_r("<pre>" . print_r($this->NameCom->list_domains(), true) . "</pre>");
		exit();
	}

	public function admin_domain_get($domain_name) {
		print_r("<pre>" . print_r($this->NameCom->domain_get($domain_name), true) . "</pre>");
		exit();
	}

	public function admin_index() {
		$domains = $this->paginate('AccountDomain');
		$primary_domain = $this->AccountDomain->find('first', array(
			'conditions' => array(
				'AccountDomain.is_primary' => '1'
			),
			'contain' => false
		));
		$primary_domain_id = '';
		if (empty($primary_domain) === false) {
			$primary_domain_id = $primary_domain['AccountDomain']['id'];
		}
		
		$total_external_domains = $this->AccountDomain->find('count', array(
			'conditions' => array(
				'AccountDomain.type' => 'external',
			),
		));

		////////////////////////////////////////////////////////////
		// FOR DEBUGGING - ONLY IN DEBUG MODE
		$debugging = array();
		if (Configure::read('debug') > 0) {
			$debugging['hello'] = $this->NameCom->hello();
			$debugging['account'] = $this->NameCom->get_account();
			$debugging['list_domains'] = $this->NameCom->list_domains();
		}

		$this->set(compact(array('domains', 'primary_domain_id', 'debugging', 'total_external_domains')));
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

	public function admin_renew() {
		ignore_user_abort(true);
		set_time_limit(1200);
		$inputFromClient = $this->get_json_from_input();

		// sanitize the domain
		$inputFromClient['domain'] = strtolower(preg_replace('/[^a-zA-Z-]/', '', $inputFromClient['domain']));
		$inputFromClient['tld'] = strtolower(preg_replace('/[^a-zA-Z]/', '', $inputFromClient['tld']));

		//check input
		try {
			$this->Validation->validate('not_empty', $inputFromClient, 'domain', __('You must provide the domain name you want to renew.', true));
			$this->Validation->validate('not_empty', $inputFromClient, 'tld', __('You must provide the domain name you want to renew.', true));
		} catch (Exception $e) {
			$return['message'] = $e->getMessage();
			$return['result'] = false;
			$this->return_json($return);
			exit();
		}


		//double check domain owned by client and check price
		$full_domain_name = $inputFromClient['domain'] . "." . $inputFromClient['tld'];
		// 1) make sure they own the domain and that it can be renewed
			// - grab domain from db
		$account_domain = $this->AccountDomain->find('first', array(
			'conditions' => array(
				'AccountDomain.url' => $full_domain_name,
			),
			'contain' => false,
		));
		if (empty($account_domain['AccountDomain']['url']) || empty($account_domain['AccountDomain']['id'])) {
			$return['message'] = sprintf(__("You do not own the domain %s.", true), $full_domain_name);
			$return['result'] = false;
			$this->major_error('tried to renew a domain the client does not own', compact('account_domain', 'inputFromClient', 'full_domain_name'), 'high');
			$this->return_json($return);
			exit();
		}
		if ($account_domain['AccountDomain']['type'] !== 'purchased') {
			$return['message'] = __("You cannot renew an external domain.", true);
			$return['result'] = false;
			$this->major_error('tried to renew an external domain', compact('account_domain', 'inputFromClient', 'full_domain_name'), 'high');
			$this->return_json($return);
			exit();
		}
		
			// - make sure domain is not too old
		if (empty($account_domain['AccountDomain']['expires'])) {
			$return['message'] = sprintf(__("%s is expired.", true), $full_domain_name);
			$this->major_error('account domain expires empty on domain renew', compact('account_domain', 'inputFromClient', 'full_domain_name'), 'high');
			$return['result'] = false;
			$this->return_json($return);
			exit();
		}
		$days_till_expired = $this->AccountDomain->get_days_until_expired($account_domain['AccountDomain']['expires']);
		if ($days_till_expired < DOMAIN_MAX_DAYS_PAST_EXPIRE) {
			$return['message'] = sprintf(__("%s is expired. Please contact support at %s.", true), $full_domain_name, FOTOMATTER_SUPPORT_EMAIL);
			$this->major_error('tried to renew an expired domain', compact('days_till_expired', 'account_domain', 'inputFromClient', 'full_domain_name'), 'low');
			$return['result'] = false;
			$this->return_json($return);
			exit();
		}
			// - grab actual renew price
		$name_com_domain = $this->NameCom->domain_get($full_domain_name);
		if (empty($name_com_domain['addons']['domain/renew']) || empty($name_com_domain['expire_date'])) {
			$return['message'] = __("Cannot renew domain.", true);
			$this->major_error('tried to renew an and price did not come back', compact('name_com_domain', 'days_till_expired', 'account_domain', 'inputFromClient', 'full_domain_name'));
			$return['result'] = false;
			$this->return_json($return);
			exit();
		}

		
		// 2) charge to renew domain
		$domain_to_buy = array();
		$domain_to_buy['name'] = $full_domain_name;
		$domain_to_buy['price'] = ((int)$name_com_domain['addons']['domain/renew']) + DOMAIN_MARKUP_DOLLAR;
		$overlord_domain_charge_result = $this->FotomatterDomainManagement->charge_for_domain($domain_to_buy);
		if ($overlord_domain_charge_result['code'] == -2) {
			$this->major_error('failed to charge for domain renewal on overlord', compact('inputFromClient', 'domain_to_buy'), 'high');
			$this->system_domain_fail_generic();
			exit();
		} elseif ($overlord_domain_charge_result['code'] == -1) {
			$return['result'] = false;
			$return['message'] = __('Your credit card has been declined.', true);
			$this->return_json($return);
			exit();
		}

		
		// 3) renew domain on name.com
		if ($this->NameCom->renew_domain($full_domain_name) === false) {
			$this->major_error('failed to renew domain', compact('inputFromClient', 'domain_to_buy'), 'high');
			$this->system_domain_fail_generic();
			exit();
		}
		
		
		// 4) save domain to reset expire date
		$this->AccountDomain = ClassRegistry::init("AccountDomain");
		$this->AccountDomain->create();
		$domain_setup_overlord_info = array();
		$domain_setup_overlord_info['AccountDomain']['id'] = $account_domain['AccountDomain']['id'];
		$domain_setup_overlord_info['AccountDomain']['expires'] = date('y-m-d H:i:s', strtotime('+1 year', strtotime($name_com_domain['expire_date'])));
		if (!$this->AccountDomain->save($domain_setup_overlord_info)) {
			$this->major_error('failed to save account domain after renewal', compact('name_com_domain', 'domain_setup_overlord_info', 'inputFromClient'), 'high');
			$this->system_domain_fail_generic();
			exit();
		}



		$return = array();
		$return['result'] = true;
		$this->Session->setFlash(__('Your domain has been renewed.', true), 'admin/flashMessage/success');
		$this->return_json($return);
		exit();
	}
	
	public function admin_add_external_domain() {
		ignore_user_abort(true);
		set_time_limit(1200);
		$inputFromClient = $this->get_json_from_input();

		////////////////////////////////////////////////////////////////////////////////////////
		// check to make sure they are not trying to add more than 2 external domains
		$total_external_domains = $this->AccountDomain->find('count', array(
			'conditions' => array(
				'AccountDomain.type' => 'external',
			),
		));
		if ($total_external_domains > 2) {
			$this->major_error('tried to add more than 2 external domains', compact('inputFromClient'), 'high');
			$return['result'] = false;
			$return['message'] = __('You cannot connect more than 2 external domains.', true);
			$this->return_json($return);
			exit();
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// VALIDATE THE INPUT
		try {
			$this->Validation->validate('external_domain', $inputFromClient, 'domain', __('You must provide the domain name you want to purchase.', true));
		} catch (Exception $e) {
			$return['message'] = $e->getMessage();
			$return['result'] = false;
			$this->return_json($return);
			exit();
		}
		
		
		////////////////////////////////////////////////////////////////////////////////////////
		// make sure domain is not already in dns 
		if ($this->FotomatterDomainManagement->domain_taken_in_dns($inputFromClient['domain'])) {
			$this->major_error('tried to add domain dns that was already taken 1', compact('inputFromClient'), 'high');
			$return['result'] = false;
			$return['message'] = __('Failed to connect external domain to fotomatter.net.', true);
			$this->return_json($return);
			exit();
		}
		
		
		///////////////////////////////////////////////////////////////////////////////////////////
		// SETUP THE DOMAIN 
		// -- ADD DOMAIN SYMLINK VIA SERVER METRICS
		// -- ADD DOMAIN TO OVERLORD DNS (POINT DOMAIN TO OUR SERVER WITH A RECORD)
		// -- SAVE ACCOUNT DOMAIN AS EXTERNAL DOMAIN
		$domain['name'] = $inputFromClient['domain'];
		$domain_setup_overlord_info = $this->FotomatterDomainManagement->setupDomain($domain);
		if ($domain_setup_overlord_info === false) {
			$this->major_error('failed to setup external domain on overlord', compact('inputFromClient'), 'high');
			$return['result'] = false;
			$return['message'] = __('Failed to connect external domain to fotomatter.net.', true);
			$this->return_json($return);
			exit();
		}
		$this->AccountDomain = ClassRegistry::init("AccountDomain");
		$this->AccountDomain->create();
		//if we have no other AccountDomains saved then mark as primary
		$current_domain_count = $this->AccountDomain->find('count');
		if (empty($current_domain_count)) {
			$domain_setup_overlord_info['AccountDomain']['is_primary'] = true;
		}
		$domain_setup_overlord_info['AccountDomain']['type'] = 'external';
		if (!$this->AccountDomain->save($domain_setup_overlord_info)) {
			$this->major_error('failed to save external account domain', compact('domain_setup_overlord_info', 'inputFromClient'), 'high');
			$return['result'] = false;
			$return['message'] = __('Failed to connect external domain to fotomatter.net.', true);
			$this->return_json($return);
			exit();
		}
		
		
		// return a successfull result
		$return['result'] = true;
		$this->Session->setFlash(__('Your external domain has been connected. If you changed the domain dns nameservers to ns1.fotomatter.net and ns2.fotomatter.net then the domain should point to your fotomatter.net website in 1 to 48 hours.', true), 'admin/flashMessage/success');
		$this->return_json($return);
		exit();
	}
	
	public function admin_purchase() {
		ignore_user_abort(true);
		set_time_limit(1200);
		$inputFromClient = $this->get_json_from_input();

		// sanitize the domain
		$inputFromClient['domain'] = strtolower(preg_replace('/[^a-zA-Z-]/', '', $inputFromClient['domain']));
		$inputFromClient['tld'] = strtolower(preg_replace('/[^a-zA-Z]/', '', $inputFromClient['tld']));

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
			$this->Validation->validate('not_empty', $inputFromClient, 'domain', __('You must provide the domain name you want to purchase.', true));
			$this->Validation->validate('not_empty', $inputFromClient, 'tld', __('You must provide the domain name you want to purchase.', true));
		} catch (Exception $e) {
			$return['message'] = $e->getMessage();
			$return['result'] = false;
			$this->return_json($return);
			exit();
		}

		
		////////////////////////////////////////////////////////////////////////////////////////
		// make sure domain is not already in dns 
		if ($this->FotomatterDomainManagement->domain_taken_in_dns($inputFromClient['domain'])) {
			$this->major_error('tried to add domain dns that was already taken 2', compact('inputFromClient'), 'high');
			$return['result'] = false;
			$return['message'] = __('Failed to connect external domain to fotomatter.net.', true);
			$this->return_json($return);
			exit();
		}
		

		//double check domain is still avail, and check price
		$full_domain_name = $inputFromClient['domain'] . "." . $inputFromClient['tld'];
		$domain_data = $this->NameCom->check_availability($inputFromClient['domain'], $inputFromClient['tld']);

		$domain_to_buy = array();
		foreach ($domain_data['domain_list'] as $domain_name => $domain) {
			if ($domain_name == $full_domain_name && $domain['avail'] == 1) {
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
		
		////////////////////////////////////////////////////////////////////////////////////////
		// DREW TODO -  make sure domain is not already in dns 
		//$this->FotomatterDomainManagement->domain_taken_in_dns($domain);

		$overlord_domain_charge_result = $this->FotomatterDomainManagement->charge_for_domain($domain_to_buy);
		if ($overlord_domain_charge_result['code'] == -2) {
			$this->major_error('failed to charge for domain on overlord', compact('inputFromClient'), 'high');
			$this->system_domain_fail_generic();
			exit();
		} elseif ($overlord_domain_charge_result['code'] == -1) {
			$return['result'] = false;
			$return['message'] = __('Your credit card has been declined.', true);
			$this->return_json($return);
			exit();
		}
		if ($this->NameCom->buy_domain($inputFromClient['contact'], $domain_to_buy) === false) {
			$this->major_error('failed to buy domain on overlord 2', compact('inputFromClient'), 'high');
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

		if (!$this->AccountDomain->save($domain_setup_overlord_info)) {
			$this->major_error('failed to save account domain', compact('domain_setup_overlord_info', 'inputFromClient'), 'high');
			$this->system_domain_fail_generic();
			exit();
		}

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
		$this->log($this->params['form'], 'admin_search');
		if (isset($this->params['form']['domain']) && isset($this->params['form']['tld'])) {
			// sanitize the search
			$domain_name = strtolower(preg_replace('/[^a-zA-Z-]/', '', $this->params['form']['domain']));
			$tld = strtolower(preg_replace('/[^a-zA-Z]/', '', $this->params['form']['tld']));


			$domain_data = $this->NameCom->check_availability($domain_name, $tld);

			if (empty($domain_data['domain_list'])) {
				$domain_data['domain_list'] = array();
			}

			foreach ($domain_data['domain_list'] as $key => &$domain) {
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
	
	public function admin_add_external_domain_confirm() {
		$this->layout = 'ajax';
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

		$extra_domain_data = $this->NameCom->domain_get($account_domain['AccountDomain']['url']);
		$this->log($extra_domain_data, 'extra_domain_data');
		if (empty($extra_domain_data['addons']['domain/renew']['price']) || empty($extra_domain_data['expire_date'])) {
			$this->major_error('tried to add renew domain but failed to grab renew price', compact('extra_domain_data', 'account_domain', 'account_domain_id'), 'high');
			exit();
		}
		$account_domain['AccountDomain']['renew_price'] = $extra_domain_data['addons']['domain/renew']['price'];
		$account_domain['AccountDomain']['actual_expires'] = $extra_domain_data['expire_date'];
		$account_domain['AccountDomain']['renew_expires'] = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($extra_domain_data['expire_date'])));
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
				'conditions' => array(
					'AccountDomain.id' => $data_posted['primary_domain_id']
				),
				'contain' => false
			));
			if (empty($new_primary_domain)) {
				$this->major_error("Tried to set primary domain with id that doesn't exist, should never happen.", $data_posted);
				$this->return_json(array('code' => false));
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
				$this->return_json(array('code' => false));
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
		if (stripos($domain, 'http://') === 0) {
			$domain = substr($domain, 7);
		}
		if (stripos($domain, 'https://') === 0) {
			$domain = substr($domain, 8);
		}

		///Not even a single . this will eliminate things like abcd, since http://abcd is reported valid
		if (!substr_count($domain, '.')) {
			return false;
		}

		if (stripos($domain, 'www.') === 0) {
			$domain = substr($domain, 4);
		}

		$again = 'http://' . $domain;
		return filter_var($again, FILTER_VALIDATE_URL);
	}

	private function system_domain_fail_generic() {
		$return['result'] = false;
		$return['message'] = __('There was a problem with purchasing or renewing your domain. We have been notified about the problem and will work to correct it.', true);
		$this->return_json($return);
		exit();
	}

}
