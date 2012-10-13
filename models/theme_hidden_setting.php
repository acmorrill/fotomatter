<?php
class ThemeHiddenSetting extends AppModel {
	public $name = 'ThemeHiddenSetting';
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
				'ThemeHiddenSetting.theme_id' => $theme_id,
				'ThemeHiddenSetting.name' => $name
			),
			'fields' => array('ThemeHiddenSetting.id', 'ThemeHiddenSetting.name', 'ThemeHiddenSetting.value')
		));

		return isset($toSet['ThemeHiddenSetting']['value']) ? $toSet['ThemeHiddenSetting']['value'] : $default;
	}

	public function setVal($name, $value, $theme_id = null) {
		if (empty($theme_id)) {
			$this->SiteSetting = ClassRegistry::init('SiteSetting');
			
			$current_theme_name = $this->SiteSetting->getVal('current_theme');
			$current_theme = $this->Theme->get_theme($current_theme_name);
			$theme_id = $current_theme['Theme']['id'];
			
		}
		
		
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
		
		$this->log($theme_id, 'theme_id');
		$this->log($name, 'theme_id');
		$this->log($exists, 'theme_id');

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
}