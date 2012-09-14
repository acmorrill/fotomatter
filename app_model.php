<?php
App::import('Lib', 'LazyModel.LazyModel');

/**
 * General app-wide Model Overrides
 *
 * @package Precious
 */
class AppModel extends LazyModel {
	public $actsAs = array(
		'Containable'
	);
	
	
	public function get_insult() {
		$insults = array();
		
		$insults[] = 'You really suck!';
		$insults[] = 'Maybe you should just kill yourself';
		$insults[] = 'A day late and a dollar short';
		$insults[] = 'A donut short of being a cop';
		$insults[] = 'Made a career out of a midlife crisis';
		
		return $insults[rand(0, count($insults)-1)];
	}
	
	/*public function beforeFind($conditions) {
		if ( !isset($conditions['contain']) ) {
			$conditions['contain'] = false;
			$conditions['recursive'] = -1;
		}
		
		return $conditions;
	}*/
	
	
	/**
	 *
	 * @param type $description
	 * @param type $extra_data
	 * @param type $severity 
	 */
	public function major_error($description, $extra_data = null, $severity = 'normal') {
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
		$data['MajorError']['line_num'] = isset($stackTrace[1]['line']) ? $stackTrace[1]['line']: 1;
		$data['MajorError']['description'] = $description;
		if ($extra_data != null) {
			$data['MajorError']['extra_data'] = print_r($extra_data, true);
		}
		$data['MajorError']['severity'] = $severity;
		$majorError->create();
		$majorError->save($data);
	}
	
	
	/*********************************************************
	 * HELPER FUNCTIONS
	 * 
	 */
	protected function random_num($n=5) {
		return rand(0, pow(10, $n));
	}
	
	public function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	public function endsWith($haystack, $needle) {
		$length = strlen($needle);
		$start  = $length * -1; //negative
		return (substr($haystack, $start) === $needle);
	}
	
	protected function number_pad($number,$n) {
		return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
	}
}