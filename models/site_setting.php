<?php
class SiteSetting extends AppModel {
	public $name = 'SiteSetting';
	
	public function get_apc_key($name) {
		return "ss_".$_SERVER['local']['database']."_{$name}";
	}
	
	public function getImageContainerUrl($force_type = null) {
		$is_ssl = false;
		if (!empty($_SERVER['HTTPS'])) {
			$is_ssl = true;
		}
		if ($force_type === 'nonssl') {
			$is_ssl = false;
		}
		if ($force_type === 'ssl') {
			$is_ssl = true;
		}
		
		if ($is_ssl) {
			$imageContainerUrl = $this->getVal('image-container-secure_url', '');
		} else {
			$imageContainerUrl = $this->getVal('image-container-url', '');
		}
		
		return trim($imageContainerUrl, '/').'/';
	}
	
	public function get_site_default_container_url() {
		if (empty($_SERVER['HTTPS'])) {
			$imageContainerUrl = SITE_DEFAULT_CONTAINER_URL;
		} else {
			$imageContainerUrl = SITE_DEFAULT_CONTAINER_SECURE_URL;
		}
		
		return trim($imageContainerUrl, '/').'/';
	}
	
	public function clearVal($name) {
		apc_delete($this->get_apc_key($name));
		$this->query("DELETE FROM site_settings WHERE name = '$name'");
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
			'fields' => array('SiteSetting.id', 'SiteSetting.name', 'SiteSetting.value')
		));

		if (isset($toSet['SiteSetting']['value'])) {
			apc_add($apc_key, $toSet['SiteSetting']['value'], SITE_SETTINGS_APC_CACHE_TTL);
			return $toSet['SiteSetting']['value'];
		} else {
			apc_add($apc_key, SITE_SETTINGS_APC_DEFAULT_KEY, SITE_SETTINGS_APC_CACHE_TTL);
			return $default;
		}
	}

	public function setVal($name, $value) {
		// delete apc key because we are doing a new value
		apc_delete($this->get_apc_key($name));
		
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