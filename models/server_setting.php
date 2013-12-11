<?php
class ServerSetting extends AppModel {
	public $name = 'ServerSetting';
	public $useDbConfig = 'server_global';
	public $useTable = "server_settings";
	
	public function getVal ($name, $default = false) {
		$toSet = $this->find('first', array(
			'conditions' => array('name' => $name),
			'fields' => array('ServerSetting.id', 'ServerSetting.name', 'ServerSetting.value')
		));

		return isset($toSet['ServerSetting']['value']) ? $toSet['ServerSetting']['value'] : $default;
	}

	public function setVal($name, $value) {
		$toSet = array(
			'ServerSetting' => array(
				'name' => $name,
				'value' => $value
			)
		);

		$exists = $this->find('first', array(
			'conditions' => array('name' => $name)
		));

		if (!empty($exists['ServerSetting']['id'])) {
			$toSet['ServerSetting']['id'] = $exists['ServerSetting']['id'];
		}

		$this->create();
		if (!$this->save($toSet)) {
			return false;
		} else {
			return true;
		}
	}
}