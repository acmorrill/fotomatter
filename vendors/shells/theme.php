<?php

class ThemeShell extends Shell {
	public $uses = array('Theme');
	
	
	public function rebuild_theme_table() {
		$this->Theme->query('TRUNCATE table themes');
		
		$top_level_themes_dir = scandir(PATH_TO_THEMES);
		foreach ($top_level_themes_dir as $curr_top_level_dir) {
			if ($curr_top_level_dir == '.' || $curr_top_level_dir == '..' || $curr_top_level_dir == 'default') {
				continue;
			}
			
			
			$new_theme_id = $this->Theme->add_theme($curr_top_level_dir);
			if ($new_theme_id === false) {
				$this->hr();
				$this->out("Failed to add theme $curr_top_level_dir");
				$this->hr();
				exit(1);
			}
			
			
			
			if (file_exists(PATH_TO_THEMES.DS.$curr_top_level_dir.DS.'subthemes')) {
				$curr_bottom_level_themes_dir = scandir(PATH_TO_THEMES.DS.$curr_top_level_dir.DS.'subthemes');
				foreach ($curr_bottom_level_themes_dir as $curr_bottom_level_theme) {
					if ($curr_bottom_level_theme == '.' || $curr_bottom_level_theme == '..') {
						continue;
					}
			
					$new_sub_theme_id = $this->Theme->add_theme($curr_bottom_level_theme, $curr_top_level_dir);
					if ($new_sub_theme_id === false) {
						$this->hr();
						$this->out("Failed to add theme $curr_top_level_dir");
						$this->hr();
						exit(1);
					}
				}
			}
		}
	}
	
