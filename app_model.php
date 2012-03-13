<?php
App::import('Lib', 'LazyModel.LazyModel');

/**
 * General app-wide Model Overrides
 *
 * @package Precious
 */
class AppModel extends LazyModel {
	var $actsAs = array(
		'Containable'
	);
	
	
	public function major_error($description, $extra_data = null) {
		$stackTrace = debug_backtrace(false);
		
		$majorError = ClassRegistry::init("MajorError");
		
		$location = '';
		if (isset($stackTrace[1]['class'])) {
			$location .= " --- Class: ".$stackTrace[1]['class']." --- ";
		}
		if (isset($stackTrace[1]['function'])) {
			$location .= " --- Function: ".$stackTrace[1]['function']." --- ";
		}
		$data['MajorError']['location'] = $location;
		$data['MajorError']['line_num'] = isset($stackTrace[1]['line']) ? $stackTrace[1]['line']: '';
		$data['MajorError']['description'] = $description;
		if ($extra_data != null) {
			$data['MajorError']['extra_data'] = print_r($extra_data, true);
		}
		
		@$majorError->save($data);
	}
	
	
	/*********************************************************
	 * HELPER FUNCTIONS
	 * 
	 */
	protected function random_num($n=5) {
		return rand(0, pow(10, $n));
	}
	
	protected function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	protected function endsWith($haystack, $needle) {
		$length = strlen($needle);
		$start  = $length * -1; //negative
		return (substr($haystack, $start) === $needle);
	}
	
	protected function number_pad($number,$n) {
		return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
	}
}