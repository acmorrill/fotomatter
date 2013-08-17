<?php
class WelcomeController extends AppController {
    public $name = 'Welcome';
	
	public function  beforeFilter() {
		parent::beforeFilter();
	}
	
    public function login($authentication) {
		
	}
}
