<?php
class ThemeGlobalSetting extends AppModel {
	public $name = 'ThemeGlobalSetting';
	
	
	public function getVal ($name, $default = false) {
		$toSet = $this->find('first', array(
			'conditions' => array('name' => $name),
			'fields' => array('ThemeGlobalSetting.id', 'ThemeGlobalSetting.name', 'ThemeGlobalSetting.value')
		));

		return isset($toSet['ThemeGlobalSetting']['value']) ? $toSet['ThemeGlobalSetting']['value'] : $default;
	}

	public function setVal($name, $value) {
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