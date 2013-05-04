<?php

class DrewShell extends Shell {
	public $uses = array('SiteSetting', 'Photo');
	
	function main() {
		$this->SiteSetting->setVal('image-container-url', 'http://c9134086.r86.cf2.rackcdn.com/');
		$this->SiteSetting->setVal('image-container-secure_url', 'https://c9134086.ssl.cf2.rackcdn.com/');
		$this->SiteSetting->setVal('image-container-name', 'andrew-dev-container');
	} 
	
	function start_over()   {
		$this->delete_photo();
		$this->remove_all_objects();
	}
	
	
	public function list_cloudfiles() {
		App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
		
		debug($this->files->list_objects());
	}
	
	function delete_photo() {
		$this->Photo->deleteAll(array("1=1"), true, true);
	}
	
	function remove_all_objects() {
		App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
		
		$all_objects = $this->files->list_objects();
		
		foreach ($all_objects as $all_object) {
			//$this->files->delete_object($all_object['name']);
			//print_r($all_object);
		}
	}
	
	public function calc_phase_equity() {
		$phase_1 = array(
			'a' => array(
				'start_equity' => 37.5,
//				'hours' => 18.08,
				'hours' => 15,
			),
			'b' => array(
				'start_equity' => 37.5,
				'hours' => 10,
			),
			'c' => array(
				'start_equity' => 25,
				'hours' => 11.91,
			),
		);
		$this->calc_equity($phase_1, 1);
		debug($phase_1);
		
		
		$phase_2 = array(
			'a' => array(
				'start_equity' => 37.5,
//				'hours' => 18.08,
				'hours' => 15,
			),
			'b' => array(
				'start_equity' => 37.5,
				'hours' => 1,
			),
			'c' => array(
				'start_equity' => 25,
				'hours' => 11.91,
			),
		);
		$this->calc_equity($phase_2, .15);
		debug($phase_2);
		
		
		$phase_3 = array(
			'a' => array(
				'start_equity' => 37.5,
//				'hours' => 18.08,
				'hours' => 15,
			),
			'b' => array(
				'start_equity' => 37.5,
				'hours' => 10.83,
			),
			'c' => array(
				'start_equity' => 25,
				'hours' => 11.91,
			),
		);
		$this->calc_equity($phase_3, .15);
		debug($phase_3);
	}
	
	
	public function calc_equity(&$people, $phase_percent) {
		// get lost equity
		foreach ($people as $name => &$person) {
			$person['lost_equity'] = $this->equity_lost($person['hours'], $person['start_equity']);
			$person['equity_kept'] = $this->equity_kept($person['hours'], $person['start_equity']);
		}
		
	
		
		$people['a']['gained'] = 0;
		$people['a']['gained'] += $this->calc_gained_equity($people['a']['hours'], $people['c']['hours'], $people['b']['lost_equity']);
		$people['a']['gained'] += $this->calc_gained_equity($people['a']['hours'], $people['b']['hours'], $people['c']['lost_equity']);
		$people['a']['total_equity'] = $people['a']['equity_kept'] + $people['a']['gained'];
		
		$people['b']['gained'] = 0;
		$people['b']['gained'] += $this->calc_gained_equity($people['b']['hours'], $people['c']['hours'], $people['a']['lost_equity']);
		$people['b']['gained'] += $this->calc_gained_equity($people['b']['hours'], $people['a']['hours'], $people['c']['lost_equity']);
		$people['b']['total_equity'] = $people['b']['equity_kept'] + $people['b']['gained'];
		
		$people['c']['gained'] = 0;
		$people['c']['gained'] += $this->calc_gained_equity($people['c']['hours'], $people['b']['hours'], $people['a']['lost_equity']);
		$people['c']['gained'] += $this->calc_gained_equity($people['c']['hours'], $people['a']['hours'], $people['b']['lost_equity']);
		$people['c']['total_equity'] = $people['c']['equity_kept'] + $people['c']['gained'];
		
		foreach ($people as &$person) {
			$person['phase_equity'] = $person['total_equity'] * $phase_percent;
		}
		
		
//		$all_total = $people['a']['total_equity'] + $people['b']['total_equity'] + $people['c']['total_equity'];
		
		
		// divy up lost equity
//		foreach ($people as $name => $person) {
//			$other_keys = $this->get_other_two_people_keys($name, $people);
//			
//			$gained = 0;
//			foreach($other_keys as $curr_key) {
//				$gained += $this->calc_gained_equity($one_hours, $two_hours, $equity_up_for_grabs);
//			}
//		}
		
		
		
		
	}
	
	public function calc_gained_equity($one_hours, $two_hours, $equity_up_for_grabs) {
		if ($equity_up_for_grabs <= 0) {
			return 0;
		}
		
		$normalize_val = 5;
		
		return ( ( ($one_hours - $normalize_val) / ( ($one_hours - $normalize_val) + ($two_hours - $normalize_val) ) ) ) * $equity_up_for_grabs;
	}
	
	public function get_other_two_people_keys($me, $array) {
		$other_keys = array();
		foreach ($array as $key => $current) {
			if ($key != $me) {
				$other_keys[] = $key;
			}
		}
		
		return $other_keys;
	}
		
	
	
	public function equity_kept($hours, $equity) {
		if ($hours > 15) {
			return $equity;
		}
		
		return (((7 * $hours) - 5) / 100) * $equity;
	}
	
	public function equity_lost($hours, $equity) {
		return $equity - $this->equity_kept($hours, $equity);
	}
	
	
}