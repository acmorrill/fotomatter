<?php
class ThemeHelper extends AppHelper {
	
	public function get_theme_setting($name, $default = false) {
		$this->ThemeGlobalSetting = ClassRegistry::init('ThemeGlobalSetting', 'Model');
		
		return $this->ThemeGlobalSetting->getVal($name, $default);
	}
	
	public function get_theme_hidden_setting($name, $default = false) {
		$this->ThemeHiddenSetting = ClassRegistry::init('ThemeHiddenSetting', 'Model');
		
		return $this->ThemeHiddenSetting->getVal($name, $default);
	}

	public function get_theme_uploaded_background_abs_path($theme_name = null) {
		if (!isset($theme_name)) {
			$theme_name = $this->get_theme_name();
		}
		
		$this->Theme = ClassRegistry::init('Theme', 'Model');
		
		$the_uploaded_image_path = $this->Theme->get_theme_uploaded_background_abs_path($theme_name);
		
		if (!file_exists($the_uploaded_image_path)) {
			return false;
		}
		
		return $the_uploaded_image_path;
	}
	
	public function get_theme_uploaded_background_web_path($theme_name = null) {
		if (!isset($theme_name)) {
			$theme_name = $this->get_theme_name();
		}
		
		$this->Theme = ClassRegistry::init('Theme', 'Model');
		
		$the_uploaded_image_path = $this->Theme->get_theme_uploaded_background_abs_path($theme_name);
		$the_uploaded_image_web_path = $this->Theme->get_theme_uploaded_background_web_path($theme_name);
		
		if (!file_exists($the_uploaded_image_path)) {
			return false;
		}
		
		return $the_uploaded_image_web_path;
	}
	
	public function get_theme_name() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting', 'Model');
		
		return $this->SiteSetting->getVal('current_theme', false);
	}
	
}