<?php

class GlobalCountry extends AppModel {
    public $name = 'GlobalCountry';
	public $useDbConfig = 'server_global';
	public $useTable = "countries";
	
	public $hasMany = array(
		'GlobalCountryState',
	);
        
        public function get_available_countries() {
            $apc_key = 'available_countries';
            if (apc_exists($apc_key)) {
                    $countries = apc_fetch($apc_key);
            } else {
                    $this->GlobalCountry = ClassRegistry::init("GlobalCountry");

                    $countries = $this->GlobalCountry->find('all', array(
                            'contain' => false
                    ));
                    apc_store($apc_key, $countries, 60*60*24*7); // store for one week
            }

            return $countries;
        }
        
        
	
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
	
	public function get_country_name_by_id($country_id) {
		$country_data = $this->find('first', array(
			'conditions' => array(
				'GlobalCountry.id' => $country_id,
			),
			'contain' => false,
		));
		
		if (!empty($country_data['GlobalCountry']['country_name'])) {
			return $country_data['GlobalCountry']['country_name'];
		} else {
			return false;
		}
	}
	
}
