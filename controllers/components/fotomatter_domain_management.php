<?php

require_once(ROOT . DS . 'app' . DS . 'controllers' . DS . 'components' . DS . 'fotomatter_overlord_api.php');

class FotomatterDomainManagementComponent extends FotomatterOverlordApi {

	public function __construct() {
		$this->server_url = 'https://' . Configure::read('OVERLORD_URL');
	}

	public function domain_taken_in_dns($domain) {
		$api_result = $this->send_api_request("api_domain/domain_taken_in_dns", $domain);
		
		return $api_result;
	}
	
	public function setupDomain($domain) {
		$api_result = $this->send_api_request("api_domain/setup", $domain);
		if ($api_result['code'] == 1) {
			return $api_result;
		}
		return false;
	}
	
	public function unsetup_domain($account_domain_id) {
		$api_result = $this->send_api_request("api_domain/unsetup", compact('account_domain_id'));
		if ($api_result['code'] == 1) {
			return $api_result;
		}
		return false;
	}

	////////////////////////////////////
	// charge domain codes
	// 1: success
	// -1: card declined
	// -2: other error
	public function charge_for_domain($domain) {
		$api_result = $this->send_api_request("api_domain/charge_domain", $domain);
		return $api_result;
	}

}
