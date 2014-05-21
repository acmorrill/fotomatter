<?php

require_once(ROOT . DS . 'app' . DS . 'controllers' . DS . 'components' . DS . 'fotomatter_overlord_api.php');

class FotomatterDomainManagementComponent extends FotoMatterOverlordApi {

	public function __construct() {
		$this->server_url = Configure::read('OVERLORD_URL');
	}

	public function setupDomain($domain) {
		$api_result = $this->send_api_request("api_domain/setup", $domain);
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
