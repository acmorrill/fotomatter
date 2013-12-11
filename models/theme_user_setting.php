<?php
class ThemeUserSetting extends AppModel {
	public $name = 'ThemeUserSetting';
	public $belongsTo = array('Theme');
	
	
	public function getVal($name, $default = false, $theme_id = null) {
		if (empty($theme_id)) {
			$this->SiteSetting = ClassRegistry::init('SiteSetting');
			
			$current_theme_name = $this->SiteSetting->getVal('current_theme');
			$current_theme = $this->Theme->get_theme($current_theme_name);
			$theme_id = $current_theme['Theme']['id'];
		}
		
		$toSet = $this->find('first', array(
			'conditions' => array(
				'ThemeUserSetting.theme_id' => $theme_id,
				'ThemeUserSetting.name' => $name
			),
			'fields' => array('ThemeUserSetting.id', 'ThemeUserSetting.name', 'ThemeUserSetting.value')
		));

		return isset($toSet['ThemeUserSetting']['value']) ? $toSet['ThemeUserSetting']['value'] : $default;
	}

	public function setVal($name, $value, $theme_id = null) {
		if (empty($theme_id)) {
			$this->SiteSetting = ClassRegistry::init('SiteSetting');
			
			$current_theme_name = $this->SiteSetting->getVal('current_theme');
			$current_theme = $this->Theme->get_theme($current_theme_name);
			$theme_id = $current_theme['Theme']['id'];
			
		}
		
		
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