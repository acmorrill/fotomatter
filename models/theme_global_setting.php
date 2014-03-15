<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
// ThemeGlobalSetting is for settings that are global to all themes
class ThemeGlobalSetting extends AppModel {
	public $name = 'ThemeGlobalSetting';
	
	public function get_apc_key($name) {
		return "tgs_".$_SERVER['local']['database']."_{$name}";
	}
	
	public function getVal ($name, $default = false) {
		// check if the setting is stored in apc
		$apc_key = $this->get_apc_key($name);
		if (apc_exists($apc_key)) {
			$apc_data = apc_fetch($apc_key);
			if ($apc_data === SITE_SETTINGS_APC_DEFAULT_KEY) {
				return $default;
			}
			return $apc_data;
		}
		
		
		$toSet = $this->find('first', array(
			'conditions' => array('name' => $name),
			'fields' => array('ThemeGlobalSetting.id', 'ThemeGlobalSetting.name', 'ThemeGlobalSetting.value')
		));

		
		if (isset($toSet['ThemeGlobalSetting']['value'])) {
			apc_add($apc_key, $toSet['ThemeGlobalSetting']['value'], SITE_SETTINGS_APC_CACHE_TTL);
			return $toSet['ThemeGlobalSetting']['value'];
		} else {
			apc_add($apc_key, SITE_SETTINGS_APC_DEFAULT_KEY, SITE_SETTINGS_APC_CACHE_TTL);
			return $default;
		}
	}

	public function setVal($name, $value) {
		// delete apc key because we are doing a new value
		apc_delete($this->get_apc_key($name));
		
		$toSet = array(
			'ThemeGlobalSetting' => array(
				'name' => $name,
				'value' => $value
			)
		);

		$exists = $this->find('first', array(
			'conditions' => array('name' => $name)
		));

		if (!empty($exists['ThemeGlobalSetting']['id'])) {
			$toSet['ThemeGlobalSetting']['id'] = $exists['ThemeGlobalSetting']['id'];
		}

		$this->create();
		if (!$this->save($toSet)) {
			return false;
		} else {
			return true;
		}
	}
}