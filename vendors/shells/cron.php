<?php


class CronShell extends Shell {
	
	public $uses = array(
		'CronJob'
	);
	
	public function run() {
		$this->CronJob->check_all_crons();
	}

}




