<?php
class FotoMatterOverlordApi extends Object {
	
	protected function send_api_request($api, $params=array()) {
		$request['Request']['data'] = $params;

		$url_to_use = $this->server_url . '/' .$api;
		$request['Request']['Server_params']['url'] = $url_to_use;
		$time_stamp = (string) time();
		$request['Request']['Server_params']['time_stamp'] = $time_stamp;

		$this->SiteSetting = ClassRegistry::init("SiteSetting");
		$site_key = $this->SiteSetting->getVal('site_domain');
		$request['Request']['key'] = $site_key;
		$request['Access']['signature'] = hash_hmac('sha256', json_encode($request['Request']), OVERLORD_API_KEY);
		

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL , $url_to_use);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CAINFO, '/etc/apache2/ssl/*.fotomatter.net.pem');
		curl_setopt($ch, CURLOPT_CAPATH, '/etc/ssl/certs');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',
			'API_SIGNATURE: '.$request['Access']['signature']
		));
		$json_response = curl_exec($ch);
		
		
		if ($json_response === false) {
			$curl_error = curl_error($ch);
			$curl_getinfo = curl_getinfo($ch);
            
			$this->MajorError = ClassRegistry::init('MajorError');
			$this->MajorError->major_error('api call to overlord failed', compact('curl_getinfo', 'url_to_use', 'request', 'curl_error'), 'high');
		}
		curl_close($ch);
		
		$response = json_decode($json_response, true);
		if (isset($response['code']) && $response['code'] != 1) {
			$this->MajorError = ClassRegistry::init('MajorError');
			$this->MajorError->major_error('api call to overlord returned with fail code', compact('url_to_use', 'request', 'json_response', 'response'), 'high');
		}
		
		return $response;
	}
}