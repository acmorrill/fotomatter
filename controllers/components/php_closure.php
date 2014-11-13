<?php

define('LIB_DIR', ROOT . DS . APP_DIR . DS . 'vendor' . DS . 'php-closure' . DS . 'lib/');

class PhpClosureComponent extends Object {

	public function initialize(&$controller, $settings = array()) {
		$this->controller = $controller;
	}

	public function recompile_javascript() {
		///////////////////////////////////////////
		// recompile admin js
		$webroot_js_path = WEBROOT_ABS . DS . 'js' . DS . 'php_closure' . DS . 'fotomatter_admin.js';
		$php_closure_root_path = PHP_CLOSURE_ROOT;
		$this->compile_js_fromdir_todir($php_closure_root_path, $webroot_js_path);


		///////////////////////////////////////////
		// recompile theme js
		$top_level_themes_dir = scandir(PATH_TO_THEMES);
		foreach ($top_level_themes_dir as $curr_top_level_dir) {
			if ($curr_top_level_dir == '.' || $curr_top_level_dir == '..') {
				continue;
			}

			// DREW TODO - START HERE TOMORROW
			// recompile theme less css
//			$theme_webroot_css_path = PATH_TO_THEMES.DS.$curr_top_level_dir.DS.'webroot'.DS.'css';
//			$theme_less_css_root_path = PATH_TO_THEMES.DS.$curr_top_level_dir.DS.'lesscss';
//			if (is_dir($theme_webroot_css_path) && is_dir($theme_less_css_root_path)) {
//				$this->compile_less_fromdir_todir($theme_less_css_root_path, $theme_webroot_css_path);
//			}
			// recompile theme subtheme css
//			if (file_exists(PATH_TO_THEMES.DS.$curr_top_level_dir.DS.'subthemes')) {
//				$theme_sub_themes_path = PATH_TO_THEMES.DS.$curr_top_level_dir.DS.'subthemes';
//				$curr_bottom_level_themes_dir = scandir($theme_sub_themes_path);
//				foreach ($curr_bottom_level_themes_dir as $curr_bottom_level_theme) {
//					if ($curr_bottom_level_theme == '.' || $curr_bottom_level_theme == '..') {
//						continue;
//					}
//					
//					$sub_theme_dir = $theme_sub_themes_path.DS.$curr_bottom_level_theme;
//					
//					$sub_theme_webroot_css_path = $sub_theme_dir.DS.'webroot'.DS.'css';
//					$sub_theme_less_css_root_path = $sub_theme_dir.DS.'lesscss';
//					if (is_dir($sub_theme_webroot_css_path) && is_dir($sub_theme_less_css_root_path)) {
//						$this->compile_less_fromdir_todir($sub_theme_less_css_root_path, $sub_theme_webroot_css_path);
//					}
//				}
//			}
		}
	}

	private function compile_js_fromdir_todir($php_closure_root_path, $webroot_js_path) {
		$php_closure_object = $this->get_php_closure_object();
		
		$path_info = pathinfo($webroot_js_path);
		$dir_path = $path_info['dirname'] . DS;
		$compiled_path = $path_info['dirname'] . DS . $path_info['filename'] . ".min.js";
		$php_closure_object->cacheDir( $dir_path );
		

		$dir = new DirectoryIterator($php_closure_root_path);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->getExtension() == 'php') {
				$php_closure_file_full_path = $php_closure_root_path . DS . $fileinfo->getFilename();

				require($php_closure_file_full_path);
				if (empty($php_closure)) {
					$this->controller->major_error('Failed to load php_closure config', compact('php_closure_root_path', 'webroot_js_path'));
					return false;
				}

				foreach ($php_closure as $js_file_path) {
					$js_to_load_path = WEBROOT_ABS . DS . $js_file_path;
					if (file_exists($js_to_load_path)) {
						$php_closure_object->add($js_to_load_path);
					} else {
						$this->controller->major_error('error in php_closure config file', compact('php_closure_root_path', 'webroot_js_path'));
						return false;
					}
				}
				$cache_file = $php_closure_object->_getCacheFileName();
				if ($php_closure_object->_isRecompileNeeded($cache_file)) {
					$result = $php_closure_object->_compile();
					if ($result !== false) {
						file_put_contents($cache_file, $result);
						file_put_contents($compiled_path, $result);
					} else {
						$this->controller->major_error('Failed to recompile php_closure', compact('php_closure_root_path', 'webroot_js_path'));
						return false;
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
