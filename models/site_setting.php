<?php
class SiteSetting extends AppModel {
	public $name = 'SiteSetting';
	
	public function getVal ($name, $default = false) {
		$toSet = $this->find('first', array(
			'conditions' => array('name' => $name),
			'fields' => array('SiteSetting.id', 'SiteSetting.name', 'SiteSetting.value')
		));

		return isset($toSet['SiteSetting']['value']) ? $toSet['SiteSetting']['value'] : $default;
	}

	public function setVal($name, $value) {
		$toSet = array(
			'SiteSetting' => array(
				'name' => $name,
				'value' => $value
			)
		);

		$exists = $this->find('first', array(
			'conditions' => array('name' => $name)
		));

		if (!empty($exists['SiteSetting']['id'])) {
			$toSet['SiteSetting']['id'] = $exists['SiteSetting']['id'];
		}

		$this->create();
		if (!$this->save($toSet)) {
			return false;
		} else {
			return true;
		}
	}
}