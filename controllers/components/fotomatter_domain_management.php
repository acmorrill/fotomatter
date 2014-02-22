<?php
require_once(ROOT . DS . 'app' . DS. 'controllers' . DS . 'components' . DS . 'fotomatter_overlord_api.php');
class FotomatterDomainManagementComponent extends FotoMatterOverlordApi {
	
	public function __construct() {
        $this->server_url = Configure::read('OVERLORD_URL');
    }
	
	public function setupDomain($domain) {
		$api_result = json_decode($this->send_api_request("api_domain/setup", $domain), true);
		if ($api_result['code']) {
			return $api_result;
		}
		return false;
	}
	
	public function charge_domain($domain) {
		$api_result = json_decode($this->send_api_request("api_domain/charge_domain", $domain), true);
		
		if (empty($api_result['data']['AuthnetDomainOrder']['id']) === false) {
			return true;
		}
		return $api_result['data']['AuthnetDomainOrder']['id'];
	}
	
}