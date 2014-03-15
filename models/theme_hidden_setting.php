<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// theme hidden settings is for theme settings that are per theme, but are not user controlled
class ThemeHiddenSetting extends AppModel {
	public $name = 'ThemeHiddenSetting';
	public $belongsTo = array('Theme');
	

	public function get_apc_key($name, $theme_id) {
		return "hts_".$_SERVER['local']['database']."_{$name}_{$theme_id}";
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
				'ThemeHiddenSetting.theme_id' => $theme_id,
				'ThemeHiddenSetting.name' => $name
			),
			'fields' => array('ThemeHiddenSetting.id', 'ThemeHiddenSetting.name', 'ThemeHiddenSetting.value')
		));

		if (isset($toSet['ThemeHiddenSetting']['value'])) {
			apc_add($apc_key, $toSet['ThemeHiddenSetting']['value'], SITE_SETTINGS_APC_CACHE_TTL);
			return $toSet['ThemeHiddenSetting']['value'];
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
			'ThemeHiddenSetting' => array(
				'theme_id' => $theme_id,
				'name' => $name,
				'value' => $value
			)
		);

		$exists = $this->find('first', array(
			'conditions' => array(
				'ThemeHiddenSetting.theme_id' => $theme_id,
				'ThemeHiddenSetting.name' => $name
			),
			'contain' => false
		));
		
		if (!empty($exists['ThemeHiddenSetting']['id'])) {
			$toSet['ThemeHiddenSetting']['id'] = $exists['ThemeHiddenSetting']['id'];
		}

		$this->create();
		if (!$this->save($toSet)) {
			return false;
		} else {
			return true;
		}
	}
	
	public function clearVal($name, $theme_id = null) {
		if (empty($theme_id)) {
			$this->SiteSetting = ClassRegistry::init('SiteSetting');
			
			$current_theme_name = $this->SiteSetting->getVal('current_theme');
			$current_theme = $this->Theme->get_theme($current_theme_name);
			$theme_id = $current_theme['Theme']['id'];
		}
		// delete apc key because we are doing a new value
		apc_delete($this->get_apc_key($name, $theme_id));
		
		return $this->deleteAll(array(
			'ThemeHiddenSetting.theme_id' => $theme_id,
			'ThemeHiddenSetting.name' => $name,
		), false, false);
	}
	
	public function clear_theme_background_position_cache() {
		$this->clearVal('default_admin_current_background_left');
		$this->clearVal('default_admin_current_background_top');
		$this->clearVal('default_admin_current_background_width');
		$this->clearVal('default_admin_current_background_height');
		$this->clearVal('uploaded_admin_current_background_left');
		$this->clearVal('uploaded_admin_current_background_top');
		$this->clearVal('uploaded_admin_current_background_width');
		$this->clearVal('uploaded_admin_current_background_height');
	}
	
	
}