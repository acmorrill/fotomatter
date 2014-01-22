<?php
class ThemeCentersController extends AppController {
    public $name = 'ThemeCenters';
	public $uses = array('ThemeGlobalSetting', 'SiteSetting', 'ThemeHiddenSetting', 'Theme', 'ThemeUserSetting', 'ThemeHiddenSetting');
	public $helpers = array(
		'Page',
		'Gallery',
		'ThemeMenu',
		'ThemeHiddenSetting',
	);
	
	
	public function  beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'admin/theme_centers';
		
		//$this->Auth->allow('view_photo');
	}
	
	public function admin_index() {
		
	}
	
	public function admin_ajax_save_theme_settings() {
		$returnArr = array();
		$returnArr['code'] = 1;
		
		$setting_name = isset($this->params['form']['setting_name']) ? $this->params['form']['setting_name'] : null;
		$setting_value = isset($this->params['form']['setting_value']) ? $this->params['form']['setting_value'] : null;
		$theme_id = isset($this->params['form']['theme_id']) ? $this->params['form']['theme_id'] : null;
		$current_theme_id = $this->Theme->get_current_theme_id();
		if (!isset($setting_name) || !isset($setting_value) || !isset($theme_id) || $theme_id != $current_theme_id) {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'invalid params';
			$this->return_json($returnArr);
		}
		
		
		if (!$this->ThemeUserSetting->setVal($setting_name, $setting_value, $theme_id)) {
			$returnArr['code'] = -1;
			$this->Theme->major_error('failed to save theme user setting', compact('setting_name', 'setting_value', 'theme_id'));
			$returnArr['message'] = 'failed to save theme user setting';
		}
		
		
		$this->return_json($returnArr);
	}
	
	public function admin_theme_settings() {
		$avail_settings_list = $this->viewVars['theme_config']['admin_config']['theme_avail_custom_settings']['settings'];
		
		$theme_id = $this->Theme->get_current_theme_id();
		
		$this->set(compact('avail_settings_list', 'theme_id'));
	}
	
	public function admin_main_menu() {
		
	}
	
	public function admin_configure_logo() {
		
	}
	
	public function admin_configure_background() {
		$use_theme_background = $this->ThemeHiddenSetting->getVal('use_theme_background', false);
		
		
		$this->set(compact('use_theme_background'));
	}
	
	
	public function admin_choose_theme() {
		if (!empty($this->data['new_theme_id'])) {
			$this->Theme->change_to_theme_by_id($this->data['new_theme_id']);
			$new_theme_config = $this->ThemeRenderer->_process_theme_config_with_user_settings(true);
			$this->Theme->after_change_to_theme($new_theme_config);
		}
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
	
	public function admin_ajax_create_merged_bg_and_save_bg_config() {
		// DREW TODO - put in major error checks into this function
		// DREW TODO - make sure the default background color for the theme is also passed and used in this function
		
		$returnArr = array();
		$returnArr['code'] = 1;
		$using_custom_background_image = ($this->params['form']['using_custom_background_image'] == 'true') ? true : false ;
		
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////
		// get the javascript needed for admin current drag/resize status for next load (save to theme config)
		$current_background_width = $this->params['form']['current_background_width'];
		$current_background_height = $this->params['form']['current_background_height'];
		$current_background_left = $this->params['form']['current_background_left'];
		$current_background_top = $this->params['form']['current_background_top'];
		if (empty($current_background_width) || empty($current_background_height) || empty($current_background_left) || empty($current_background_top)) {
			$this->major_error('one of the current background paths was empty in ajax_create_merged_bg_and_save_bg_config', array(), 'high');
		}
		if ($using_custom_background_image == true) {
			$this->ThemeHiddenSetting->setVal('uploaded_admin_current_background_width', $current_background_width);
			$this->ThemeHiddenSetting->setVal('uploaded_admin_current_background_height', $current_background_height);
			$this->ThemeHiddenSetting->setVal('uploaded_admin_current_background_left', $current_background_left);
			$this->ThemeHiddenSetting->setVal('uploaded_admin_current_background_top', $current_background_top);
		} else {
			$this->ThemeHiddenSetting->setVal('default_admin_current_background_width', $current_background_width);
			$this->ThemeHiddenSetting->setVal('default_admin_current_background_height', $current_background_height);
			$this->ThemeHiddenSetting->setVal('default_admin_current_background_left', $current_background_left);
			$this->ThemeHiddenSetting->setVal('default_admin_current_background_top', $current_background_top);
		}
		
		
		
		$overlay_abs_path = $this->params['form']['overlay_abs_path'];
		$current_background_abs_path = $this->params['form']['current_background_abs_path'];
		$final_background_width = $this->params['form']['final_background_width'];
		$final_background_height = $this->params['form']['final_background_height'];
		$final_background_left = $this->params['form']['final_background_left'];
		$final_background_top = $this->params['form']['final_background_top'];

		if (empty($overlay_abs_path)) {
			$this->major_error('overlay_abs_path empty', array(), 'high');
		}
		if (empty($current_background_abs_path)) {
			$this->major_error('current_background_abs_path empty', array(), 'high');
		}
		if (empty($final_background_width)) {
			$this->major_error('final_background_width empty', array(), 'high');
		}
		if (empty($final_background_height)) {
			$this->major_error('final_background_height empty', array(), 'high');
		}
		if (empty($final_background_left)) {
			$this->major_error('final_background_left empty', array(), 'high');
		}
		if (empty($final_background_top)) {
			$this->major_error('final_background_top empty', array(), 'high');
		}
		if (empty($using_custom_background_image)) {
			$this->major_error('using_custom_background_image empty', array(), 'high');
		}
		
		
		$this->Theme->create_theme_merged_background(
				$overlay_abs_path, 
				$current_background_abs_path, 
				$final_background_width, 
				$final_background_height, 
				$final_background_left,
				$final_background_top,
				$using_custom_background_image
		);
		
		
		echo json_encode($returnArr);
		exit();
	}
	
	
	public function admin_upload_logo_file() {
		if (isset($this->params['form']['hidden_logo_file_chooser'])) {
			$upload_data['name'] = $this->params['form']['hidden_logo_file_chooser']['name'];
			$upload_data['tmp_name'] = $this->params['form']['hidden_logo_file_chooser']['tmp_name'];
			$upload_data['type'] = $this->params['form']['hidden_logo_file_chooser']['type'];
			$upload_data['size'] = $this->params['form']['hidden_logo_file_chooser']['size'];
			
			
			if ($upload_data['size'] > 307200) { // fail if logo image bigger than 300k
				$this->Session->setFlash(__('Exceeded maximum logo upload size of 300 kilobytes.', true));
				$this->redirect('/admin/theme_centers/configure_logo/');
				exit();
			}
			
			
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
//					$this->log($exec_command, 'delete_cache');
					if ($return_var != 0) {
						$this->major_error('Failed to delete logo uploaded cache files', compact('logo_caches_dir'));
					}
					
					$this->ThemeGlobalSetting->setVal('use_theme_logo', false);
					$this->Session->setFlash(__('Successfully uploaded logo', true));
				} else {
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
	
	public function admin_upload_background_file() {
		if (isset($this->params['form']['hidden_background_file_chooser'])) {
			$upload_data['name'] = $this->params['form']['hidden_background_file_chooser']['name'];
			$upload_data['tmp_name'] = $this->params['form']['hidden_background_file_chooser']['tmp_name'];
			$upload_data['type'] = $this->params['form']['hidden_background_file_chooser']['type'];
			$upload_data['size'] = $this->params['form']['hidden_background_file_chooser']['size'];
			
			
			if ($upload_data['size'] > 2097152) { // fail if background image bigger than 2 megs
				$this->Session->setFlash(__('Exceeded maximum background upload size of 2 megabytes.', true));
				$this->redirect('/admin/theme_centers/configure_background/');
				exit();
			}
			
			
			$background_file_data = getimagesize($upload_data['tmp_name']);
			if ($background_file_data !== false) {
				list($width, $height, $type, $attr) = $background_file_data;
	
				$filename_info = pathinfo($upload_data['name']);
				$is_jpeg_extension = isset($filename_info['extension']) && ($filename_info['extension'] == 'jpg' || $filename_info['extension'] == 'jpeg');
				$is_jpeg_mime_type = isset($upload_data['type']) && ($upload_data['type'] == 'image/jpg' || $upload_data['type'] == 'image/jpeg');
				if (!$is_jpeg_extension && !$is_jpeg_mime_type) {
					$this->major_error('Tried to upload a non jpg image.');
					$this->Session->setFlash(__('Only jpg images can be uploaded as the a theme background', true));
					$this->redirect('/admin/theme_centers/configure_background/');
					exit();
				}
				
				if(move_uploaded_file($upload_data['tmp_name'], UPLOADED_BACKGROUND_PATH)) {
					chmod(UPLOADED_BACKGROUND_PATH, 0776);
					
					$this->ThemeHiddenSetting->setVal('use_theme_background', true); // use theme background is used for seeing of we are using the theme default background image
					$this->Session->setFlash(__('Successfully uploaded background', true));
					$this->ThemeHiddenSetting->clear_theme_background_position_cache();
				} else{
					$this->major_error('failed to move_uploaded_file of an uploaded background');
					$this->Session->setFlash(__('Failed to upload background file', true));
				}
			} else {
				$this->major_error('failed to getimagesize of an uploaded background');
				$this->Session->setFlash(__('Could not upload the background file', true));
			}
		}
		
		$this->redirect('/admin/theme_centers/configure_background/');
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
	
	public function admin_set_use_theme_background() {
		
		if (isset($this->params['form']['change_background_choice'])) {
			if ($this->params['form']['change_background_choice'] == 'custom_background') {
				$this->ThemeHiddenSetting->setVal('use_theme_background', true);
			} else {
				$this->ThemeHiddenSetting->setVal('use_theme_background', false);
			}
			
			$this->ThemeHiddenSetting->clear_theme_background_position_cache();
		} else {
			$this->major_error('failed to admin_set_use_theme_background');
			$this->Session->setFlash(__('Could not change the background that is used', true));
		}
		
		
		
		$this->redirect('/admin/theme_centers/configure_background/');
		exit();
	}
	
}