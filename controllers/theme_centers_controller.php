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
		
		$use_theme_logo = $this->ThemeGlobalSetting->getVal('use_theme_logo', true);
		$logo_path = $ThemeLogo->get_logo_cache_size_path($height, $width, false, $use_theme_logo);
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
	
	public function admin_upload_logo_file() {
		if (isset($this->params['form']['hidden_logo_file_chooser'])) {
			$upload_data['name'] = $this->params['form']['hidden_logo_file_chooser']['name'];
			$upload_data['tmp_name'] = $this->params['form']['hidden_logo_file_chooser']['tmp_name'];
			$upload_data['type'] = $this->params['form']['hidden_logo_file_chooser']['type'];
			$upload_data['size'] = $this->params['form']['hidden_logo_file_chooser']['size'];
			
			$logo_file_data = getimagesize($upload_data['tmp_name']);
			if ($logo_file_data !== false) {
				list($width, $height, $type, $attr) = $logo_file_data;
	
				// DREW TODO - make it so other types of images can be upload but just make sure it gets converted to a png before save
				$filename_info = pathinfo($upload_data['name']);
				if (!isset($filename_info['extension']) || $filename_info['extension'] != 'png' || !isset($upload_data['type']) || $upload_data['type'] != 'image/png') {
					$this->major_error('Tried to upload a non png image.');
					$this->Session->setFlash(__('Only png images can be uploaded as the logo', true));
					$this->redirect('/admin/theme_centers/configure_logo/');
					exit();
				}
				
				if(move_uploaded_file($upload_data['tmp_name'], UPLOADED_LOGO_PATH)) {
					chmod(UPLOADED_LOGO_PATH, 0776);
					
					// clear all uploaded cache files
					$logo_caches_dir = SITE_LOGO_CACHES_PATH;
					$exec_command = "find $logo_caches_dir -name '*_uploaded.png' -depth -type f -delete";
					exec($exec_command, $output, $return_var);
					$this->log($exec_command, 'delete_cache');
					if ($return_var != 0) {
						$this->major_error('Failed to delete logo uploaded cache files', compact('logo_caches_dir'));
					}
					
					$this->ThemeGlobalSetting->setVal('use_theme_logo', false);
					$this->Session->setFlash(__('Successfully uploaded logo', true));
				} else{
					$this->major_error('failed to move_uploaded_file of an uploaded logo');
					$this->Session->setFlash(__('Failed to upload logo file', true));
				}
				
				
			} else {
				$this->major_error('failed to getimagesize of an uploaded logo');
				$this->Session->setFlash(__('Could not upload the logo file', true));
			}
		}
		
		$this->redirect('/admin/theme_centers/configure_logo/');
	}
	
	public function admin_set_use_theme_logo() {
		
		if (isset($this->params['form']['change_logo_choice'])) {
			if ($this->params['form']['change_logo_choice'] == 'theme_logo') {
				$this->ThemeGlobalSetting->setVal('use_theme_logo', true);
			} else {
				$this->ThemeGlobalSetting->setVal('use_theme_logo', false);
			}
		} else {
			$this->major_error('failed to admin_set_use_theme_logo');
			$this->Session->setFlash(__('Could not change the logo that is used', true));
		}
		
		
		$this->redirect('/admin/theme_centers/configure_logo/');
		exit();
	}
	
}