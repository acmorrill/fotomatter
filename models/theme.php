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
		
		// change the symlynks
		$new_theme = $this->find('first', array(
			'conditions' => array(
				'Theme.ref_name' => $theme_name
			),
			'contain' => array(
				'ParentTheme'
			)
		));
		unlink(ROOT.DS.'current_theme_webroot');
		unlink(ROOT.DS.'parent_theme_webroot');
		unlink(ROOT.DS.'default_theme_webroot');
		if (!empty($new_theme)) {
			if ($new_theme['Theme']['theme_id'] == 0) {
				exec('ln -s '.ROOT.DS.'app/themes/'.$theme_name.'/webroot '.ROOT.DS.'current_theme_webroot');
				exec('ln -s '.ROOT.DS.'app/themes/'.$theme_name.'/webroot '.ROOT.DS.'parent_theme_webroot');
				exec('ln -s '.ROOT.DS.'app/themes/default/webroot '.ROOT.DS.'default_theme_webroot');
			} else {
				exec('ln -s '.PATH_TO_THEMES.DS.$new_theme['ParentTheme']['ref_name'].DS.'subthemes'.DS.$theme_name.'/webroot '.ROOT.DS.'current_theme_webroot');
				exec('ln -s '.PATH_TO_THEMES.DS.$new_theme['ParentTheme']['ref_name'].'/webroot '.ROOT.DS.'parent_theme_webroot');
				exec('ln -s '.PATH_TO_THEMES.DS.'default/webroot '.ROOT.DS.'default_theme_webroot');
			}
		} else {
			exec('ln -s '.ROOT.DS.'app/themes/default/webroot '.ROOT.DS.'current_theme_webroot');
			exec('ln -s '.ROOT.DS.'app/themes/default/webroot '.ROOT.DS.'parent_theme_webroot');
			exec('ln -s '.ROOT.DS.'app/themes/default/webroot '.ROOT.DS.'default_theme_webroot');
		}
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
		return SITE_THEME_UPLOADED_IMAGES.DS.$theme_name;
	}
	public function get_theme_uploaded_background_web_path($theme_name) {
		return SITE_THEME_UPLOADED_IMAGES_WEB_PATH.DS.$theme_name;
	}
	public function get_theme_merged_background_abs_path($theme_name) {
		return SITE_THEME_MERGED_FINAL_IMAGES.DS.$theme_name;
	}
	public function get_theme_merged_background_web_path($theme_name) {
		return SITE_THEME_MERGED_FINAL_IMAGES_WEB_PATH.DS.$theme_name;
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
		if (CURRENT_THEME_PATH == PARENT_THEME_PATH) {
			return false;
		} else {
			return true;
		}
	}
	
	public function display_name_exists($display_name) {
		$theme = $this->find('first', array(
			'conditions'=>array(
				'Theme.display_name'=>$display_name
			),
			'fields'=> array('id'),
			'contain'=>false
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
			'fields' => array( 'id', 'theme_id'),
			'contain' => array(
				'ParentTheme' => array(
					'fields' => array('id', 'ref_name')
				)
			)
		));
		
		if (!empty($curr_theme)) {
			if ($curr_theme['Theme']['theme_id'] == 0) { // is parent
				return PATH_TO_THEMES.DS.$theme_name;
			} else {
				// I'm forgoing error checking here in favor of proformance because this happens on every request
				return PATH_TO_THEMES.DS.$curr_theme['ParentTheme']['ref_name'].DS.'subthemes'.DS.$theme_name;
			}
		} else {
			return false;
		}
	}
	

}