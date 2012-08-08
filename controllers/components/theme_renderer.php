<?php
class ThemeRendererComponent extends Object {
	
	public function initialize(&$controller, $settings=array()) {
		$this->controller = $controller;
	}
	
	public function startup(&$controller) {
		$this->controller = $controller;
		
		$theme_config = $this->_process_theme_config();
		
		// check to see if current action needs to have theme rendering done to it
		if (isset($theme_config['theme_controller_action_layouts'][$this->controller->name][$this->controller->action])) {
			$this->controller->layout = $theme_config['theme_controller_action_layouts'][$this->controller->name][$this->controller->action];
			
			$this->controller->theme_config = $theme_config;
			$this->controller->set(compact('theme_config'));
			
			$this->controller->render('/elements/empty_theme_page');
		}
	}
	

	public function shutdown(&$controller) {}
	
	public function beforeRedirect(&$controller, $url, $status=null, $exit) {}
	
	/**
	 * function read the default theme config and the current theme config - 
	 * --- then merge the configs and set view vars 
	 */
	private function _process_theme_config() {
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