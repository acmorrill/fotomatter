<?php
class ThemeCentersController extends AppController {
    public $name = 'ThemeCenters';
	public $uses = array('ThemeGlobalSetting', 'SiteSetting', 'ThemeHiddenSetting');
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
	
	public function admin_configure_background() {
		
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
		
		$overlay_abs_path = $this->params['form']['overlay_abs_path'];
		$current_background_abs_path = $this->params['form']['current_background_abs_path'];
		$final_background_width = $this->params['form']['final_background_width'];
		$final_background_height = $this->params['form']['final_background_height'];
		$final_background_left = $this->params['form']['final_background_left'];
		$final_background_top = $this->params['form']['final_background_top'];
		$using_custom_background_image = ($this->params['form']['using_custom_background_image'] == 'true') ? true : false ;
		
		$palette_background_size = getimagesize($overlay_abs_path);
		list($orig_palette_background_width, $orig_palette_background_height, $palette_background_size_type, $palette_background_size_attr) = $palette_background_size;
		
		$current_background_size = getimagesize($current_background_abs_path);
		list($orig_background_width, $orig_background_height, $current_background_size_type, $current_background_size_attr) = $current_background_size;
		
		
		$imgOverlay = imagecreatefrompng($overlay_abs_path);
		$imgAvatar = $this->_resize_image($current_background_abs_path, $final_background_width, $final_background_height);

		
		$dst_x = -$final_background_left;
		$dst_y = -$final_background_top;
		$src_x = 0;
		$src_y = 0;
		$dst_w = imagesx($imgAvatar);
		$dst_h = imagesy($imgAvatar);
		$src_w = imagesx($imgAvatar);
		$src_h = imagesy($imgAvatar);

		$o_width = imagesx($imgOverlay);
		$o_height = imagesy($imgOverlay);

		$imgBanner = imagecreatetruecolor($o_width, $o_height);
		$backgroundColor = imagecolorallocate($imgBanner, 255, 255, 255); // DREW TODO use the color from the theme
		imagefill($imgBanner, 0, 0, $backgroundColor);
		
		
//		$this->log('dst_x: '.$dst_x, 'sizes');
//		$this->log('dst_y: '.$dst_y, 'sizes');
//		$this->log('src_x: '.$src_x, 'sizes');
//		$this->log('src_y: '.$src_y, 'sizes');
//		$this->log('dst_w: '.$dst_w, 'sizes');
//		$this->log('dst_h: '.$dst_h, 'sizes');
//		$this->log('src_w: '.$src_w, 'sizes');
//		$this->log('src_h: '.$src_h, 'sizes');
		
		
		imagecopyresampled($imgBanner, $imgAvatar, $dst_x, $dst_y, $src_x, $src_x, $dst_w, $dst_h, $src_w, $src_h);
		imagecopyresampled($imgBanner, $imgOverlay, 0, 0, 0, 0, $o_width, $o_height, $o_width, $o_height);

		$theme_name = $this->SiteSetting->getVal('current_theme', false);
		
		$dest_save_path = SITE_THEME_MERGED_FINAL_IMAGES.DS.$theme_name.'.jpg';
		if (file_exists($dest_save_path)) {
			unlink($dest_save_path);
		}

		// sharpen the bg image before output -- DREW TODO - maybe try and make the sharpening better
		$matrix = array(
            array(-1, -1, -1),
            array(-1, 16, -1),
            array(-1, -1, -1),
        );
        $divisor = array_sum(array_map('array_sum', $matrix));
        $offset = 0; 
        imageconvolution($imgBanner, $matrix, $divisor, $offset);
		
		imagejpeg($imgBanner, $dest_save_path, 100);
		
		if ($using_custom_background_image == true) {
			$this->ThemeHiddenSetting->setVal('uploaded_bg_overlay_abs_path', $overlay_abs_path);
			$this->ThemeHiddenSetting->setVal('uploaded_bg_current_background_abs_path', $current_background_abs_path);
			$this->ThemeHiddenSetting->setVal('uploaded_bg_final_background_width', $final_background_width);
			$this->ThemeHiddenSetting->setVal('uploaded_bg_final_background_height', $final_background_height);
			$this->ThemeHiddenSetting->setVal('uploaded_bg_final_background_left', $final_background_left);
			$this->ThemeHiddenSetting->setVal('uploaded_bg_final_background_top', $final_background_top);
		} else {
			$this->ThemeHiddenSetting->setVal('default_bg_overlay_abs_path', $overlay_abs_path);
			$this->ThemeHiddenSetting->setVal('default_bg_current_background_abs_path', $current_background_abs_path);
			$this->ThemeHiddenSetting->setVal('default_bg_final_background_width', $final_background_width);
			$this->ThemeHiddenSetting->setVal('default_bg_final_background_height', $final_background_height);
			$this->ThemeHiddenSetting->setVal('default_bg_final_background_left', $final_background_left);
			$this->ThemeHiddenSetting->setVal('default_bg_final_background_top', $final_background_top);
		}
		
		
		$returnArr['extra'] = 'this is some extra';
		
//		$this->log(json_encode($returnArr), 'test');
		
		echo json_encode($returnArr);
		exit();
	}
	
	private function _resize_image($file, $w, $h, $crop=FALSE) {
		list($width, $height) = getimagesize($file);
		$r = $width / $height;
		if ($crop) {
			if ($width > $height) {
				$width = ceil($width-($width*($r-$w/$h)));
			} else {
				$height = ceil($height-($height*($r-$w/$h)));
			}
			$newwidth = $w;
			$newheight = $h;
		} else {
			if ($w/$h > $r) {
				$newwidth = $h*$r;
				$newheight = $h;
			} else {
				$newheight = $w/$r;
				$newwidth = $w;
			}
		}
		$src = imagecreatefromjpeg($file);
		$dst = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		return $dst;
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
//					$this->log($exec_command, 'delete_cache');
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