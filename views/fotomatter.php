<?php
class FotomatterView extends View {
	
	public function element($name, $params = array(), $loadHelpers = false) {
		$element_result = parent::element($name, $params, $loadHelpers);
		
		$debug_data = '';
		if (Configure::read('debug') > 1) {
			$uuid = substr(base64_encode(String::uuid()), 0, 20);
			$debug_data .= "\n<script type='text/javascript'>\n";
				$debug_data .= "jQuery(document).ready(function() {";
					$debug_data .= "var curr_element = jQuery('#".$uuid."');\n";
					$debug_data .= "var next_element = jQuery('#".$uuid."').next();\n";
					$debug_data .= "console.log(next_element);\n";
					
					$debug_data .= "next_element.mousemove(function() {\n";
						$debug_data .= "jQuery('.debug_element_path').hide();\n";
						$debug_data .= "curr_element.show();\n";
						$debug_data .= "setTimeout(function() {\n";
							$debug_data .= "curr_element.hide()\n";
						$debug_data .= "}, 5000);\n";
					$debug_data .= "});\n";
					
				$debug_data .= "});";
			$debug_data .= "</script>\n";
			$debug_data .= '<div id="'.$uuid.'" class="debug_element_path" style="display:none; position: fixed; padding: 10px; background: black; color: white; bottom: 0px; right: 0px; border: 3px solid white; font-size: 16px; font-weight: bold;">';
				$debug_data .= $name;
			$debug_data .= '</div>';
		}
		
		return $debug_data.$element_result;
	}
	
}