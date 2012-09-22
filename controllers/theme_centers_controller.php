<?php
class ThemeCentersController extends AppController {
    public $name = 'ThemeCenters';
	public $uses = array('ThemeGlobalSetting');
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
	
	public function admin_configure_logo() {
		
	}
	
	public function admin_ajax_get_logo_webpath_and_save_dimension($height, $width, $top, $left) {
		App::import('Helper', 'ThemeLogo'); 
        $ThemeLogo = new ThemeLogoHelper();
		
		$returnArr = array();
		
		$logo_path = $ThemeLogo->get_logo_cache_size_path($height, $width);
		if ($logo_path !== false) {
			$this->ThemeGlobalSetting->setVal('logo_current_height', $height);
			$this->ThemeGlobalSetting->setVal('logo_current_width', $width);
			$this->ThemeGlobalSetting->setVal('logo_current_top', $top);
			$this->ThemeGlobalSetting->setVal('logo_current_left', $left);
			
			$returnArr['code'] = 1;
			$returnArr['logo_path'] = $logo_path;
		} else {
			$this->major_error('failed to get the logo cache size via ajax');
			$returnArr['code'] = -1;
		}
		
		
		$this->return_json($returnArr);
	}
	
}