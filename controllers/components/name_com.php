<?php

/**
 * Description of fotomatter_domain
 *
 * @author acmorrill
 */
class NameComComponent extends Object {

	private $_account;
	private $_api_token;
	private $_api_url;
	private $_dns_servers;
	private $_ttl_to_use = 3600;

	public function __construct() {
		App::import('Core', 'HttpSocket');
		$this->Http = new HttpSocket();

		// DREW TODO test real api
		if (false && Configure::read('debug') == 0) {
			$this->_account = 'acmorrill';
			$this->_api_token = '6fa1adbd6bf414426a84b3eed8fc57aaa69de8a8';
			$this->_api_url = 'https://api.name.com';
			$this->_dns_servers = array(
				'dns1.fotomatter.net',
				'dns2.fotmatter.net'
			);
		} else {
			$this->_account = 'acmorrill-ote';
			$this->_api_token = '30867e7ace1d5c9194aa176e2a2f416192a3af7f';
			$this->_api_url = 'https://api.dev.name.com';
			$this->_dns_servers = array(
				'ns1.name.com',
				'ns2.name.com'
			);
			// https://dev.name.com
			// dev1l3den 443
		}
	}

	public function hello() {
		$api_results = $this->_send_request("/api/hello", "GET");

		return $api_results;
	}

	public function get_account() {
		$api_results = $this->_send_request("/api/account/get", "GET");
		return $api_results;
	}

	public function list_domains() {
		$api_results = $this->_send_request("/api/domain/list", "GET");
		return $api_results;
	}

	public function domain_get($domain_name) {
		$api_results = $this->_send_request("/api/domain/get/$domain_name", "GET");

		if (empty($api_results['result']['code']) || $api_results['result']['code'] != 100) {
			return false;
		}
		return $api_results;
	}

	/**
	 * This funciton is used to check the availabiliity to check a single domain name
	 *  
	 * * @param type $domain_name The domain to check
	 * 
	 * If you pass a domain formated as str1.str2.com then it will check str2 (basically it just assumes that this is a subdomain and you really want to know about str2
	 * 
	 * VS
	 * 
	 * If you pass a domain formated as str2.com it will also check str2 
	 * 
	 * @return bool true of false depending on domain availability
	 * 
	 */
	public function check_availability($domain_name, $tld) {
		$tlds_list = array(
			'com',
			'net',
//			'org', // DREW TODO - these don't seem to work - need to test live
//			'me',
//			'biz',
		);
		$api_args = array(
			"keyword" => $domain_name,
			'tlds' => $tlds_list,
		);
		$api_results = $this->_send_request("/api/domain/check", 'POST', $api_args);
		if ($api_results['result']['code'] !== 100 || empty($api_results['domains'])) {
			return false;
		}

		$return_data = array();
		$return_data['domain_list'] = $api_results['domains'];


		$domain_searched = $domain_name . '.' . $tld;
		$return_data['domain_available'] = false;
		foreach ($return_data['domain_list'] as $domain_name => &$domain_info) {
			if ($domain_name == $domain_searched && $domain_info['avail'] == true) {
				$return_data['domain_available'] = true;
			}
		}


		return $return_data;
	}

	/**
	 * renew the domain for one year
	 * @param type $domain
	 */
	public function renew_domain($domain) {
		$order = array(
			'order_type' => 'domain/renew',
			'domain_name' => $domain,
			'period' => '1',
		);
		$api_args['items'][] = $order;
		$api_results = $this->_send_request("/api/order", "POST", $api_args);
		if (isset($api_results['result']['code']) && $api_results['result']['code'] == '100') {
			return true;
		}
		return false;
	}

	/**
	 * buy the following domain, if its avilable, and then save it associated to the account id passed
	 * @param account_id Id of the account that will own the domain
	 * @param the domain that we should attempt to buy
	 */
	public function buy_domain($contact_info, $domainObj) {
		$this->SiteSetting = ClassRegistry::init("SiteSetting");
		$site_email = $this->SiteSetting->getVal('account_email', '');

		//formulate the api call
		$order = array(
			'order_type' => 'domain/create',
			'domain_name' => $domainObj['name'],
			'nameservers' => $this->_dns_servers,
			'contacts' => array(
				'type' => array(
					'registrant', 'administrative', 'billing', 'technical'
				),
				'first_name' => $contact_info['first_name'],
				'last_name' => $contact_info['last_name'],
				'organization' => empty($contact_info['organization']) == false ? $contact_info['organization'] : '',
				'address_1' => $contact_info['address_1'],
				'address_2' => empty($contact_info['address_2']) == false ? $contact_info['address_2'] : '',
				'city' => $contact_info['city'],
				'state' => $contact_info['country_state_id'],
				'email' => $site_email,
				'phone' => $contact_info['phone'], //TODO fix this
				'fax' => empty($contact_info['fax']) == false ? $contact_info['fax'] : '',
				'country' => $contact_info['country_id']
			),
			'period' => 1,
		);
		$api_args['items'][] = $order;

		$api_results = $this->_send_request("/api/order", "POST", $api_args);
		if ($api_results['result']['code'] != '100') {
			return false;
		}
		return true;
	}

	/**
	 * @param String api_call String that represents the attempted api action.. (example /api/domain/create)
	 * @param String request_type GET or POST
	 * @param request_args array Parameter required for api call.. see docs here https://www.name.com/files/name_api_documentation.pdf
	 */
	private function _send_request($api_call, $request_type = 'GET', $request_args = array()) {
		$call_url = $this->_api_url . $api_call;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $call_url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request_type);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_args));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CAPATH, '/etc/ssl/certs');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		if (Configure::read('debug') > 0) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Api-Username: ' . $this->_account,
			'Api-Token: ' . $this->_api_token
		));
		$api_result_json = curl_exec($ch);

		if ($api_result_json === false) {
			$curl_error = curl_error($ch);
			$this->MajorError = ClassRegistry::init('MajorError');
			$this->MajorError->major_error('api call to name.com failed', compact('curl_error', 'call_url', 'api_result_json', 'api_call', 'request_type', 'request_args'), 'high');
			return array();
		}
		curl_close($ch);

		$api_result = json_decode($api_result_json, true);

		if (!isset($api_result['result']['code']) || $api_result['result']['code'] != 100) {
			$this->MajorError = ClassRegistry::init('MajorError');
			$this->MajorError->major_error('name.com api error', compact('call_url', 'api_result', 'api_result_json', 'api_call', 'request_type', 'request_args'), 'high');
		}

		return $api_result;
	}

}
