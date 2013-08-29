<?php


class Welcome extends AppModel {
	public $name = 'Welcome';
	public $useTable = false;

	
	
	public function welcome_email_hash_is_valid($email_hash) {
        $ch = curl_init(); 

		$url = Configure::read('OVERLORD_URL').'/fm_build/welcome_email_hash_is_valid/'.$email_hash;
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $json_output = json_decode(curl_exec($ch)); 
		
		

        curl_close($ch); 
		
		
		if ($json_output == true) {
			return true;
		} else {
			return false;
		}
	}
	
	public function site_is_built($email_hash) {
		$ch = curl_init(); 

		$url = Configure::read('OVERLORD_URL').'/fm_build/site_is_built/'.$email_hash;
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $json_output = json_decode(curl_exec($ch)); 
		

        curl_close($ch); 
		
		
		if ($json_output == true) {
			return true;
		} else {
			return false;
		}
	}
}