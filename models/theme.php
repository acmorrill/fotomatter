<?php

/**
 * TEST NOTES
 * 
 */
class Theme extends AppModel {

	public $name = 'Theme';
	public $belongsTo = array(
		'ParentTheme' => array(
			'className' => 'Theme',
			'foreignKey' => 'theme_id'
		)
	);
	
	public function get_theme_dynamic_background_style($theme_config) {
		if ($theme_config['admin_config']['theme_background_config']['theme_has_dynamic_background'] === true) {
			$this->ThemeGlobalSetting = ClassRegistry::init('ThemeGlobalSetting');
			$break_dynamic_bg_cache = $this->ThemeGlobalSetting->getVal('break_dynamic_bg_cache', false);
			$bg_url_ending = '';
			if ($break_dynamic_bg_cache == true) {
				$bg_url_ending = '?v=' . rand(10000, 99999);
				$this->ThemeGlobalSetting->setVal('break_dynamic_bg_cache', false);
			}
			
			return "<style type='text/css'>body { background: {$theme_config['admin_config']['theme_background_config']['default_bg_color']} url('/theme_merged_final_images/{$theme_config['theme_name']}.jpg{$bg_url_ending}') no-repeat; } </style>";
		}

		if ($theme_config['admin_config']['theme_user_chosen_background']['theme_has_user_chosen_background'] === true) {
			$user_chosen_bg_settings = $theme_config['admin_config']['theme_user_chosen_background'];
			$user_chosen_background_path = $theme_config['admin_config']['theme_avail_custom_settings']['settings'][$user_chosen_bg_settings['background_path_theme_setting_name']]['current_value'];
			$repeat_background_str = 'no-repeat';
			if ($user_chosen_bg_settings['repeating_background'] == true) {
				$repeat_background_str = 'repeat';
			}
			
			$this->ThemeGlobalSetting = ClassRegistry::init('ThemeGlobalSetting');
			$break_dynamic_bg_cache = $this->ThemeGlobalSetting->getVal('break_user_dynamic_bg_cache', false);
			$bg_url_ending = '';
			if ($break_dynamic_bg_cache == true) {
				$bg_url_ending = '?v=' . rand(10000, 99999);
				$this->ThemeGlobalSetting->setVal('break_user_dynamic_bg_cache', false);
			}
			
			return "<style type='text/css'>body { background: url('{$user_chosen_background_path}{$bg_url_ending}') $repeat_background_str; }</style>";
		}
			
		
		return '';
	}
	
	public function reduce_gallery_list_square_size($start_size) {
		$new_size = round($start_size * .9);
		
		return $new_size;
	}
	
	public function get_default_photo_size($format) {
		$current_size = 'small';
		if ($format == 'panoramic' || $format == 'vertical_panoramic') {
			$current_size = 'medium';
		}
		
		return $current_size;
	}
	
	public function get_dynamic_photo_cookie_value($format) {
		$current_size = $this->get_default_photo_size($format);
		
		if (isset($_COOKIE['frontend_photo_size'])) {
			$current_size = $_COOKIE['frontend_photo_size'];
		}
		
		return $current_size;
	}
	
	public function get_dynamic_photo_size($small, $medium, $large, $format) {
		$current_size = $this->get_default_photo_size($format);
		if ($current_size == 'small') {
			$photo_size = $small;
		} else if ($current_size == 'medium') {
			$photo_size = $medium;
		} else if ($current_size == 'large') {
			$photo_size = $large;
		}
		
		return compact('current_size', 'photo_size');
	}
	

	public function get_landing_page_slideshow_images($num_to_grab, $gallery_id = null, $actually_grab_photos = false) {
		// DREW TODO - for now we are just going to grab the the number of images from the first gallery
		// later we will have a way for the user to specify the images that get pulled in

		$this->PhotoGallery = ClassRegistry::init('PhotoGallery');
		$this->PhotoGalleriesPhoto = ClassRegistry::init('PhotoGalleriesPhoto');

		if (empty($gallery_id)) {
			$first_gallery = $this->PhotoGallery->get_first_gallery_by_weight();
			if (!empty($first_gallery['PhotoGallery']['id'])) {
				$gallery_id = $first_gallery['PhotoGallery']['id'];
			}
		}


		$slide_show_photo_ids = array();
		if (!empty($gallery_id)) {
			$slide_show_photo_ids = $this->PhotoGalleriesPhoto->get_gallery_photos_ids_by_weight($gallery_id, $num_to_grab, $actually_grab_photos);
		} else {
			// so just grab the first image among all images
			$this->Photo = ClassRegistry::init('Photo');
			$slide_show_photo_ids = $this->Photo->get_first_n_photos($num_to_grab, $actually_grab_photos);
		}
		
		
		if (empty($slide_show_photo_ids)) {
			$this->major_error("no images to use on landing page");
		}
		

		return $slide_show_photo_ids;
	}

