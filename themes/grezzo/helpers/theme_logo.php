<?php
require_once(ROOT.DS.'app'.DS.'views'.DS.'helpers'.DS.'abstract_theme_logo.php');

class ThemeLogoHelper extends AbstractThemeLogoHelper {
	
	protected function _create_theme_base_logo($base_logo_file_path) {
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
		
	protected function _get_theme_name() {
		return "grezzo";
	}
}