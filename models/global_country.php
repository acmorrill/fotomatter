<?php

class GlobalCountry extends AppModel {
    public $name = 'GlobalCountry';
	public $useDbConfig = 'server_global';
	public $useTable = "countries";
	
	public $hasMany = array(
		'GlobalState',
	);
	
	public function get_country_id_by_name($country_name) {
		$country_data = $this->find('first', array(
			'conditions' => array(
				'GlobalCountry.country_name' => $country_name,
			),
			'contain' => false,
		));
		
		if (!empty($country_data['GlobalCountry']['id'])) {
			return $country_data['GlobalCountry']['id'];
		} else {
			return false;
		}
	}
	
}
