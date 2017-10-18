<?php

define('LIB_DIR', ROOT . DS . APP_DIR . DS . 'vendors' . DS . 'php-closure' . DS . 'lib/');

class PhpClosureComponent extends Object {

	public function initialize(&$controller, $settings = array()) {
		$this->controller = $controller;
	}

	public function recompile_javascript() {
		///////////////////////////////////////////
		// recompile admin js
		$webroot_js_path = WEBROOT_ABS . DS . 'js' . DS . 'php_closure' . DS;
		$php_closure_root_path = PHP_CLOSURE_ROOT;
		$this->compile_js_fromdir_todir($php_closure_root_path, $webroot_js_path, array( WEBROOT_ABS ));
		
		//TODO remove for prod, but useful during dev
		//unlink($webroot_js_path . "theme_global.min.js");


		///////////////////////////////////////////
		// recompile theme js
		$top_level_themes_dir = scandir(PATH_TO_THEMES);
		foreach ($top_level_themes_dir as $curr_top_level_dir) {
			if ($curr_top_level_dir == '.' || $curr_top_level_dir == '..') {
				continue;
			}

			// mixin js path for theme global
			$mixin_path = PHP_CLOSURE_ROOT . DS . 'theme_global.php';
			
			// recompile theme js
			$theme_webroot = PATH_TO_THEMES . DS . $curr_top_level_dir . DS . 'webroot';
			$theme_webroot_js_path = $theme_webroot . DS . 'js' . DS . 'php_closure' . DS;
			$theme_php_closure_root_path = PATH_TO_THEMES . DS . $curr_top_level_dir . DS . 'php_closure';
			if (is_dir($theme_webroot_js_path) && is_dir($theme_php_closure_root_path) && is_dir($theme_webroot)) {
				$this->compile_js_fromdir_todir($theme_php_closure_root_path, $theme_webroot_js_path, array( $theme_webroot, DEFAULT_THEME_WEBROOT_ABS, WEBROOT_ABS )/*, $mixin_path, WEBROOT_ABS*/); // DREW TODO - add mixin path to compile in if wanted
			}

			// recompile theme subtheme js
			if (file_exists(PATH_TO_THEMES . DS . $curr_top_level_dir . DS . 'subthemes')) {
				$theme_sub_themes_path = PATH_TO_THEMES . DS . $curr_top_level_dir . DS . 'subthemes';
				$curr_bottom_level_themes_dir = scandir($theme_sub_themes_path);
				foreach ($curr_bottom_level_themes_dir as $curr_bottom_level_theme) {
					if ($curr_bottom_level_theme == '.' || $curr_bottom_level_theme == '..') {
						continue;
					}
					$sub_theme_dir = $theme_sub_themes_path . DS . $curr_bottom_level_theme;
					
					$sub_theme_webroot = $sub_theme_dir . DS . 'webroot';
					$sub_theme_webroot_js_path = $sub_theme_webroot . DS . 'js' . DS . 'php_closure' . DS;
					$sub_theme_php_closure_root_path = $sub_theme_dir . DS . 'php_closure';
					if (is_dir($sub_theme_webroot_js_path) && is_dir($sub_theme_php_closure_root_path) && is_dir($sub_theme_webroot)) {
						$this->compile_js_fromdir_todir($sub_theme_php_closure_root_path, $sub_theme_webroot_js_path, array( $sub_theme_webroot, $theme_webroot, DEFAULT_THEME_WEBROOT_ABS, WEBROOT_ABS )/*, $mixin_path, WEBROOT_ABS*/); // DREW TODO - add mixin path to compile in if wanted
					}
				}
			}
		}
	}

	private function compile_js_fromdir_todir($php_closure_root_path, $webroot_dir_path, $webroot_root_paths, $mixin_path = '', $mixin_webroot_root_path = '') {
		$dir = new DirectoryIterator($php_closure_root_path);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->getExtension() == 'php') {
				$php_closure_object = $this->get_php_closure_object();
				$php_closure_object->cacheDir($webroot_dir_path);
				$files_added = false;
				
				
				/////////////////////////////////////////////////
				// add possible mixin files
				// mixins are only things that we will 
				// want to add to all compiles
				// not currently used
				if (!empty($mixin_path)) {
					require($mixin_path);
					if (!empty($php_closure)) {
						foreach ($php_closure as $js_file_path) {
							$js_to_load_path = $mixin_webroot_root_path . DS . $js_file_path;
							if (file_exists($js_to_load_path)) {
								$files_added = true;
								$php_closure_object->add($js_to_load_path);
							} else {
								$this->controller->major_error('error in php_closure config file 1', compact('php_closure_root_path', 'webroot_js_path'));
								continue;
							}
						}
					}
					unset($php_closure);
				}
				
				
				//////////////////////////////////////////
				// add files to compile queue
				$php_closure_file_full_path = $php_closure_root_path . DS . $fileinfo->getFilename();
				require($php_closure_file_full_path);
				if (!empty($php_closure)) {
					foreach ($php_closure as $js_file_path) {
						$js_to_load_path = '';
						foreach ($webroot_root_paths as $curr_path) {
							if (file_exists($curr_path . DS . $js_file_path)) {
								$js_to_load_path = $curr_path . DS . $js_file_path;
							}
						}
						
						if (!empty($js_to_load_path)) {
							$files_added = true;
							$php_closure_object->add($js_to_load_path);
						} else {
							$this->controller->major_error('error in php_closure config file 2', compact('php_closure_root_path', 'webroot_js_path'));
							continue;
						}
					}
				}

				
				////////////////////////////////////////////////////////////
				// compile files and put in correct location
				if ($files_added) {
					$cache_file = $php_closure_object->_getCacheFileName();
					if ($php_closure_object->_isRecompileNeeded($cache_file)) {
						$result = $php_closure_object->_compile();
						if ($result !== false) {
							$compiled_path = $webroot_dir_path . $fileinfo->getBasename('.php') . ".min.js";
							file_put_contents($cache_file, $result);
							file_put_contents($compiled_path, $result);
						} else {
							$this->controller->major_error('Failed to recompile php_closure', compact('php_closure_root_path', 'webroot_js_path'));
							continue;
						}
					}
				}
			}
		}

		return true;
	}

	private function get_php_closure_object() {
		App::import('Vendor', 'PhpClosure', array(
			'file' => 'php-closure' . DS . 'lib' . DS . 'third-party' . DS . 'php-closure.php'
		));

		$PhpClosure = new PhpClosure();
		$PhpClosure->simpleMode()->hideDebugInfo();

		return $PhpClosure;
	}

}
