<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// theme hidden settings is for theme settings that are per theme and are user defined/controlled
class ThemeUserSetting extends AppModel {
	public $name = 'ThemeUserSetting';
	public $belongsTo = array('Theme');
	
	public function get_apc_key($name, $theme_id) {
		return "hus_".$_SERVER['local']['database']."_{$name}_{$theme_id}";
	}
	
	public function getVal($name, $default = false, $theme_id = null) {
		if (empty($theme_id)) {
			$this->SiteSetting = ClassRegistry::init('SiteSetting');
			
			$current_theme_name = $this->SiteSetting->getVal('current_theme');
			$current_theme = $this->Theme->get_theme($current_theme_name);
			$theme_id = $current_theme['Theme']['id'];
		}
		
		// check if the setting is stored in apc
		$apc_key = $this->get_apc_key($name, $theme_id);
		if (apc_exists($apc_key)) {
			$apc_data = apc_fetch($apc_key);
			if ($apc_data === SITE_SETTINGS_APC_DEFAULT_KEY) {
				return $default;
			}
			return $apc_data;
		}
		
		$toSet = $this->find('first', array(
			'conditions' => array(
				'ThemeUserSetting.theme_id' => $theme_id,
				'ThemeUserSetting.name' => $name
			),
			'fields' => array('ThemeUserSetting.id', 'ThemeUserSetting.name', 'ThemeUserSetting.value')
		));

		if (isset($toSet['ThemeUserSetting']['value'])) {
			apc_add($apc_key, $toSet['ThemeUserSetting']['value'], SITE_SETTINGS_APC_CACHE_TTL);
			return $toSet['ThemeUserSetting']['value'];
		} else {
			apc_add($apc_key, SITE_SETTINGS_APC_DEFAULT_KEY, SITE_SETTINGS_APC_CACHE_TTL);
			return $default;
		}
	}

	public function setVal($name, $value, $theme_id = null) {
		if (empty($theme_id)) {
			$this->SiteSetting = ClassRegistry::init('SiteSetting');
			
			$current_theme_name = $this->SiteSetting->getVal('current_theme');
			$current_theme = $this->Theme->get_theme($current_theme_name);
			$theme_id = $current_theme['Theme']['id'];
			
		}
		// delete apc key because we are doing a new value
		apc_delete($this->get_apc_key($name, $theme_id));
		
		
		$toSet = array(
			'ThemeUserSetting' => array(
				'theme_id' => $theme_id,
				'name' => $name,
				'value' => $value
			)
		);
		
		$exists = $this->find('first', array(
			'conditions' => array(
				'ThemeUserSetting.theme_id' => $theme_id,
				'ThemeUserSetting.name' => $name
			),
			'contain' => false
		));
		

		if (!empty($exists['ThemeUserSetting']['id'])) {
			$toSet['ThemeUserSetting']['id'] = $exists['ThemeUserSetting']['id'];
		}

		
		
		$this->create();
		if (!$this->save($toSet)) {
			return false;
		} else {
			return true;
		}
	}
}