<?php

class GlobalCountryState extends AppModel {
    public $name = 'GlobalCountryState';
	public $useDbConfig = 'server_global';
	public $useTable = "country_states";
	
	public $belongsTo = array(
		'GlobalCountry',
	);
        
	public function get_states_by_country($country_id) {
		$apc_key = 'global_country_state_option_html_'.$country_id;
//		apc_clear_cache('user');
		if (apc_exists($apc_key)) {
			$states = apc_fetch($apc_key);
		} else {
			$states = $this->find('all', array(
				'conditions' => array(
					'GlobalCountryState.country_id' => $country_id
				),
				'contain' => false,
			));
                        
			apc_store($apc_key, $states, 60*60*24*7); // store for one week
		}
		
		return $states;
	}
        
        public function get_states_by_country_code($country_code) {
            $apc_key = 'global_country_code_'.$country_code;
            if (apc_exists($apc_key)) {
                $country_id = apc_fetch($apc_key);
            } else {
                $this->GlobalCountry = ClassRegistry::init("GlobalCountry");
                $country = $this->GlobalCountry->find('first', array(
                    'conditions'=>array(
                        'GlobalCountry.country_code_2'=>$country_code
                    )
                ));
                if (empty($country)) {
                    return array();
                }
                apc_store($apc_key, $country['GlobalCountry']['id'], 60*60*24*7);
                $country_id = $country['GlobalCountry']['id'];
            }
            
            return $this->get_states_by_country($country_id);
        }
}
