<?php
class ThemeLogoHelper extends AppHelper {

	/*************************************************************************************************
	 *  THEME BUILD BASE LOGO FUNCTIONS
	 *--------------------------------------------------------------------------------------------------------------------------------------*/
	protected function _create_theme_base_logo($base_logo_file_path, $theme_name) {
		$theme_method_name = "{$theme_name}_create_theme_base_logo";
		if (method_exists($this, $theme_method_name)) {
			return $this->{$theme_method_name}($base_logo_file_path);
		} else {
			return $this->default_create_theme_base_logo($base_logo_file_path);
		}
	}
	
	protected function default_create_theme_base_logo($base_logo_file_path) {
		$firstname = $this->_get_logo_firstname();
		$lastname = $this->_get_logo_lastname();
//		$company_name = $this->_get_logo_companyname();
		
		$font = 25;
		$padding = 20;
		$font_file = GLOBAL_TTF_FONT_PATH.DS."helvtc.ttf";
//		$longer_str = (strlen($lastname) > strlen($company_name)) ? $lastname : $company_name;
		$longer_str = $lastname;
		$string = $firstname.$longer_str;
		$string_pixel_width_arr = imagettfbbox($font, 0, $font_file, $string);
		$string_pixel_width = ($string_pixel_width_arr[2] - $string_pixel_width_arr[0]);
		$first_name_pixel_width_arr = imagettfbbox($font, 0, $font_file, $firstname);
		$first_name_pixel_width = $first_name_pixel_width_arr[2] - $first_name_pixel_width_arr[0];
//		$im = @imagecreatetruecolor($string_pixel_width + (2*$padding), $font*2 + (2*$padding)); // takes into account $company_name
		$im = @imagecreatetruecolor($string_pixel_width + (2*$padding), $font + (2*$padding));
		imagesavealpha($im, true);
		imagealphablending($im, false);
		$clear = imagecolorallocatealpha($im, 255, 255, 255, 127);
		$white = imagecolorallocatealpha($im, 255, 255, 255, 0);
		$red = imagecolorallocatealpha($im, 246, 63, 63, 0);
		imagefill($im, 0, 0, $clear);
		imagettftext($im, $font, 0, $padding, $padding+$font, $red, $font_file, $firstname);
		imagettftext($im, $font, 0, $first_name_pixel_width+$padding, $font+$padding, $white, $font_file, $lastname);
//		imagettftext($im, $font, 0, $first_name_pixel_width+$padding, ($font*2)+$padding, $white, $font_file, $company_name);
		//header("Content-type: image/png");
		$result = imagepng($im, $base_logo_file_path, 0);
		imagedestroy($im);
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function andrewmorrill_create_theme_base_logo($base_logo_file_path) {
		$firstname = $this->_get_logo_firstname();
		$lastname = $this->_get_logo_lastname();
//		$company_name = $this->_get_logo_companyname();
		
		$font = 25;
		$padding = 20;
		$font_file = GLOBAL_TTF_FONT_PATH.DS."helvtc.ttf";
//		$longer_str = (strlen($lastname) > strlen($company_name)) ? $lastname : $company_name;
		$longer_str = $lastname;
		$string = $firstname.$longer_str;
		$string_pixel_width_arr = imagettfbbox($font, 0, $font_file, $string);
		$string_pixel_width = ($string_pixel_width_arr[2] - $string_pixel_width_arr[0]);
		$first_name_pixel_width_arr = imagettfbbox($font, 0, $font_file, $firstname);
		$first_name_pixel_width = $first_name_pixel_width_arr[2] - $first_name_pixel_width_arr[0];
//		$im = @imagecreatetruecolor($string_pixel_width + (2*$padding), $font*2 + (2*$padding)); // takes into account $company_name
		$im = @imagecreatetruecolor($string_pixel_width + (2*$padding), $font + (2*$padding));
		imagesavealpha($im, true);
		imagealphablending($im, false);
		$clear = imagecolorallocatealpha($im, 255, 255, 255, 127);
		$white = imagecolorallocatealpha($im, 255, 255, 255, 0);
		$red = imagecolorallocatealpha($im, 246, 63, 63, 0);
		imagefill($im, 0, 0, $clear);
		imagettftext($im, $font, 0, $padding, $padding+$font, $red, $font_file, $firstname);
		imagettftext($im, $font, 0, $first_name_pixel_width+$padding, $font+$padding, $white, $font_file, $lastname);
//		imagettftext($im, $font, 0, $first_name_pixel_width+$padding, ($font*2)+$padding, $white, $font_file, $company_name);
		//header("Content-type: image/png");
		$result = imagepng($im, $base_logo_file_path, 0);
		imagedestroy($im);
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function simple_lightgrey_textured_create_theme_base_logo($base_logo_file_path) {
		$firstname = $this->_get_logo_firstname();
		$lastname = $this->_get_logo_lastname();
		$company_name = $this->_get_logo_companyname();
		
		// DREW TODO - further tweak this logo to make the first letters of each word a little bigger and a little lower
		$font_size = 30;
		$padding = 20;
		$font_file = GLOBAL_TTF_FONT_PATH.DS."MarcellusSC-Regular.ttf";
		$drop_shadow_offset = 3;
		
		$string = $firstname.' '.$lastname;
		$string_pixel_width_arr = imagettfbbox($font_size, 0, $font_file, $string);
		$string_pixel_width = ($string_pixel_width_arr[2] - $string_pixel_width_arr[0]);
		$im = @imagecreatetruecolor($string_pixel_width + (2*$padding), $font_size + (2*$padding));
		imagesavealpha($im, true);
		imagealphablending($im, false);
		$clear = imagecolorallocatealpha($im, 255, 255, 255, 127);
		$white = imagecolorallocatealpha($im, 255, 255, 255, 0);
		$black = imagecolorallocatealpha($im, 0, 0, 0, 0);
		$dark_grey = imagecolorallocatealpha($im, 50, 50, 50, 100);
		$red = imagecolorallocatealpha($im, 246, 63, 63, 0);
		imagefill($im, 0, 0, $clear);
		imagettftext($im, $font_size, 0, $padding+$drop_shadow_offset, $padding+$font_size+$drop_shadow_offset, $dark_grey, $font_file, $string);
		
//		$gauss_blur = array(
//			array(0, 0, 0, 5, 0, 0, 0), 
//			array(0, 5, 18, 32, 18, 5, 0), 
//			array(0, 18, 64, 100, 64, 18, 0), 
//			array(5, 32, 100, 100, 100, 32, 5), 
//			array(0, 18, 64, 100, 64, 18, 0), 
//			array(0, 5, 18, 32, 18, 5, 0), 
//			array(0, 0, 0, 5, 0, 0, 0) 
//		);
//	
//		imageconvolution($im, $gauss_blur, 1, 127);
		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		
		
		imagettftext($im, $font_size, 0, $padding, $padding+$font_size, $white, $font_file, $string);
		$result = imagepng($im, $base_logo_file_path, 0);
		imagedestroy($im);
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function white_angular_create_theme_base_logo($base_logo_file_path) {
		$firstname = strtoupper($this->_get_logo_firstname());
		$lastname = strtoupper($this->_get_logo_lastname());
//		$company_name = strtoupper($this->_get_logo_companyname());
		
		$font_size = 64;
		$padding = 20;
		$font_file = GLOBAL_TTF_FONT_PATH . DS . "signika.negative-bold.ttf";
		$drop_shadow_offset = 3;
		
		$string = $firstname.' '.$lastname;
		$string_pixel_width_arr = imagettfbbox($font_size, 0, $font_file, $string);
		$string_pixel_width = ($string_pixel_width_arr[2] - $string_pixel_width_arr[0]);
		$im = @imagecreatetruecolor($string_pixel_width + (2*$padding), $font_size + (2*$padding));
		imagesavealpha($im, true);
		imagealphablending($im, false);
		$clear = imagecolorallocatealpha($im, 255, 255, 255, 127);
		$white = imagecolorallocatealpha($im, 255, 255, 255, 0);
		$black = imagecolorallocatealpha($im, 0, 0, 0, 255);
		$dark_grey = imagecolorallocatealpha($im, 50, 50, 50, 100);
		imagefill($im, 0, 0, $clear);
//		imagettftext($im, $font_size, 0, $padding+$drop_shadow_offset, $padding+$font_size+$drop_shadow_offset, $dark_grey, $font_file, $string);
		
//		$gauss_blur = array(
//			array(0, 0, 0, 5, 0, 0, 0), 
//			array(0, 5, 18, 32, 18, 5, 0), 
//			array(0, 18, 64, 100, 64, 18, 0), 
//			array(5, 32, 100, 100, 100, 32, 5), 
//			array(0, 18, 64, 100, 64, 18, 0), 
//			array(0, 5, 18, 32, 18, 5, 0), 
//			array(0, 0, 0, 5, 0, 0, 0) 
//		);
//	
//		imageconvolution($im, $gauss_blur, 1, 127);
		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		
		
		imagettftext($im, $font_size, 0, $padding, $padding+$font_size, $black, $font_file, $string);
		$result = imagepng($im, $base_logo_file_path, 0);
		imagedestroy($im);
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function white_slider_create_theme_base_logo($base_logo_file_path) {
		$firstname = strtoupper($this->_get_logo_firstname());
		$lastname = strtoupper($this->_get_logo_lastname());
//		$company_name = strtoupper($this->_get_logo_companyname());
		
		$font_size = 48;
		$padding = 20;
		$font_file = GLOBAL_TTF_FONT_PATH . DS . "signika.negative-bold.ttf";
		$drop_shadow_offset = 3;
		
		$string = $firstname.' '.$lastname;
		$string_pixel_width_arr = imagettfbbox($font_size, 0, $font_file, $string);
		$string_pixel_width = ($string_pixel_width_arr[2] - $string_pixel_width_arr[0]);
		$im = @imagecreatetruecolor($string_pixel_width + (2*$padding), $font_size + (2*$padding));
		imagesavealpha($im, true);
		imagealphablending($im, false);
		$clear = imagecolorallocatealpha($im, 255, 255, 255, 127);
		$white = imagecolorallocatealpha($im, 255, 255, 255, 0);
		$black = imagecolorallocatealpha($im, 0, 0, 0, 255);
		$dark_grey = imagecolorallocatealpha($im, 50, 50, 50, 100);
		imagefill($im, 0, 0, $clear);
//		imagettftext($im, $font_size, 0, $padding+$drop_shadow_offset, $padding+$font_size+$drop_shadow_offset, $dark_grey, $font_file, $string);
		
//		$gauss_blur = array(
//			array(0, 0, 0, 5, 0, 0, 0), 
//			array(0, 5, 18, 32, 18, 5, 0), 
//			array(0, 18, 64, 100, 64, 18, 0), 
//			array(5, 32, 100, 100, 100, 32, 5), 
//			array(0, 18, 64, 100, 64, 18, 0), 
//			array(0, 5, 18, 32, 18, 5, 0), 
//			array(0, 0, 0, 5, 0, 0, 0) 
//		);
//	
//		imageconvolution($im, $gauss_blur, 1, 127);
		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		
		
		imagettftext($im, $font_size, 0, $padding, $padding+$font_size, $black, $font_file, $string);
		$result = imagepng($im, $base_logo_file_path, 0);
		imagedestroy($im);
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	
	protected function white_slider_subone_create_theme_base_logo($base_logo_file_path) {
		$firstname = $this->_get_logo_firstname();
		$lastname = $this->_get_logo_lastname();
		$company_name = $this->_get_logo_companyname();
		
		$line_one = strtoupper($firstname . ' ' . $lastname);
		$line_two = $company_name;
		$padding = 20;
		
		$name_font_size = 25;
		$name_font_file = GLOBAL_TTF_FONT_PATH.DS."nexa_bold/Nexa_Free_Bold-webfont.ttf";
		
		$company_font_size = 22;
		$company_font_file = GLOBAL_TTF_FONT_PATH.DS."museo_300.ttf";
		
		$line_width = 2;
		
		//$company_font_file 
		$name_bounding_box = imagettfbbox($name_font_size, 0, $name_font_file, $line_one);
		$name_height = $name_bounding_box[1] - $name_bounding_box[7];
		$company_bounding_box = imagettfbbox($company_font_size, 0, $company_font_file, $line_two);
		
		//determine width
		$logo_text_width = $name_bounding_box[2] > $company_bounding_box[2] ? $name_bounding_box[2] : $company_bounding_box[2];
		$total_logo_width = ($padding * 2) + $line_width + $logo_text_width;
		$im = @imagecreatetruecolor($total_logo_width, $name_font_size + $company_font_size + (3*$padding));
		imagesavealpha($im, true);
		imagealphablending($im, false);
		
		//write name
		$white = imagecolorallocatealpha($im, 255, 255, 255, 0);
		imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);
		imagettftext($im, $name_font_size, 0, $padding * 2, $padding + $name_height, $white, $name_font_file, $line_one);
		imagettftext($im, $company_font_size, 0, ($padding * 2), $name_font_size + $company_font_size + $padding * 2, $white, $company_font_file, $line_two);		
		imagesetthickness($im, $line_width);
		
		imageline($im, $padding, $padding + 3 , $padding, $padding * 2 + $name_font_size + $company_font_size , $white);
		
		$result = imagepng($im, $base_logo_file_path, 0);
		imagedestroy($im);
		
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	
	/*************************************************************************************************
	 *  END     -----      THEME BUILD BASE LOGO FUNCTIONS
	 *--------------------------------------------------------------------------------------------------------------------------------------*/
	
	
	
	
	
	
	
	
	
	
	public function get_base_logo_path($theme_name, $use_theme_logo = true) {
		// base logo file path
		if ($use_theme_logo) {
			$base_logo_file_path = $this->_get_logo_path($theme_name);
		} else {
			$base_logo_file_path = UPLOADED_LOGO_PATH;
		}
		
		// check to see if the logo is already created
		// DREW TODO - clean up the code below a bit
		if (file_exists($base_logo_file_path)) {
			return $base_logo_file_path;
		} else if ($use_theme_logo) {
			if ($this->_create_theme_base_logo($base_logo_file_path, $theme_name)) {
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
	
	public function get_logo_cache_size_path($height, $width, $theme_name, $abs_path = false, $use_theme_logo = true) {
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
		
		
		if (!$use_theme_logo) {
			$theme_name = 'uploaded';
		}
		$theme_logo_base_path = $this->get_base_logo_path($theme_name, $use_theme_logo);
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
		$theme_name = $theme_config['theme_name'];
		
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
		$start_logo_path = $this->get_logo_cache_size_path($use_logo_height, $use_logo_width, $theme_name, true, $use_theme_logo);
		$image_size = getimagesize($start_logo_path);
		$use_logo_width = $image_size[0];
		$use_logo_height = $image_size[1];
		$start_logo_web_path = $this->get_logo_cache_size_path($use_logo_height, $use_logo_width, $theme_name, false, $use_theme_logo);

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
	
	
//	public function get_base_logo_web_path() {
//		// base logo file path
//		$base_logo_file_path = $this->_get_logo_path();
//		
//		// check to see if the logo is already created
//		$web_path = '/base/'.$this->_get_theme_name().".png";
//		if (file_exists($base_logo_file_path)) {
//			return $web_path;
//		} else {
//			if ($this->_create_theme_base_logo($base_logo_file_path)) {
//				return $web_path;
//			} else {
//				$this->MajorError = ClassRegistry::init('MajorError');
//				$this->MajorError->major_error('Failed to create a theme base logo', compact('base_logo_file_path'));
//				return false;
//			}
//		}
//	}
	
	
	public function delete_theme_base_logo($theme_name) {
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
	
	protected function _get_logo_path($theme_name) {
		return SITE_LOGO_THEME_BASE_PATH.DS.$theme_name.'.png';
	}
}