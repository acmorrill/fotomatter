<?php


class CronShell extends Shell {
	
	public $uses = array(
		'CronJob'
	);
	
	
	///////////////////////////////////////////////////////////////
	/// shell start
	function _welcome() {
		Configure::write('debug', 1);

		$this->out();
		$this->out('Welcome to CakePHP v' . Configure::version() . ' Console');
		$this->hr();
		$this->out('App : ' . $this->params['app']);
		$this->out('Path: ' . $this->params['working']);
		$this->hr();
	}

	function main() {
		$this->help();
	}

	function help() {
		$kind = '';
		if (count($this->args)) {
			$kind = $this->args[0];
		}

		$str = "cake cron";
		switch ($kind) {
			default:
				$this->out("
---- $str run {(optional)true}
	- runs all crons for the current app
	- if pass true forces to run instantly
");
		}
	}
	
	public function run() {
		$force_run_now = false;
		if (isset($this->args[0])) {
			$force_run_now = true;
			$this->out("-- Force run now --");
		}
		$this->CronJob->check_all_crons($force_run_now);
	}

}




