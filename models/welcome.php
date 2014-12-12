<?php

class Welcome extends AppModel {

	public $name = 'Welcome';
	public $useTable = false;

	public function welcome_email_hash_is_valid($email_hash) {
		$ch = curl_init();

		$url = 'https://' . Configure::read('OVERLORD_URL') . '/fm_build/welcome_email_hash_is_valid/' . $email_hash;

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CAINFO, '/etc/apache2/ssl/*.fotomatter.net.pem');
		curl_setopt($ch, CURLOPT_CAPATH, '/etc/ssl/certs');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

		$json_output = json_decode(trim(curl_exec($ch)));

		
		curl_close($ch);

		if ($json_output == true) {
			return true;
		} else {
			return false;
		}
	}
	
	public function welcome_email_hash_is_built_for_site($email_hash, $site_domain) {
		$ch = curl_init();

		$url = 'https://' . Configure::read('OVERLORD_URL') . "/fm_build/welcome_email_hash_is_built_for_site/$email_hash/" . urlencode($site_domain) . "/";
		$this->log($url, 'welcome_auto_login');
		$this->log('came here 4.8', 'welcome_auto_login');

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CAINFO, '/etc/apache2/ssl/*.fotomatter.net.pem');
		curl_setopt($ch, CURLOPT_CAPATH, '/etc/ssl/certs');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

		$json_output = json_decode(trim(curl_exec($ch)));

		
		curl_close($ch);

		if ($json_output == true) {
			return true;
		} else {
			return false;
		}
	}

	public function site_is_built($email_hash) {
		$ch = curl_init();

		$url = 'https://' . Configure::read('OVERLORD_URL') . '/fm_build/site_is_built/' . $email_hash;

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CAINFO, '/etc/apache2/ssl/*.fotomatter.net.pem');
		curl_setopt($ch, CURLOPT_CAPATH, '/etc/ssl/certs');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		$json_output = json_decode(trim(curl_exec($ch)));


		curl_close($ch);


		if ($json_output == false) {
			return false;
		} else {
			return $json_output;
		}
	}

}
