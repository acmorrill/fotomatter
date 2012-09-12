<?php
class SiteMenusController extends AppController {
     var $name = 'SiteMenus';
     var $uses = array('SiteOneLevelMenu');
	 var $helpers = array('ThemeMenu');
	 
	 public function admin_ajax_set_site_single_level_order($site_one_level_menu_id, $order) {
		$returnArr = array();
		
		$this->SiteOneLevelMenu->id = $site_one_level_menu_id;
		if ($this->SiteOneLevelMenu->moveto($site_one_level_menu_id, $order)) {
			$returnArr['code'] = 1;
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to arrange site_one_level_menu item element in page';
			$this->SiteOneLevelMenu->major_error('failed to arrange site_one_level_menu item element in page');
		}
		
		$this->return_json($returnArr);
	}
	
	public function admin_ajax_delete_one_level_menu_item($site_one_level_menu_id) {
		$returnArr = array();
		
		if ($this->SiteOneLevelMenu->delete($site_one_level_menu_id)) {
			$returnArr['code'] = 1;
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to delete site_one_level_menu item element in page';
			$this->SiteOneLevelMenu->major_error('failed to delete site_one_level_menu item element in page');
		}
		
		
		$this->return_json($returnArr);
	}
	
	public function admin_add_one_level_menu_item($external_model, $external_id) {
		$returnArr = array();
		
		$data = array();
		$data['SiteOneLevelMenu']['external_id'] = $external_id; 
		$data['SiteOneLevelMenu']['external_model'] = $external_model; 
		
		$this->SiteOneLevelMenu->create();
		if ($this->SiteOneLevelMenu->save($data)) {
			$returnArr['code'] = 1;
			
			$new_menu_item = $this->SiteOneLevelMenu->find('all', array(
				'conditions' => array(
					'SiteOneLevelMenu.id' => $this->SiteOneLevelMenu->id
				)
			));
			$returnArr['new_menu_item_html'] = $this->Element('admin/theme_center/main_menu/single_level_menu_item', array('single_menu_items' => $new_menu_item));
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = 'failed to add a one level menu item';
			$this->SiteOneLevelMenu->major_error('failed to add a one level menu item', compact('data'));
		}
		
		$this->return_json($returnArr);
	}
}