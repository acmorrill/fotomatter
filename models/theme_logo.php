<?php
class ThemeLogo extends AppModel {
	public $name = 'ThemeLogo';
	public $useTable = false;
	
	
	public function delete_theme_base_logo($theme_name) {
		$base_logo_file_path = $this->_get_logo_path($theme_name);
		
		if (file_exists($base_logo_file_path)) {
			if (unlink($base_logo_file_path)) {
				// delete cache files associated with this logo
				$logo_cache_folder = SITE_LOGO_CACHES_PATH.DS.$theme_name.DS;
				if (file_exists($logo_cache_folder)) {
					$this->MajorError = ClassRegistry::init('MajorError');
					if (!$this->MajorError->recursive_remove_directory($logo_cache_folder)) {
						$this->MajorError->major_error('Failed to delete theme cache files from theme_logo model', compact('theme_name'));
						return false;	
					}
				}

				return true;
			} else {
				$this->MajorError = ClassRegistry::init('MajorError');
				$this->MajorError->major_error('Failed to delete theme base logo from theme_logo model', compact('theme_name'));
				return false;
			}
		}
		
		return true;
	}
	
	function _get_logo_path($theme_name) {
		return SITE_LOGO_THEME_BASE_PATH.DS.$theme_name.'.png';
	}
	
	
	// DREW TODO - need to get the below code working on a cron
//	public function delete_all_theme_base_logos() {
//		$this->Theme = ClassRegistry::init('Theme');
//		
//		$all_themes = $this->Theme->find('all', array(
//			'contain' => false
//		));
//		
//		// add in the default theme if its not there
//		$all_themes[] = array(
//			'Theme' => array(
//				'ref_name' => 'default'
//			)
//		);
//		
//		foreach ($all_themes as $theme) {
//			if (!$this->delete_theme_base_logo($theme['Theme']['ref_name'])) {
//				return false;
//			}
//		}
//		
//		return true;
//	}
//	
//	public function clear_expired_logo_files() {
//		$logo_caches_dir = SITE_LOGO_CACHES_PATH;
//		exec("find $logo_caches_dir -name '*.png' -depth -type f -atime +14 -delete", $output, $return_var);
//		
//		if ($return_var != 0) {
//			$this->MajorError = ClassRegistry::init('MajorError');
//			$this->MajorError->major_error('Failed to expire logo cache files', compact('logo_caches_dir'));
//		}
//		
//		
//		$logo_base_dir = SITE_LOGO_THEME_BASE_PATH;
//		exec("find $logo_base_dir -name '*.png' -depth -type f -atime +14 -delete", $output, $return_var);
//		if ($return_var != 0) {
//			$this->MajorError = ClassRegistry::init('MajorError');
//			$this->MajorError->major_error('Failed to expire logo base files', compact('logo_base_dir'));
//		}
//		
//		return true;
//	}
	
	
}