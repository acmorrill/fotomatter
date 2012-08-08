<?php
class ThemesController extends AppController { 
	public $name = 'Themes'; 
	
	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow('choose_gallery');
	}
	
	public function choose_gallery() {
		print('you suck');
		exit();
	}
	
}