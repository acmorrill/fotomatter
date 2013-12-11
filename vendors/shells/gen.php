<?php

class GenShell extends Shell {
	public $uses = array('User', 'Group', 'Permission');
	
		///////////////////////////////////////////////////////////////
	/// shell start
	function _welcome() {
		Configure::write('debug', 1);

		$this->out();
		$this->out('Welcome to CakePHP v' . Configure::version() . ' Console');
		$this->hr();
		$this->out('App : '. $this->params['app']);
		$this->out('Path: '. $this->params['working']);
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

		switch ($kind) {
			case 'add_user':
				$this->out("
Add an admin user

");
				break;
			default:
				$this->out("
	help [function_name]
		-- more specific help on any function
		
	add_user [email] [password]
		-- add user
		
	list_cloudfiles
		-- list the current cloud files
");
		}
	}
	
	public function add_user() {
		if (count($this->args) != 2) {
			$this->error('You must supply an email address and password.');
			exit(1);
		}
		
		App::import('Core', 'Security');
		
		$devGroup = $this->Group->find('first', array(
			'conditions' => array('Group.name' => 'System Developers')
		));
		
		
		$data['User']['email_address'] = $this->args[0];
		$data['User']['password'] = Security::hash($this->args[1], null, true);
		$data['User']['active'] = '1';
		$data['Group'][0] = $devGroup['Group']['id'];
		
		$exists = $this->User->find('first', array(
			'conditions' => array('User.email_address' => $this->args[0])
		));
		if ($exists != array()) {
			$data['User']['id'] = $exists['User']['id'];
		}
		
		if ($this->User->save($data)) {
			$this->out('User created '.$data['User']['email_address']);
		} else {
			$this->error('Failed to create user.');
		}
		exit();
	}
	
	public function list_cloudfiles() {
		App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
		
		debug($this->files->list_objects());
	}
	
	public function list_cloudcontainers() {
		App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
		
		debug($this->files->list_containers());
	}

	
	
}