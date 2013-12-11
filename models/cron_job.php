<?php

class CronJob extends AppModel {
    public $name = 'CronJob';

	
	public function check_all_crons() {
		$all_crons = $this->find('all', array(
			'contain' => false,
		));
		
		foreach ($all_crons as $current_cron) {
			$next_run = strtotime('-5 minutes'); // cron will run by default
			if (!empty($current_cron['CronJob']['last_run'])) {
				// check if a cron should run
				$next_run = strtotime($current_cron['CronJob']['strtotime'], strtotime($current_cron['CronJob']['last_run']));
			}
			
			if ($next_run <= time()) {
				if ($this->run_cron($current_cron) !== true) {
					$this->major_error("failed to run cron {$current_cron['CronJob']['id']}", compact('current_cron'), 'high');
				}
				
				unset($current_cron['CronJob']['modified']);
				unset($current_cron['CronJob']['created']);
				$current_cron['CronJob']['last_run'] = date('Y-m-d H:i:s');
				$this->save($current_cron);
			}
		}
	}
	
	public function run_cron($cron_data) {
		$class_type = strtolower($cron_data['CronJob']['class_type']);
		$class_name = $cron_data['CronJob']['class_name'];
		$method_name = $cron_data['CronJob']['method_name'];
		
		switch($class_type) {
			case 'model':
				$this->$class_name = ClassRegistry::init($class_name);
				return $this->$class_name->$method_name();
				break;
		}

		return true;
	}
	
}