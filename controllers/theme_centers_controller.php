<?php
class ThemeCentersController extends AppController {
    public $name = 'ThemeCenters';
	public $uses = array();
	public $helpers = array(
		'Page',
		'Gallery',
		'ThemeMenu'
	);
	
	
	public function  beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'admin/theme_centers';
		
		//$this->Auth->allow('view_photo');
	}
	
	public function admin_index() {
		
	}
	
	public function admin_main_menu() {
		
	}
	
}