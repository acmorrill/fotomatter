<?php


class CronShell extends Shell {
	
	public $uses = array(
		'CronJob'
	);
	
	public function run() {
		$force_run_now = false;
		if (isset($this->args[0])) {
			$force_run_now = true;
			$this->out("-- Force run now --");
		}
		$this->CronJob->check_all_crons($force_run_now);
	}

}




