<?php

class CronJob extends AppModel {

	public $name = 'CronJob';

	public function send_cron_working_email() {
		if (Configure::read('debug') > 0) {
			$this->send_fotomatter_email('send_cron_working_email');
		}
		
		return true;
	}
	
	public function check_all_crons($force_run_now = false) {
		$all_crons = $this->find('all', array(
			'contain' => false,
		));

		foreach ($all_crons as $current_cron) {
			$next_run = strtotime('-5 minutes'); // cron will run by default
			if (!empty($current_cron['CronJob']['last_run'])) {
				// check if a cron should run
				$next_run = strtotime($current_cron['CronJob']['strtotime'], strtotime($current_cron['CronJob']['last_run']));
			}

			if ($force_run_now || $next_run <= time()) {
				$current_cron['CronJob']['run_count'] += 1;
				$curr_start_time = microtime(true);
				if ($this->run_cron($current_cron) !== true) {
					$this->major_error("failed to run cron {$current_cron['CronJob']['method_name']}", compact('current_cron'), 'high');
					$current_cron['CronJob']['failure_count'] += 1;
				} else {
					$current_cron['CronJob']['success_count'] += 1;
				}
				$curr_total_time = microtime(true) - $curr_start_time;
				$current_cron['CronJob']['last_runtime'] = $curr_total_time;
				$current_cron['CronJob']['average_runtime'] = $current_cron['CronJob']['average_runtime'] + ($curr_total_time - $current_cron['CronJob']['average_runtime']) / $current_cron['CronJob']['run_count'];
				

				unset($current_cron['CronJob']['modified']);
				unset($current_cron['CronJob']['created']);
				$current_cron['CronJob']['last_run'] = date('Y-m-d H:i:s');
				$this->save($current_cron);
			}
		}
	}

	public function run_cron($cron_data, $extra_data = array()) {
		$class_type = strtolower($cron_data['CronJob']['class_type']);
		$class_name = $cron_data['CronJob']['class_name'];
		$method_name = $cron_data['CronJob']['method_name'];

		switch ($class_type) {
			case 'model':
				$this->$class_name = ClassRegistry::init($class_name);
				return $this->$class_name->$method_name($extra_data);
				break;
			case 'component':
				App::import('Core', 'Controller'); 
				App::import('Controller','Accounts');
				$this->AccountsController = new AccountsController();
				$this->AccountsController->components = array(
					$class_name
				);
				$this->AccountsController->constructClasses();
				$this->AccountsController->Postmark->initialize($this->AccountsController);
				$function_args = func_get_args();
				$function_args[0] = &$this->AccountsController;
				call_user_func_array(array($this->AccountsController->$class_name, $method_name), $function_args);
				break;
		}

		return true;
	}

}
