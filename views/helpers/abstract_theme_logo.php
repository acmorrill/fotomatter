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
				if (mkdir($theme_logo_folder_cache_path, 0775, true) === false) {
					$this->PhotoCache->major_error('failed to create logo cache folder for theme', compact('theme_name', 'theme_logo_folder_cache_path'));
				}
			}
			//chmod($theme_logo_folder_cache_path, 0775); // DREW TODO - this lines seems to be unneede and maybe causes bugs - maybe delete it
			
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
	
	
	public function get_display_logo_data($theme_config) {
		$this->theme_config = $theme_config;
		$this->theme_settings = $theme_config['admin_config']['theme_avail_custom_settings']['settings'];
		$this->Theme = ClassRegistry::init('Theme');
		
		$logo_max_width = $logo_context_width = isset($theme_config['admin_config']['logo_config']['available_space']['width']) ? $theme_config['admin_config']['logo_config']['available_space']['width'] : 400;
		$logo_max_height = $logo_context_height = isset($theme_config['admin_config']['logo_config']['available_space']['height']) ? $theme_config['admin_config']['logo_config']['available_space']['height'] : 200;

		$avail_space_screenshot_web_path = '';

		$padding = isset($theme_config['admin_config']['logo_config']['available_space_screenshot']['padding']) ? $theme_config['admin_config']['logo_config']['available_space_screenshot']['padding'] : '0px';
		if (!empty($theme_config['admin_config']['logo_config']['available_space_screenshot']['absolute_path'])) {
			$avail_space_screenshot_web_path = $theme_config['admin_config']['logo_config']['available_space_screenshot']['web_path'];
			$avail_space_screenshot_path = $theme_config['admin_config']['logo_config']['available_space_screenshot']['absolute_path'];
			$avail_space_screenshot_size = getimagesize($avail_space_screenshot_path);
			$logo_context_width = $avail_space_screenshot_size[0];
			$logo_context_height = $avail_space_screenshot_size[1];
			$logo_max_width = $avail_space_screenshot_size[0] - $padding['left'] - $padding['right'];
			$logo_max_height = $avail_space_screenshot_size[1] - $padding['top'] - $padding['bottom'];
		}


		$logo_default_width = isset($theme_config['admin_config']['logo_config']['default_space']['width']) ? $theme_config['admin_config']['logo_config']['default_space']['width'] : 300;
		$logo_default_height = isset($theme_config['admin_config']['logo_config']['default_space']['height']) ? $theme_config['admin_config']['logo_config']['default_space']['height'] : 150;
		$logo_current_width = $this->Theme->get_theme_setting('logo_current_width', $logo_default_width);
		$logo_current_height = $this->Theme->get_theme_setting('logo_current_height', $logo_default_height);

		$use_logo_width = min($logo_current_width, $logo_max_width);
		$use_logo_height = min($logo_current_height, $logo_max_height);

		$use_theme_logo = $this->Theme->get_theme_setting('use_theme_logo', true);
		$start_logo_path = $this->get_logo_cache_size_path($use_logo_height, $use_logo_width, true, $use_theme_logo);
		$image_size = getimagesize($start_logo_path);
		$use_logo_width = $image_size[0];
		$use_logo_height = $image_size[1];
		$start_logo_web_path = $this->get_logo_cache_size_path($use_logo_height, $use_logo_width, false, $use_theme_logo);

		$logo_current_top = $this->Theme->get_theme_setting('logo_current_top', 0);
		$logo_current_left = $this->Theme->get_theme_setting('logo_current_left', 0);

		// check to see that the logo is still in the specified spot
		if (($logo_current_left + $use_logo_width) > $logo_max_width) {
			$logo_current_left = $logo_max_width - $use_logo_width;
		}
		if (($logo_current_top + $use_logo_height) > $logo_max_height) {
			$logo_current_top = $logo_max_height - $use_logo_height;
		}
		
		return compact('logo_max_width', 'logo_max_height', 'logo_current_top', 'logo_current_left', 'start_logo_web_path');
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
		
		$this->ThemeLogo = ClassRegistry::init('ThemeLogo');
		$this->ThemeLogo->delete_theme_base_logo($theme_name);
	}
	
	
	
	protected function _get_logo_firstname() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$first_name = $this->SiteSetting->getVal('first_name', 'John');
		
		return $first_name;
	}
	
	protected function _get_logo_lastname() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$last_name = $this->SiteSetting->getVal('last_name', 'Doe');
		
		return $last_name;
	}
	
	protected function _get_logo_companyname() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$company_or_tagline = $this->SiteSetting->getVal('company_name', 'Photography');
		
		return $company_or_tagline;
	}
	
	protected function _get_logo_path($theme_name = null) {
		if (!isset($theme_name)) {
			$theme_name = $this->_get_theme_name();
		}
		
		return SITE_LOGO_THEME_BASE_PATH.DS.$theme_name.'.png';
	}
}