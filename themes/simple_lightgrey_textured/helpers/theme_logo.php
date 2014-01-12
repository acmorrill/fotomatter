<?php
require_once(ROOT.DS.'app'.DS.'views'.DS.'helpers'.DS.'abstract_theme_logo.php');

class ThemeLogoHelper extends AbstractThemeLogoHelper {
	
	protected function _create_theme_base_logo($base_logo_file_path) {
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
	
	protected function _get_theme_name() {
		return "simple_lightgrey_textured";
	}
	
	
	
}