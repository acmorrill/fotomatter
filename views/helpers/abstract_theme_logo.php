<?php
abstract class AbstractThemeLogoHelper extends AppHelper {

	abstract protected function _get_theme_name();
	abstract protected function _create_theme_base_logo($base_logo_file_path);
	

	public function get_base_logo_path($use_theme_logo = true) {
		// base logo file path
		if ($use_theme_logo) {
			$base_logo_file_path = $this->_get_logo_path();
		} else {
			$base_logo_file_path = UPLOADED_LOGO_PATH;
		}
		
		// check to see if the logo is already created
		// DREW TODO - clean up the code below a bit
		if (file_exists($base_logo_file_path)) {
			return $base_logo_file_path;
		} else if ($use_theme_logo) {
			if ($this->_create_theme_base_logo($base_logo_file_path)) {
				return $base_logo_file_path;
			} else {
				$this->MajorError = ClassRegistry::init('MajorError');
				$this->MajorError->major_error('Failed to create a theme base logo', compact('base_logo_file_path'));
				return false;
			}
		} else {
			return $base_logo_file_path;
		}
	}
	
	public function get_logo_cache_size_path($height, $width, $abs_path = false, $use_theme_logo = true) {
		$bothEmpty = empty($height) && empty($width);
		$onlyWidth = !empty($width) && empty($height);
		$onlyHeight = empty($width) && !empty($height);
		$bothSet = !empty($width) && !empty($height);
		
		// return the full photo path
		if ($bothEmpty) {
			return $dummy_image_url_path;
		} 
		// get a cache smaller than width
		else if ($onlyWidth) {
			$folder = $width;
			$height = '';
		} 
		// get a cache smaller than height
		else if ($onlyHeight) {
			$folder = 'x'.$height;
			$width = '';
		} 
		// get a cache smaller than width and height
		else if ($bothSet) {
			$folder = $width.'x'.$height;
		}
		
		
		if ($use_theme_logo) {
			$theme_name = $this->_get_theme_name();
		} else {
			$theme_name = 'uploaded';
		}
		$theme_logo_base_path = $this->get_base_logo_path($use_theme_logo);
		$theme_logo_folder_cache_path = SITE_LOGO_CACHES_PATH.DS.$theme_name;
		$image_path = $theme_logo_folder_cache_path.DS.$folder.'_'.$theme_name.'.png';
		$url_image_path = SITE_LOGO_CACHES_WEB_PATH.DS.$theme_name.DS.$folder.'_'.$theme_name.'.png';
		
		
		if (!file_exists($image_path)) {
			if (!is_dir($theme_logo_folder_cache_path)) {
				mkdir($theme_logo_folder_cache_path, 0775, true);
			}
			chmod($theme_logo_folder_cache_path, 0775);
			
			$this->PhotoCache = ClassRegistry::init('PhotoCache');
			// DREW TODO - maybe make sure that this convert uses high quality and has smoothing/sharpening
			if ($this->PhotoCache->convert($theme_logo_base_path, $image_path, $width, $height) == false) {
				$this->PhotoCache->major_error('failed to create logo cache file for theme logo', compact('theme_name', 'theme_logo_base_path', 'image_path', 'url_image_path'));
			}
			chmod($image_path, 0776);
		}
		
		if ($abs_path) {
			return $image_path;
		} else {
			return $url_image_path;
		}
	}
	
	public function has_uploaded_custom_logo() {
		return file_exists(UPLOADED_LOGO_PATH);
	}
	
	
	public function get_base_logo_web_path() {
		// base logo file path
		$base_logo_file_path = $this->_get_logo_path();
		
		// check to see if the logo is already created
		$web_path = '/base/'.$this->_get_theme_name().".png";
		if (file_exists($base_logo_file_path)) {
			return $web_path;
		} else {
			if ($this->_create_theme_base_logo($base_logo_file_path)) {
				return $web_path;
			} else {
				$this->MajorError = ClassRegistry::init('MajorError');
				$this->MajorError->major_error('Failed to create a theme base logo', compact('base_logo_file_path'));
				return false;
			}
		}
	}
	
	
	public function delete_theme_base_logo($theme_name = null) {
		if (!isset($theme_name)) {
			$theme_name = $this->_get_theme_name();
		}
		
		$base_logo_file_path = $this->_get_logo_path($theme_name);
		
		if (file_exists($base_logo_file_path)) {
			if (unlink($base_logo_file_path)) {
				// delete cache files associated with this logo
				$logo_cache_folder = SITE_LOGO_CACHES_PATH.DS.$theme_name.DS;
				$this->MajorError = ClassRegistry::init('MajorError');
				if (file_exists($logo_cache_folder)) {
					if (!$this->MajorError->recursive_remove_directory($logo_cache_folder)) {
						$this->MajorError = ClassRegistry::init('MajorError');
						$this->MajorError->major_error('Failed to delete theme cache files', compact('theme_name'));
						return false;	
					}
				}

				return true;
			} else {
				$this->MajorError = ClassRegistry::init('MajorError');
				$this->MajorError->major_error('Failed to delete theme base logo', compact('theme_name'));
				return false;
			}
		}
		
		return true;
	}
	
	
	public function delete_all_theme_base_logos() {
		$this->Theme = ClassRegistry::init('Theme');
		
		$all_themes = $this->Theme->find('all', array(
			'contain' => false
		));
		
		// add in the default theme if its not there
		$all_themes[] = array(
			'Theme' => array(
				'ref_name' => 'default'
			)
		);
		
		foreach ($all_themes as $theme) {
			if (!$this->delete_theme_base_logo($theme['Theme']['ref_name'])) {
				return false;
			}
		}
		
		return true;
	}
	
	public function clear_expired_logo_files() {
		$logo_caches_dir = SITE_LOGO_CACHES_PATH;
		exec("find $logo_caches_dir -name '*.png' -depth -type f -atime +14 -delete", $output, $return_var);
		
		if ($return_var != 0) {
			$this->MajorError = ClassRegistry::init('MajorError');
			$this->MajorError->major_error('Failed to expire logo cache files', compact('logo_caches_dir'));
		}
		
		
		$logo_base_dir = SITE_LOGO_THEME_BASE_PATH;
		exec("find $logo_base_dir -name '*.png' -depth -type f -atime +14 -delete", $output, $return_var);
		if ($return_var != 0) {
			$this->MajorError = ClassRegistry::init('MajorError');
			$this->MajorError->major_error('Failed to expire logo base files', compact('logo_base_dir'));
		}
		
		return true;
	}

	
	protected function _get_logo_firstname() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		return $this->SiteSetting->getVal('firstname', 'Andrew');
	}
	
	protected function _get_logo_lastname() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		return $this->SiteSetting->getVal('lastname', 'Morrill');
	}
	
	protected function _get_logo_companyname() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		return $this->SiteSetting->getVal('company_name', 'Celestial Light Photography');
	}
	
	protected function _get_logo_path($theme_name = null) {
		if (!isset($theme_name)) {
			$theme_name = $this->_get_theme_name();
		}
		
		return SITE_LOGO_THEME_BASE_PATH.DS.$theme_name.'.png';
	}
}