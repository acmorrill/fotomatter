<?php
class ThemeRendererComponent extends Object {
	
	public function initialize(&$controller, $settings=array()) {
		$this->controller = $controller;
	}
	
	public function startup(&$controller) {
		$this->controller = $controller;
		
		$theme_config = $this->_process_theme_config();
		// add in the current theme settings to the config
			$avail_settings_list = $theme_config['admin_config']['theme_avail_custom_settings']['settings'];
			$this->Theme = ClassRegistry::init('Theme');
			$this->ThemeUserSetting = ClassRegistry::init('ThemeUserSetting');
			$theme_id = $this->Theme->get_current_theme_id();
			$theme_user_settings = $this->ThemeUserSetting->find('all', array(
				'conditions' => array(
					'ThemeUserSetting.theme_id' => $theme_id
				),
				'contain' => false
			));
			$settings_by_name = array();
			if (!empty($theme_user_settings)) {
				$settings_by_name = Set::combine($theme_user_settings, '{n}.ThemeUserSetting.name', '{n}.ThemeUserSetting.value');
			}
			foreach ($avail_settings_list as $key => $curr_setting) {
				if (isset($settings_by_name[$key])) {
					$avail_settings_list[$key]['current_value'] = $settings_by_name[$key];
				} else if (isset($curr_setting['default_value'])) {
					$avail_settings_list[$key]['current_value'] = $curr_setting['default_value'];
				} else {
					$avail_settings_list[$key]['current_value'] = '';
				}
			}
			$theme_config['admin_config']['theme_avail_custom_settings']['settings'] = $avail_settings_list;
		// save the config for the view
			$this->controller->set(compact('theme_config'));
		
			
		// check to see if current action needs to have theme rendering done to it
		if (isset($theme_config['theme_controller_action_layouts'][$this->controller->name][$this->controller->action])) {
			
			if (isset($theme_config['theme_include_helpers'])) {
				foreach ($theme_config['theme_include_helpers'] as $helper_name) {
					$this->controller->helpers[] = $helper_name;
				}
			}
			
			if ($theme_config['theme_controller_action_layouts'][$this->controller->name][$this->controller->action] === false) {
				// DREW TODO - we need to make a php page for 404s maybe
				header('HTTP/1.0 404 Not Found');
				exit();
			}
			
			$this->controller->layout = $theme_config['theme_controller_action_layouts'][$this->controller->name][$this->controller->action];
			
			$this->controller->theme_config = $theme_config;
		}
	}
	

	public function shutdown(&$controller) {}
	
	public function beforeRedirect(&$controller, $url, $status=null, $exit) {}
	
	/**
	 * public function read the default theme config and the current theme config - 
	 * --- then merge the configs and set view vars 
	 */
	public function _process_theme_config() {
		if (!isset($this->merged_theme_config)) {
			$default_theme_config = array();
			require_once(DEFAULT_THEME_PATH.DS.'theme_config.php');
			if (isset($theme_config)) {
				$default_theme_config = $theme_config;
				unset($theme_config);
			}

			$curr_theme_config_file_path = CURRENT_THEME_PATH.DS.'theme_config.php';
			$current_theme_config = array();
			if (file_exists($curr_theme_config_file_path)) {
				require_once($curr_theme_config_file_path);
				if (isset($theme_config)) {
					$current_theme_config = $theme_config;
					unset($theme_config);
				}
			}

			// merge with global theme settings
			$this->merged_theme_config = $this->_merge_arrays($default_theme_config, $current_theme_config);
		}

		
		// DREW TODO - add apc caching for the theme config here
		
		
		return $this->merged_theme_config;
	}
	
	private function _merge_arrays($Arr1, $Arr2) {
		foreach($Arr2 as $key => $Value) {
			if(array_key_exists($key, $Arr1) && is_array($Value)) {
				$Arr1[$key] = $this->_merge_arrays($Arr1[$key], $Arr2[$key]);
			} else {
				$Arr1[$key] = $Value;
			}
		}

		return $Arr1;
	}
	
}