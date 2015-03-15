<?php
require_once(ROOT.DS.'app'.DS.'views'.DS.'helpers'.DS.'abstract_theme_logo.php');

class ThemeLogoHelper extends AbstractThemeLogoHelper {
	
	function _create_theme_base_logo($base_logo_file_path) {
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
	
	function _get_theme_name() {
		return "white_angular";
	}
	

	
}