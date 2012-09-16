<?php
abstract class AbstractThemeLogoHelper extends AppHelper {

	abstract protected function _get_theme_name();
	abstract protected function _create_theme_base_logo($base_logo_file_path);
	

	public function get_base_logo_path() {
		// base logo file path
		$base_logo_file_path = $this->_get_logo_path();
		
		// check to see if the logo is already created
		if (file_exists($base_logo_file_path)) {
			return $base_logo_file_path;
		} else {
			if ($this->_create_theme_base_logo($base_logo_file_path)) {
				return $base_logo_file_path;
			} else {
				$this->MajorError = ClassRegistry::init('MajorError');
				$this->MajorError->major_error('Failed to create a theme base logo', compact('base_logo_file_path'));
				return false;
			}
		}
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
	
	
	public function delete_theme_base_logo() {
		$base_logo_file_path = $this->_get_logo_path();
		
		if (unlink($base_logo_file_path)) {
			// delete cache files associated with this logo
			$logo_cache_folder = SITE_LOGO_CACHES_PATH.DS.$this->_get_theme_name().DS;
			if (file_exists($logo_cache_folder)) {
				if (!unlink($logo_cache_folder)) {
					$this->MajorError = ClassRegistry::init('MajorError');
					$this->MajorError->major_error('Failed to delete theme cache files');
					return false;	
				}
			}
			
			return true;
		} else {
			$this->MajorError = ClassRegistry::init('MajorError');
			$this->MajorError->major_error('Failed to delete theme base logo');
			return false;
		}
		
		return true;
	}
	
	
	public function delete_all_theme_base_logos() {
		// DREW TODO - finish this function
	}

	
	protected function _get_logo_firstname() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		return $this->SiteSetting->getVal('firstname', 'John');
	}
	
	protected function _get_logo_lastname() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		return $this->SiteSetting->getVal('lastname', 'Doe');
	}
	
	protected function _get_logo_companyname() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		return $this->SiteSetting->getVal('company_name', 'Really Amazing Photography');
	}
	
	protected function _get_logo_path() {
		return SITE_LOGO_THEME_BASE_PATH.DS.$this->_get_theme_name().'.png';
	}
}