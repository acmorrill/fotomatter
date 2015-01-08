<?php
class ThemeRendererComponent extends Object {
	
	public function initialize(&$controller, $settings=array()) {
//		$this->controller = $controller;
	}
	
	public function startup(&$controller) {
		$theme_config = $this->_process_theme_config_with_user_settings();
//		print("<pre>" . print_r($theme_config, true) . "</pre>");
//		die();
		$theme_custom_settings = isset($theme_config['admin_config']['theme_avail_custom_settings']['settings']) ? $theme_config['admin_config']['theme_avail_custom_settings']['settings'] : array();

		// save the config for the view
			$controller->set(compact('theme_config', 'theme_custom_settings'));
		
			
		// check to see if current action needs to have theme rendering done to it
		$theme_layout_data = $theme_config['theme_controller_action_layouts'];
		
		if ($controller->is_mobile === true) {
			$theme_layout_data = $theme_config['theme_controller_action_mobile_layouts'];
		}
		if (isset($theme_layout_data[$controller->name][$controller->action])) {
			if (isset($theme_config['theme_include_helpers'])) {
				foreach ($theme_config['theme_include_helpers'] as $helper_name) {
					$controller->helpers[] = $helper_name;
				}
			}
			
			if ($theme_layout_data[$controller->name][$controller->action] === false) {
				// DREW TODO - we need to make a php page for 404s maybe
				header('HTTP/1.0 404 Not Found');
				exit();
			}
			//$theme_layout_data[$controller->name][$controller->action] = 'gallery';
			$layout_view_data = $theme_layout_data[$controller->name][$controller->action];
			$controller->layout = $layout_view_data['layout'];
			
			if (!empty($layout_view_data['view'])) {
				$controller->theme_view = $layout_view_data['view'];
			}
			
		}
		$controller->theme_config = $theme_config;
	}
	
	public function render(&$controller) {
		if (isset($controller->theme_view)) {
			$controller->render($controller->theme_view);
		} else {
			$controller->render('/elements/empty_theme_page');
		}
	}
	
	public function render_default(&$controller, $theme_view) {
		$theme_config = $this->_process_theme_config();
		if (!empty($theme_config['theme_controller_action_layouts']['Default']['layout'])) {
			$controller->layout = $theme_config['theme_controller_action_layouts']['Default']['layout'];
			$controller->theme_view = $theme_view;
		}
		
		$this->render($controller);
	}
	

	//public function shutdown(&$controller) {}
	
	//public function beforeRedirect(&$controller, $url, $status=null, $exit) {}
	
	/**
	 * public function read the default theme config and the current theme config - 
	 * --- then merge the configs and set view vars 
	 */
	public function _process_theme_config_with_user_settings($skip_cache = false) {
		$theme_config = $this->_process_theme_config($skip_cache);
		
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
			$global_settings = array();
			foreach ($avail_settings_list as $key => $curr_setting) {
				if (isset($settings_by_name[$key])) {
					$avail_settings_list[$key]['current_value'] = $settings_by_name[$key];
				} else if (isset($curr_setting['default_value'])) {
					$avail_settings_list[$key]['current_value'] = $curr_setting['default_value'];
				} else {
					$avail_settings_list[$key]['current_value'] = '';
				}
				
				
				if ($this->startsWith($key, 'global_')) {
					$global_settings[$key] = $avail_settings_list[$key];
					unset($avail_settings_list[$key]);
				}
			}
			foreach ($global_settings as $key => $global_setting) { // reverse the order of global settings
				$avail_settings_list[$key] = $global_setting;
			}
			$theme_config['admin_config']['theme_avail_custom_settings']['settings'] = $avail_settings_list;
		
		return $theme_config;
	}
	
	public function _process_theme_config($skip_cache = false) {
		if (!isset($this->merged_theme_config) || $skip_cache == true) {
			
			//////////////////////////////////////////////
			// grab the $default_theme_config
			$default_theme_config = array();
			require(DEFAULT_THEME_PATH.DS.'theme_config.php');
			if (isset($theme_config)) {
				$default_theme_config = $theme_config;
				unset($theme_config);
			}
			
			
			//////////////////////////////////////////////
			// grab the $current_theme_config
			$curr_theme_config_file_path = $GLOBALS['CURRENT_THEME_PATH'].DS.'theme_config.php';
			$current_theme_config = array();
			if (file_exists($curr_theme_config_file_path)) {
				require($curr_theme_config_file_path);
				if (isset($theme_config)) {
					$current_theme_config = $theme_config;
					unset($theme_config);
				}
			}
			
			
			///////////////////////////////////////////////////
			// if is third level theme
			// use merged parent theme config instead
			// of default theme config
			$this->Theme = ClassRegistry::init("Theme");
			if ($this->Theme->current_is_child_theme()) {
				$parent_theme_config_file_path = $GLOBALS['PARENT_THEME_PATH'].DS.'theme_config.php';
				$parent_theme_config = array();
				
				if (file_exists($parent_theme_config_file_path)) {
					require($parent_theme_config_file_path);
					if(isset($theme_config)) {
						$parent_theme_config = $theme_config;
						unset($theme_config);
					}
					$default_theme_config = $this->_merge_arrays($default_theme_config, $parent_theme_config);
				}
			} 

			
			// merge with global theme settings
			$this->merged_theme_config = $this->_merge_arrays($default_theme_config, $current_theme_config, true);
			$this->recursive_remove_override_able($this->merged_theme_config);
		}

		
		return $this->merged_theme_config;
	}
	
	private function recursive_remove_override_able(&$theme_configs) {
		if (isset($theme_configs['override_able'])) {
			unset($theme_configs['override_able']);
		}
		
		foreach ($theme_configs as $key => &$theme_config) {
			if (is_array($theme_config)) {
				$this->recursive_remove_override_able($theme_config);
			}
		}
	}
	
	////////////////////////////////////////////////////////
	// Merge $child_arr on top of $parent_arr
	//	override_able means that for that array a merge will not occur
	//	-- rather the child value wil override completely
	private function _merge_arrays($parent_arr, $child_arr, $unset_override_able_at_end = false) {
		foreach($child_arr as $key => $child_arr_value) {
			if (isset($parent_arr[$key]['override_able'])) {
				unset($parent_arr[$key]['override_able']);
				$parent_arr[$key] = $child_arr_value;
			} else {
				//////////////////////////////////////////////
				//	if key exists in parent array
				//	and the value of child is an array
				//		then recursively merge sub arrays
				//		otherwise use child value as value
				if (is_array($child_arr_value)) {
					if(isset($parent_arr[$key]) || array_key_exists($key, $parent_arr)) {
						$parent_arr[$key] = $this->_merge_arrays($parent_arr[$key], $child_arr[$key], $unset_override_able_at_end);
					} else {
						$parent_arr[$key] = $child_arr_value;
					}
				} else {
					// child_arr value and key added to parent (but what happens if the parent is not an array??)
					$parent_arr[$key] = $child_arr_value; 
				}
			}
		}

		
		return $parent_arr;
	}
	
	/*********************************************************
	* HELPER FUNCTIONS
	* 
	*/
	public function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	public function endsWith($haystack, $needle) {
		$length = strlen($needle);
		$start  = $length * -1; //negative
		return (substr($haystack, $start) === $needle);
	}
	
	
}