	public function get_all_available_themes() {
		// get the top level themes
		$top_level_themes = $this->find('all', array(
			'conditions' => array(
				'Theme.theme_id' => '0',
				'Theme.disabled' => '0'
			),
			'contain' => false
		));

		$all_themes = array();
		foreach ($top_level_themes as $top_level_theme) {
			// get child themes
			$child_themes = $this->find('all', array(
				'conditions' => array(
					'Theme.theme_id' => $top_level_theme['Theme']['id'],
					'Theme.disabled' => '0'
				),
				'contain' => false
			));

			// add the theme
			$all_themes[] = $top_level_theme;

			// add child themes
			foreach ($child_themes as $child_theme) {
				$all_themes[] = $child_theme;
			}
		}

		return $all_themes;
	}

	public function get_current_theme_id() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$current_theme_ref_name = $this->SiteSetting->getVal('current_theme');

		$curr_theme = $this->find('first', array(
			'conditions' => array(
				'Theme.ref_name' => $current_theme_ref_name
			),
			'contain' => false
		));

		return $curr_theme['Theme']['id'];
	}

	public function get_current_theme() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$current_theme_ref_name = $this->SiteSetting->getVal('current_theme');

		$curr_theme = $this->find('first', array(
			'conditions' => array(
				'Theme.ref_name' => $current_theme_ref_name
			),
			'contain' => false
		));

		return $curr_theme;
	}

	public function add_theme_display_name($theme_name, $add_theme_display_name, $parent_theme_name = null) {
		$parent_theme_id = 0;
		if (!empty($parent_theme_name)) {
			$parent_theme = $this->find('first', array(
				'conditions' => array(
					'Theme.ref_name' => $parent_theme_name
				),
				'fields' => array('id'),
				'contain' => false
			));

			if ($parent_theme) {
				$parent_theme_id = $parent_theme['Theme']['id'];
			}
		}

		$data['Theme'] = array();
		$data['Theme']['display_name'] = $add_theme_display_name;
		$data['Theme']['theme_id'] = $parent_theme_id;
		$data['Theme']['ref_name'] = $theme_name;

		$this->create();
		if ($this->save($data)) {
			return $this->id;
		} else {
			return false;
		}
	}

	public function add_theme($theme_name, $parent_theme_name = null) {
		$parent_theme_id = 0;
		if (!empty($parent_theme_name)) {
			$parent_theme = $this->find('first', array(
				'conditions' => array(
					'Theme.ref_name' => $parent_theme_name
				),
				'fields' => array('id'),
				'contain' => false
			));

			if ($parent_theme) {
				$parent_theme_id = $parent_theme['Theme']['id'];
			}
		}

		$data['Theme'] = array();
		$data['Theme']['theme_id'] = $parent_theme_id;
		$data['Theme']['ref_name'] = $theme_name;

		$this->create();
		if ($this->save($data)) {
			return $this->id;
		} else {
			return false;
		}
	}

	public function get_theme($theme_name) {
		$theme = $this->find('first', array(
			'conditions' => array(
				'Theme.ref_name' => $theme_name
			),
			'fields' => array('id', 'theme_id', 'ref_name'),
			'contain' => array(
				'ParentTheme' => array(
					'fields' => array('id', 'theme_id', 'ref_name')
				)
			)
		));

		return $theme;
	}

	public function change_to_theme($theme_name) {
		// set the current theme in settings
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$this->SiteSetting->setVal('current_theme', $theme_name);


		$theme_root_path = ROOT;
		
		
		//////////////////////////////////
		// use a different ROOT if we are on the welcome site!
			$WELCOME_SITE_URL = WELCOME_SITE_URL;
			if (empty($WELCOME_SITE_URL)) {
				$WELCOME_SITE_URL = 'welcome.fotomatter.net';
			}
			$on_welcome_site = $_SERVER['HTTP_HOST'] === $WELCOME_SITE_URL;
			if ($on_welcome_site === true) {
				// grab the account_id
				$account_id = $this->SiteSetting->getVal('account_id', false);
				if (!empty($account_id)) {
					$theme_root_path = "/var/www/accounts/$account_id";
				}
			}

		
		
		
		//////////////////////////////////////////////
		// change the symlynks
		$path_to_themes = $theme_root_path . DS . APP_DIR . DS . 'themes';
		$new_theme = $this->find('first', array(
			'conditions' => array(
				'Theme.ref_name' => $theme_name
			),
			'contain' => array(
				'ParentTheme'
			)
		));
		unlink("$theme_root_path/current_theme_webroot");
		unlink("$theme_root_path/parent_theme_webroot");
		unlink("$theme_root_path/default_theme_webroot");
		if (!empty($new_theme)) {
			if ($new_theme['Theme']['theme_id'] == 0) {
				exec("ln -s $theme_root_path/app/themes/$theme_name/webroot  $theme_root_path/current_theme_webroot");
				exec("ln -s $theme_root_path/app/themes/$theme_name/webroot  $theme_root_path/parent_theme_webroot");
				exec("ln -s $theme_root_path/app/themes/default/webroot         $theme_root_path/default_theme_webroot");
				$GLOBALS['CURRENT_THEME_PATH'] = "$path_to_themes/$theme_name";
				$GLOBALS['PARENT_THEME_PATH'] = "$path_to_themes/$theme_name";
			} else {
				$new_theme_ref_name = $new_theme['ParentTheme']['ref_name'];
				exec("ln -s $path_to_themes/$new_theme_ref_name/subthemes/$theme_name/webroot $theme_root_path/current_theme_webroot");
				exec("ln -s $path_to_themes/$new_theme_ref_name/webroot  $theme_root_path/parent_theme_webroot");
				exec("ln -s $path_to_themes/default/webroot  $theme_root_path/default_theme_webroot");
				$GLOBALS['CURRENT_THEME_PATH'] = "$path_to_themes/$new_theme_ref_name/subthemes/$theme_name";
				$GLOBALS['PARENT_THEME_PATH'] = "$path_to_themes/$new_theme_ref_name";
			}
		} else {
			exec("ln -s $theme_root_path/app/themes/default/webroot  $theme_root_path/current_theme_webroot");
			exec("ln -s $theme_root_path/app/themes/default/webroot  $theme_root_path/parent_theme_webroot");
			exec("ln -s $theme_root_path/app/themes/default/webroot  $theme_root_path/default_theme_webroot");
			$GLOBALS['CURRENT_THEME_PATH'] = "$path_to_themes/default";
			$GLOBALS['PARENT_THEME_PATH'] = "$path_to_themes/default";
		}
	}

	public function get_theme_background_config_values($theme_config, $reset_to_defaults = false) {
		$background_settings = array();

		$this->ThemeHiddenSetting = ClassRegistry::init('ThemeHiddenSetting');
		$background_settings['use_theme_background'] = $this->ThemeHiddenSetting->getVal('use_theme_background', false);


		$background_settings['image_cache_ending'] = "?r=" . rand(1000, 10000);

		$background_settings['background_config'] = $theme_config['admin_config']['theme_background_config'];
		$background_settings['theme_has_dynamic_background'] = $background_settings['background_config']['theme_has_dynamic_background'];

		if ($background_settings['theme_has_dynamic_background'] === true) {
			///////////////////////////////////////////////////////////////////////
			// get the paths 
			// overlay: the paths to the overlay png
			// default: the starting background image to use if user has not uploaded one
			// uploaded: the path to the user uploaded background image
			// merged: the version that is used on the frontend
			$background_settings['overlay_web_path'] = $background_settings['background_config']['overlay_image']['web_path'];
			$background_settings['overlay_abs_path'] = $background_settings['background_config']['overlay_image']['absolute_path'];
			$background_settings['default_bg_web_path'] = $background_settings['background_config']['default_bg_image']['web_path'];
			$background_settings['default_bg_abs_path'] = $background_settings['background_config']['default_bg_image']['absolute_path'];
			//		$background_settings['uploaded_bg_abs_path = $background_settings['this->Theme->get_theme_uploaded_background_abs_path();
			//		$background_settings['uploaded_bg_web_path = $background_settings['this->Theme->get_theme_uploaded_background_web_path();
			//		$background_settings['merged_bg_abs_path = $background_settings['this->Theme->get_theme_merged_background_abs_path();
			//		$background_settings['merged_bg_web_path = $background_settings['this->Theme->get_theme_merged_background_web_path();
			$background_settings['bg_edit_path'] = $this->get_theme_bd_edited_web_path();


			/////////////////////////////////////////////////////////////////////////////////////////////////////////
			// use_theme_background: means the user has uploaded a custom image for the background
			// populate the current_background starting image
			if ($background_settings['use_theme_background'] == true) {
				$background_settings['current_background_web_path'] = UPLOADED_BACKGROUND_WEB_PATH;
				$background_settings['current_background_abs_path'] = UPLOADED_BACKGROUND_PATH;
			} else {
				$background_settings['current_background_web_path'] = $background_settings['default_bg_web_path'];
				$background_settings['current_background_abs_path'] = $background_settings['default_bg_abs_path'];
			}


			// get sizes for background image (starting image)
			$current_background_size = getimagesize($background_settings['current_background_abs_path']);
			list($background_settings['orig_background_width'], $background_settings['orig_background_height'], $current_background_size_type, $current_background_size_attr) = $current_background_size;

			// get size for starting png pallete image
			$palette_background_size = getimagesize($background_settings['overlay_abs_path']);
			list($background_settings['orig_palette_background_width'], $background_settings['orig_palette_background_height'], $palette_background_size_type, $palette_background_size_attr) = $palette_background_size;


			// set some constants
			$max_background_image_width = 1600;
			$max_background_image_height = 1200;
			$background_settings['max_palette_width'] = $max_background_image_width / 2;
			$background_settings['max_palette_height'] = $max_background_image_height / 2;


			$background_settings['current_background_width'] = $background_settings['orig_background_width'] / 2;
			$background_settings['current_background_height'] = $background_settings['orig_background_height'] / 2;
			$background_settings['palette_background_width'] = $background_settings['orig_palette_background_width'] / 4;
			$background_settings['palette_background_height'] = $background_settings['orig_palette_background_height'] / 4;

			$background_settings['palette_start_left'] = ($background_settings['max_palette_width'] / 2) - ($background_settings['palette_background_width'] / 2);
			$background_settings['palette_start_top'] = ($background_settings['max_palette_height'] / 2) - ($background_settings['palette_background_height'] / 2);




			$start_bounding_box_width = floor($background_settings['max_palette_width'] - (.3 * $background_settings['max_palette_width']));
			$start_bounding_box_height = floor($background_settings['max_palette_height'] - (.3 * $background_settings['max_palette_height']));


			$W_width = $start_bounding_box_width;
			$W_height = round(($W_width * $background_settings['current_background_height']) / $background_settings['current_background_width']);
			$H_height = $start_bounding_box_height;
			$H_width = round(($H_height * $background_settings['current_background_width']) / $background_settings['current_background_height']);

			$use_height = ($H_height * $H_width) < ($W_width * $W_height);

			if ($use_height) {
				$background_settings['start_width'] = $H_width;
				$background_settings['start_height'] = $H_height;
			} else {
				$background_settings['start_width'] = $W_width;
				$background_settings['start_height'] = $W_height;
			}


			$background_settings['start_left'] = ($background_settings['max_palette_width'] / 2) - ($background_settings['start_width'] / 2);
			$background_settings['start_top'] = ($background_settings['max_palette_height'] / 2) - ($background_settings['start_height'] / 2);


			if ($background_settings['use_theme_background'] == true) {
				$background_settings['start_left'] = $this->ThemeHiddenSetting->getVal('uploaded_admin_current_background_left', $background_settings['start_left']);
				$background_settings['start_top'] = $this->ThemeHiddenSetting->getVal('uploaded_admin_current_background_top', $background_settings['start_top']);
				$background_settings['start_width'] = $this->ThemeHiddenSetting->getVal('uploaded_admin_current_background_width', $background_settings['start_width']);
				$background_settings['start_height'] = $this->ThemeHiddenSetting->getVal('uploaded_admin_current_background_height', $background_settings['start_height']);
			} else {
				$background_settings['start_left'] = $this->ThemeHiddenSetting->getVal('default_admin_current_background_left', $background_settings['start_left']);
				$background_settings['start_top'] = $this->ThemeHiddenSetting->getVal('default_admin_current_background_top', $background_settings['start_top']);
				$background_settings['start_width'] = $this->ThemeHiddenSetting->getVal('default_admin_current_background_width', $background_settings['start_width']);
				$background_settings['start_height'] = $this->ThemeHiddenSetting->getVal('default_admin_current_background_height', $background_settings['start_height']);
			}


			////////////////////////////////////////////////////////////////////////////////////////
			// get current gd edit settings
			$background_settings['current_brightness'] = $this->ThemeHiddenSetting->getVal('current_brightness', 0);
			$background_settings['current_contrast'] = $this->ThemeHiddenSetting->getVal('current_contrast', 0);
			$background_settings['current_desaturation'] = $this->ThemeHiddenSetting->getVal('current_desaturation', 100);
			$background_settings['current_inverted'] = $this->ThemeHiddenSetting->getVal('current_inverted', 0);
			if (empty($background_settings['current_brightness'])) {
				$background_settings['current_brightness'] = 0;
			}
			if (empty($background_settings['current_contrast'])) {
				$background_settings['current_contrast'] = 0;
			}
			if (empty($background_settings['current_desaturation'])) {
				$background_settings['current_desaturation'] = 0;
			}
			if (empty($background_settings['current_inverted'])) {
				$background_settings['current_inverted'] = 0;
			}



			///////////////////////////////////////////////////////////////////////////////////////
			// get custom overlay background settings
			$background_settings['custom_overlay_transparency_settings'] = array();
			$background_settings['default_overlay_transparency_settings'] = array();
			foreach ($background_settings['background_config']['overlay_image']['custom_overlay_transparency_fade'] as $custom_overlay_transparency_setting_name => $value) {
				$background_settings['custom_overlay_transparency_settings'][$custom_overlay_transparency_setting_name] = $this->ThemeHiddenSetting->getVal('custom_overlay_setting_' . $custom_overlay_transparency_setting_name, 4);
				$background_settings['default_overlay_transparency_settings'][$custom_overlay_transparency_setting_name] = 4;
			}


			////////////////////////////////////////////////////////////////////////////////////////
			/// recreate the background image on load so don't have to rely on ajax finishing
			$background_settings['small_background_left'] = $background_settings['palette_start_left'] - $background_settings['start_left'];
			$background_settings['small_background_top'] = $background_settings['palette_start_top'] - $background_settings['start_top'];
			$background_settings['final_background_width'] = ($background_settings['orig_palette_background_width'] * $background_settings['start_width']) / $background_settings['palette_background_width'];
			$background_settings['final_background_height'] = ($background_settings['orig_palette_background_height'] * $background_settings['start_height']) / $background_settings['palette_background_height'];
			$background_settings['final_background_left'] = ($background_settings['final_background_width'] * $background_settings['small_background_left']) / $background_settings['start_width'];
			$background_settings['final_background_top'] = ($background_settings['final_background_height'] * $background_settings['small_background_top']) / $background_settings['start_height'];
			if ($reset_to_defaults === true) {
				$this->create_theme_merged_background(
						$background_settings['overlay_abs_path'], $background_settings['current_background_abs_path'], $background_settings['final_background_width'], $background_settings['final_background_height'], $background_settings['final_background_left'], $background_settings['final_background_top'], false, 0, 0, 100, 0, $background_settings['default_overlay_transparency_settings'], $theme_config
				);
			}
		}

		return $background_settings;
	}

	public function get_theme_bd_edited_web_path($theme_name = null) {
		if (!isset($theme_name)) {
			$theme_name = $this->get_theme_name();
		}

		return SITE_THEME_BG_EDITED_IMAGES_WEB_PATH . DS . $theme_name . '.jpg';
	}

	public function change_to_theme_by_id($theme_id) {
		$new_theme = $this->find('first', array(
			'conditions' => array(
				'Theme.id' => $theme_id
			)
		));

		$this->change_to_theme($new_theme['Theme']['ref_name']);
	}

	public function get_theme_uploaded_background_abs_path($theme_name) {
		return SITE_THEME_UPLOADED_IMAGES . DS . $theme_name;
	}

	public function get_theme_name() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting', 'Model');

		return $this->SiteSetting->getVal('current_theme', false);
	}

	public function get_theme_uploaded_background_web_path($theme_name) {
		return SITE_THEME_UPLOADED_IMAGES_WEB_PATH . DS . $theme_name;
	}

	public function get_theme_merged_background_abs_path($theme_name) {
		return SITE_THEME_MERGED_FINAL_IMAGES . DS . $theme_name;
	}

	public function get_theme_merged_background_web_path($theme_name) {
		return SITE_THEME_MERGED_FINAL_IMAGES_WEB_PATH . DS . $theme_name;
	}

	public function theme_is_parent($theme_name) {
		$curr_theme = $this->find('first', array(
			'conditions' => array(
				'Theme.ref_name' => $theme_name
			),
			'fields' => array('id', 'theme_id'),
			'contain' => false
		));

		if (!empty($curr_theme) && $curr_theme['Theme']['theme_id'] == 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_theme_parent($theme_name) {
		$theme = $this->find('first', array(
			'conditions' => array(
				'Theme.ref_name' => $theme_name
			),
			'fields' => array('id', 'theme_id'),
			'contain' => array(
				'ParentTheme'
			)
		));

		if (!empty($theme['ParentTheme'])) {
			return $theme['ParentTheme'];
		} else {
			return false;
		}
	}

	public function current_is_child_theme() {
		if ($GLOBALS['CURRENT_THEME_PATH'] == $GLOBALS['PARENT_THEME_PATH']) {
			return false;
		} else {
			return true;
		}
	}

	public function display_name_exists($display_name) {
		$theme = $this->find('first', array(
			'conditions' => array(
				'Theme.display_name' => $display_name
			),
			'fields' => array('id'),
			'contain' => false
		));
		if (empty($theme)) {
			return false;
		} else {
			return true;
		}
	}

	public function theme_exists($theme_name) {
		$curr_theme = $this->find('first', array(
			'conditions' => array(
				'Theme.ref_name' => $theme_name
			),
			'fields' => array('id'),
			'contain' => false
		));

		if (!empty($curr_theme)) {
			return true;
		} else {
			return false;
		}
	}

	public function get_path_to_theme($theme_name) {
		$curr_theme = $this->find('first', array(
			'conditions' => array(
				'Theme.ref_name' => $theme_name
			),
			'fields' => array('id', 'theme_id'),
			'contain' => array(
				'ParentTheme' => array(
					'fields' => array('id', 'ref_name')
				)
			)
		));

		if (!empty($curr_theme)) {
			if ($curr_theme['Theme']['theme_id'] == 0) { // is parent
				return PATH_TO_THEMES . DS . $theme_name;
			} else {
				// I'm forgoing error checking here in favor of proformance because this happens on every request
				return PATH_TO_THEMES . DS . $curr_theme['ParentTheme']['ref_name'] . DS . 'subthemes' . DS . $theme_name;
			}
		} else {
			return false;
		}
	}

	public function create_theme_merged_background(
		$overlay_abs_path, 
		$current_background_abs_path, 
		$final_background_width, 
		$final_background_height, 
		$final_background_left, 
		$final_background_top, 
		$using_custom_background_image, 
		$current_brightness, 
		$current_contrast, 
		$current_desaturation, 
		$current_inverted,
		$custom_overlay_transparency_settings,
		$theme_config
	) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$this->ThemeHiddenSetting = ClassRegistry::init('ThemeHiddenSetting');
		$this->ThemeGlobalSetting = ClassRegistry::init('ThemeGlobalSetting');
		$theme_name = $this->SiteSetting->getVal('current_theme', false);


		$palette_background_size = getimagesize($overlay_abs_path);
		list($orig_palette_background_width, $orig_palette_background_height, $palette_background_size_type, $palette_background_size_attr) = $palette_background_size;

		$current_background_size = getimagesize($current_background_abs_path);
		list($orig_background_width, $orig_background_height, $current_background_size_type, $current_background_size_attr) = $current_background_size;


		$imgOverlay = imagecreatefrompng($overlay_abs_path);
		$imgAvatar = $this->_resize_image($current_background_abs_path, $final_background_width, $final_background_height);

		///////////////////////////////////////////////////////////////////////////////////////////////
		// apply filters to image (desaturation, desat, contrast etc)
//		$current_brightness,
//		$current_contrast,
//		$current_desaturation,
//		$current_inverted

		$this->ThemeHiddenSetting->setVal('current_brightness', $current_brightness);
		$this->ThemeHiddenSetting->setVal('current_desaturation', $current_desaturation);
		$this->ThemeHiddenSetting->setVal('current_contrast', $current_contrast);
		$this->ThemeHiddenSetting->setVal('current_inverted', $current_inverted);
		if ($current_desaturation != 100) {
			if (imagecopymergegray($imgAvatar, $imgAvatar, 0, 0, 0, 0, imagesx($imgAvatar), imagesy($imgAvatar), $current_desaturation) === false) {
				$this->major_error("failed to change saturation on dynamic background");
			}
		}
		if ($current_brightness != 0) {
			if (imagefilter($imgAvatar, IMG_FILTER_BRIGHTNESS, $current_brightness) === false) { // -255 = min brightness, 0 = no change, +255 = max brightness
				$this->major_error("failed to change brightness on dynamic background");
			}
		}
		if ($current_contrast != 0) {
			if (imagefilter($imgAvatar, IMG_FILTER_CONTRAST, -$current_contrast) === false) { // -100 = max contrast, 0 = no change, +100 = min contrast (note the direction!)
				$this->major_error("failed to change contrast on dynamic background");
			}
		}
		if ($current_inverted == 1) {
			$width = imagesx($imgAvatar);
			$height = imagesy($imgAvatar);
			$dest = imagecreatetruecolor($width, $height);
			for ($i = 0; $i < $width; $i++) {
				imagecopy($dest, $imgAvatar, ($width - $i - 1), 0, $i, 0, 1, $height);
			}
			$imgAvatar = $dest;
		}
		$bg_edit_save_path = SITE_THEME_BG_EDITED_IMAGES . DS . $theme_name . '.jpg';
		if (file_exists($bg_edit_save_path)) {
			unlink($bg_edit_save_path);
		}
		imagejpeg($imgAvatar, $bg_edit_save_path, 100);



		$dst_x = -$final_background_left;
		$dst_y = -$final_background_top;
		$src_x = 0;
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
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// sharpen the bg image before output -- DREW TODO - maybe try and make the sharpening better
		$matrix = array(
			array(-1, -1, -1),
			array(-1, 16, -1),
			array(-1, -1, -1),
		);
		$divisor = array_sum(array_map('array_sum', $matrix));
		$offset = 0;
		imageconvolution($imgAvatar, $matrix, $divisor, $offset);


		imagecopyresampled($imgBanner, $imgAvatar, $dst_x, $dst_y, $src_x, $src_x, $dst_w, $dst_h, $src_w, $src_h);


		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// prepare custom overlay transparency settings
		$custom_transparency_settings = $theme_config['admin_config']['theme_background_config']['overlay_image']['custom_overlay_transparency_fade'];
		$has_not_default_transparency_settings = false;
//		$this->get_overlay_transparency_multiplyer(2, 2, $custom_overlay_transparency_settings, $custom_transparency_settings);
		if (!empty($custom_transparency_settings)) {
			foreach ($custom_transparency_settings as &$custom_transparency_setting) {
				if ($custom_transparency_setting['tl']['x'] === '*') {
					$custom_transparency_setting['tl']['x'] = $o_width - 1;
				}
				if ($custom_transparency_setting['tl']['y'] === '*') {
					$custom_transparency_setting['tl']['y'] = $o_height - 1;
				}
				if ($custom_transparency_setting['br']['x'] === '*') {
					$custom_transparency_setting['br']['x'] = $o_width - 1;
				}
				if ($custom_transparency_setting['br']['y'] === '*') {
					$custom_transparency_setting['br']['y'] = $o_height - 1;
				}
			}
			// save current custom transparency settings
			foreach ($custom_overlay_transparency_settings as $custom_overlay_transparency_setting_name => $custom_overlay_transparency_setting) {
				if ($custom_overlay_transparency_setting != 4) {
					$has_not_default_transparency_settings = true;
				}
				$this->ThemeHiddenSetting->setVal('custom_overlay_setting_' . $custom_overlay_transparency_setting_name, $custom_overlay_transparency_setting);
			}
		}
		unset($custom_transparency_setting);

		if ($has_not_default_transparency_settings === true && !empty($custom_transparency_settings)) {
			for ($x = 0; $x < $o_width; $x++) {
				for ($y = 0; $y < $o_height; $y++) {
					$ovrARGB = imagecolorat($imgOverlay, $x, $y);
					$ovrA = ($ovrARGB >> 24) << 1;
					$ovrR = $ovrARGB >> 16 & 0xFF;
					$ovrG = $ovrARGB >> 8 & 0xFF;
					$ovrB = $ovrARGB & 0xFF;

					$change = false;
					if ($ovrA == 0) {
						$dstR = $ovrR;
						$dstG = $ovrG;
						$dstB = $ovrB;
						$change = true;
					} elseif ($ovrA < 254) {
						/////////////////////////////////////////////////////////////////////////////////////////////////////
						// figure out which custom transparency box the pixel is in
						foreach ($custom_transparency_settings as $custom_transparency_name => $custom_transparency_setting) {
							if ($y >= $custom_transparency_setting['tl']['y'] && $y <= $custom_transparency_setting['br']['y'] && $x >= $custom_transparency_setting['tl']['x'] && $x <= $custom_transparency_setting['br']['x']) {
								$ovrA = ($custom_overlay_transparency_settings[$custom_transparency_name] / 4) * $ovrA;
								if ($ovrA > 254) {
									$ovrA = 254;
								}
								break(1);
							}
						}


						$dstARGB = imagecolorat($imgBanner, $x, $y);
						$dstR = $dstARGB >> 16 & 0xFF;
						$dstG = $dstARGB >> 8 & 0xFF;
						$dstB = $dstARGB & 0xFF;

						$dstR = (($ovrR * (0xFF - $ovrA)) >> 8) + (($dstR * $ovrA) >> 8);
						$dstG = (($ovrG * (0xFF - $ovrA)) >> 8) + (($dstG * $ovrA) >> 8);
						$dstB = (($ovrB * (0xFF - $ovrA)) >> 8) + (($dstB * $ovrA) >> 8);
						$change = true;
					}
					if ($change) {
						$dstRGB = imagecolorallocatealpha($imgBanner, $dstR, $dstG, $dstB, 0);
						imagesetpixel($imgBanner, $x, $y, $dstRGB);
					}
				}
			}
		} else {
			imagecopyresampled($imgBanner, $imgOverlay, 0, 0, 0, 0, $o_width, $o_height, $o_width, $o_height);
		}




		$dest_save_path = SITE_THEME_MERGED_FINAL_IMAGES . DS . $theme_name . '.jpg';
		if (file_exists($dest_save_path)) {
			unlink($dest_save_path);
		}



		imagejpeg($imgBanner, $dest_save_path, 100);
		$this->ThemeGlobalSetting->setVal('break_dynamic_bg_cache', true);
		

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
	}

	private function _resize_image($file, $w, $h, $crop = FALSE) {
		list($width, $height) = getimagesize($file);
		$r = $width / $height;
		if ($crop) {
			if ($width > $height) {
				$width = ceil($width - ($width * ($r - $w / $h)));
			} else {
				$height = ceil($height - ($height * ($r - $w / $h)));
			}
			$newwidth = $w;
			$newheight = $h;
		} else {
			if ($w / $h > $r) {
				$newwidth = $h * $r;
				$newheight = $h;
			} else {
				$newheight = $w / $r;
				$newwidth = $w;
			}
		}
		$src = imagecreatefromjpeg($file);
		$dst = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		return $dst;
	}

	public function get_theme_setting($name, $default = false) {
		$this->ThemeGlobalSetting = ClassRegistry::init('ThemeGlobalSetting', 'Model');
		
		return $this->ThemeGlobalSetting->getVal($name, $default);
	}
	
	public function get_theme_hidden_setting($name, $default = false) {
		$this->ThemeHiddenSetting = ClassRegistry::init('ThemeHiddenSetting', 'Model');
		
		return $this->ThemeHiddenSetting->getVal($name, $default);
	}
}
