<?php
class ThemeHelper extends AppHelper {
	
	/**
	 *	If can't find the function try to call on model
	 * 
	 * @param type $method_name
	 * @param type $args
	 * @return type 
	 */
	function __call($method_name, $args) {
		$this->Theme = ClassRegistry::init('Theme');
		
		return call_user_func_array(array($this->Theme, $method_name), $args);
	}
	
	
	public function get_frontend_html_title() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting', 'Model');
		$first_name = $this->SiteSetting->getVal('first_name', '');
		$last_name = $this->SiteSetting->getVal('last_name', '');
		
		return "Photography by $first_name $last_name";
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
	
	public function get_theme_merged_background_abs_path($theme_name = null) {
		if (!isset($theme_name)) {
			$theme_name = $this->get_theme_name();
		}
		
		$this->Theme = ClassRegistry::init('Theme', 'Model');
		
		$the_uploaded_image_path = $this->Theme->get_theme_merged_background_abs_path($theme_name);
		
		if (!file_exists($the_uploaded_image_path)) {
			return false;
		}
		
		return $the_uploaded_image_path;
	}
	
	public function get_theme_merged_background_web_path($theme_name = null) {
		if (!isset($theme_name)) {
			$theme_name = $this->get_theme_name();
		}
		
		$this->Theme = ClassRegistry::init('Theme', 'Model');
		
		$the_merged_image_web_path = $this->Theme->get_theme_merged_background_web_path($theme_name);
		
		
		return $the_merged_image_web_path;
	}
	
	public function has_uploaded_custom_background($theme_name = null) {
		if (!isset($theme_name)) {
			$theme_name = $this->get_theme_name();
		}
		
		return file_exists(UPLOADED_BACKGROUND_PATH);
	}
	
	public function get_theme_name() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting', 'Model');
		
		return $this->SiteSetting->getVal('current_theme', false);
	}
	
	public function get_theme_global_setting($name, $default = false) {
		$this->ThemeGlobalSetting = ClassRegistry::init('ThemeGlobalSetting');
		return $this->ThemeGlobalSetting->getVal($name, $default);
	}
	
}