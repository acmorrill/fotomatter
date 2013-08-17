<?php
class WelcomeController extends AppController {
    public $name = 'Welcome';
	public $uses = array();
	
	public function  beforeFilter() {
		// DREW TODO
		// make sure not logged in
		// make sure has hash get param is set
		
		
		
		$this->Auth->allow('*');
		
 		parent::beforeFilter();
	}
	
    public function index() {
		
	}
	
	
	///
	// stuff it limit
	// 1) choose your password
	// 2) choose your theme
	// 3) 
}
