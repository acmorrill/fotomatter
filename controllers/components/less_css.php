<?php
class LessCssComponent extends Object {
	
	public function initialize(&$controller, $settings=array()) {
		$this->controller = $controller;
	}
	
	public function recompile_css() {
		// get lessphp
		App::import('Vendor', 'LessPhp', array('file' => 'lessphp'.DS.'lessc.inc.php'));
		$this->LessPhp = new lessc();
		
		
//		///////////////////////////////////////////
		// recompile admin css
		$webroot_css_path = WEBROOT_ABS.DS.'css';
		$less_css_root_path = LESSCSS_ROOT;
		$dir = new DirectoryIterator($less_css_root_path);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->getExtension() == 'less') {
				$less_file_full_path = $less_css_root_path.DS.$fileinfo->getFilename();
				$new_css_full_path = $webroot_css_path.DS.$fileinfo->getBasename('.less').'.css';
				
				$this->LessPhp->checkedCompile($less_file_full_path, $new_css_full_path);
			}
		}
		
		
		// recompile theme css
		$top_level_themes_dir = scandir(PATH_TO_THEMES);
		foreach ($top_level_themes_dir as $curr_top_level_dir) {
			if ($curr_top_level_dir == '.' || $curr_top_level_dir == '..') {
				continue;
			}
			
			
			// recompile theme less css
			$theme_webroot_css_path = PATH_TO_THEMES.DS.$curr_top_level_dir.DS.'webroot'.DS.'css';
			$theme_less_css_root_path = PATH_TO_THEMES.DS.$curr_top_level_dir.DS.'lesscss';
			if (is_dir($theme_webroot_css_path) && is_dir($theme_less_css_root_path)) {
				$dir = new DirectoryIterator($theme_less_css_root_path);
				foreach ($dir as $fileinfo) {
					if ($fileinfo->getExtension() == 'less') {
						$theme_less_file_full_path = $theme_less_css_root_path.DS.$fileinfo->getFilename();
						$theme_new_css_full_path = $theme_webroot_css_path.DS.$fileinfo->getBasename('.less').'.css';

						$this->LessPhp->checkedCompile($theme_less_file_full_path, $theme_new_css_full_path);
					}
				}
			}
			
			
			// recompile theme subtheme css
			if (file_exists(PATH_TO_THEMES.DS.$curr_top_level_dir.DS.'subthemes')) {
				$theme_sub_themes_path = PATH_TO_THEMES.DS.$curr_top_level_dir.DS.'subthemes';
				$curr_bottom_level_themes_dir = scandir($theme_sub_themes_path);
				foreach ($curr_bottom_level_themes_dir as $curr_bottom_level_theme) {
					if ($curr_bottom_level_theme == '.' || $curr_bottom_level_theme == '..') {
						continue;
					}
					
					$sub_theme_dir = $theme_sub_themes_path.DS.$curr_bottom_level_theme;
					
					$sub_theme_webroot_css_path = $sub_theme_dir.DS.'webroot'.DS.'css';
					$sub_theme_less_css_root_path = $sub_theme_dir.DS.'lesscss';
					if (is_dir($sub_theme_webroot_css_path) && is_dir($sub_theme_less_css_root_path)) {
						$dir = new DirectoryIterator($sub_theme_less_css_root_path);
						foreach ($dir as $fileinfo) {
							if ($fileinfo->getExtension() == 'less') {
								$sub_theme_less_file_full_path = $sub_theme_less_css_root_path.DS.$fileinfo->getFilename();
								$sub_theme_new_css_full_path = $sub_theme_webroot_css_path.DS.$fileinfo->getBasename('.less').'.css';

								$this->LessPhp->checkedCompile($sub_theme_less_file_full_path, $sub_theme_new_css_full_path);
							}
						}
					}
			
				}
			}
		}
	}
	
}