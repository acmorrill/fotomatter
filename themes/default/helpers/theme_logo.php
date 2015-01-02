<?php
require_once(ROOT.DS.'app'.DS.'views'.DS.'helpers'.DS.'abstract_theme_logo.php');

class ThemeLogoHelper extends AbstractThemeLogoHelper {
	
	function _create_theme_base_logo($base_logo_file_path) {
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
	
	function _get_theme_name() {
		return "default";
	}
	
	
	
}