	public function add_theme() {
		if (!isset($this->args[0])) {
			$this->hr();
			$this->out('You must pass a name for the new theme followed by the theme display name and then followed optionally by the parent theme ref_name');
			$this->hr();
			$all_themes = $this->Theme->find('all', array(
				'contain' => false
			));
			foreach ($all_themes as $curr_theme) {
				$this->out($curr_theme['Theme']['id'].' --- '.$curr_theme['Theme']['ref_name']);
			}
			exit(1);
		}
		
		if (!isset($this->args[1])) {
			$this->hr();
			$this->out('You must pass a name for the new theme followed by the theme display name and then followed optionally by the parent theme ref_name');
			$this->hr();
			$all_themes = $this->Theme->find('all', array(
				'contain' => false
			));
			foreach ($all_themes as $curr_theme) {
				$this->out($curr_theme['Theme']['id'].' --- '.$curr_theme['Theme']['display_name']);
			}
			exit(1);
		}

		$new_theme_name = $this->args[0];
		$new_theme_display_name = $this->args[1];
		$parent_theme_name = isset($this->args[2]) ? $this->args[2] : null ;
		
		if ($this->Theme->theme_exists($new_theme_name)) {
			$this->hr();
			$this->out("The theme $new_theme_name already exists");
			$this->hr();
			exit(1);
		}
		
		if($this->Theme->display_name_exists($new_theme_display_name)) {
			$this->hr();
			$this->out("The display name $new_theme_display_name already exists");
			$this->hr();
			exit(1);
		}
		
		// add theme that has parent
		if (!empty($parent_theme_name) && $this->Theme->theme_exists($parent_theme_name) && $this->Theme->theme_is_parent($parent_theme_name)) { // adding theme as subtheme of parent theme
			$path_to_parent_theme = $this->Theme->get_path_to_theme($parent_theme_name);
			
			if (!file_exists($path_to_parent_theme.DS.'subthemes')) {
				mkdir($path_to_parent_theme.DS.'subthemes', 0775);
			}
			
			$helpers_path = $path_to_parent_theme.DS.'subthemes'.DS.$new_theme_name.DS.'helpers';
			mkdir($helpers_path, 0775, true);
			$this->add_empty_file_to_folder($helpers_path);
			
			$elements_path = $path_to_parent_theme.DS.'subthemes'.DS.$new_theme_name.DS.'views'.DS.'elements';
			mkdir($elements_path, 0775, true);
			$this->add_empty_file_to_folder($elements_path);
			
			$layouts_path = $path_to_parent_theme.DS.'subthemes'.DS.$new_theme_name.DS.'views'.DS.'layouts';
			mkdir($layouts_path, 0775, true);
			$this->add_empty_file_to_folder($layouts_path);
			
			$lesscss_path = $path_to_parent_theme.DS.'subthemes'.DS.$new_theme_name.DS.'lesscss';
			mkdir($lesscss_path, 0775, true);
			$this->add_empty_file_to_folder($lesscss_path);
			
			$webroot_css_path = $path_to_parent_theme.DS.'subthemes'.DS.$new_theme_name.DS.'webroot'.DS.'css';
			mkdir($webroot_css_path, 0775, true);
			$this->add_empty_file_to_folder($webroot_css_path);
			
			$handle = fopen($path_to_parent_theme.DS.'subthemes'.DS.$new_theme_name.DS.'theme_config.php', 'x+');
			fwrite($handle, "<?php\n\n\$theme_config = array();\n");
			fclose($handle);
			
		// add top level theme
		} else {
			$helper_path = PATH_TO_THEMES.DS.$new_theme_name.DS.'helpers';
			mkdir($helper_path, 0775, true);
			$this->add_empty_file_to_folder($helper_path);
			
			$elements_path = PATH_TO_THEMES.DS.$new_theme_name.DS.'views'.DS.'elements';
			mkdir($elements_path, 0775, true);
			$this->add_empty_file_to_folder($elements_path);
			
			$layouts_path = PATH_TO_THEMES.DS.$new_theme_name.DS.'views'.DS.'layouts';
			mkdir($layouts_path, 0775, true);
			$this->add_empty_file_to_folder($layouts_path);
			
			$lesscss_path = PATH_TO_THEMES.DS.$new_theme_name.DS.'lesscss';
			mkdir($lesscss_path, 0775, true);
			$this->add_empty_file_to_folder($lesscss_path);
			
			$webroot_css_path = PATH_TO_THEMES.DS.$new_theme_name.DS.'webroot'.DS.'css';
			mkdir($webroot_css_path, 0775, true);
			$this->add_empty_file_to_folder($webroot_css_path);
			
			$handle = fopen(PATH_TO_THEMES.DS.$new_theme_name.DS.'theme_config.php', 'x+');
			fwrite($handle, "<?php\n\n\$theme_config = array();\n");
			fclose($handle);
		}
		
		if (isset($parent_theme_name)) {
			$update_function_str = "\$functions[] = function() {\n\$theme = ClassRegistry::init('Theme');\n\$theme->add_theme_display_name('$new_theme_name', '$new_theme_display_name', '$parent_theme_name');\nreturn true;\n};";
		} else {
			$update_function_str = "\$functions[] = function() {\n\$theme = ClassRegistry::init('Theme');\n\$theme->add_theme_display_name('$new_theme_name', '$new_theme_display_name');\nreturn true;\n};";
		}
		$this->out($update_function_str);
		
		/*$new_theme_id = $this->Theme->add_theme($new_theme_name, $parent_theme_name);
		if ($new_theme_id === false) {
			$this->hr();
			$this->out("Failed to add theme $curr_top_level_dir");
			$this->hr();
			exit(1);
		} */
	}
	
	private function add_empty_file_to_folder($folder_path) {
		touch($folder_path.DS.'empty');
	}
	
	public function change_theme() {
		if (!isset($this->args[0])) {
			$this->hr();
			$this->out('You have to pass a theme to change to (either the id or the name)');
			$this->hr();
			$all_themes = $this->Theme->find('all', array(
				'contain' => false
			));
			foreach ($all_themes as $curr_theme) {
				$this->out($curr_theme['Theme']['id'].' --- '.$curr_theme['Theme']['ref_name']);
			}
			exit(1);
		} else {
			$theme_to_change_to = $this->args[0];
			
			if (is_numeric($theme_to_change_to)) {
				$this->Theme->change_to_theme_by_id($theme_to_change_to);
			} else {
				$this->Theme->change_to_theme($theme_to_change_to);
			}
		}
		
	}
	
}