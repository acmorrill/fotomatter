<?php
class ThemeHelper extends AppHelper {
	
	public function get_theme_setting($name, $default = false) {
		$this->ThemeGlobalSetting = ClassRegistry::init('ThemeGlobalSetting', 'Model');
		
		return $this->ThemeGlobalSetting->getVal($name, $default);
	}
	
}