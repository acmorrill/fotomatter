<?php

class SuperadminsController extends AppController {
	
	public $layout = 'admin/sidebar_less';
	public $uses = array('Photo', 'PhotoCache');

	public function  beforeFilter() {
		parent::beforeFilter();
		$this->validate_superadmin();
	}
	
	public function admin_index() {
		$curr_sub_page = 'superadmin';
		$curr_page = 'site_settings';
		$this->set(compact('curr_page', 'curr_sub_page'));
	}
	
	public function admin_delete_all_photo_caches() {
		$this->PhotoCache->delete_all_photo_caches();
		$this->Session->setFlash(
			'All cache was deleted', 
			'admin/flashMessage/success'
		);
		$this->redirect('/admin/superadmins');
	}
	
	public function admin_delete_photo_caches_by_theme($theme_id) {
		$this->PhotoCache->delete_photo_caches_by_theme_id($theme_id);
		$this->Session->setFlash(
			"All cache for theme id($theme_id) was deleted", 
			'admin/flashMessage/success'
		);
		$this->redirect('/admin/superadmins');
	}
	
	public function admin_unlink_local_master_caches() {
		$this->PhotoCache->unlink_local_master_caches();
		$this->Session->setFlash(
			'Local Master Caches Deleted', 
			'admin/flashMessage/success'
		);
		$this->redirect('/admin/superadmins');
	}
	
}
