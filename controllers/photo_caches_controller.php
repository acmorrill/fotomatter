<?php
class PhotoCachesController extends AppController {
	public $name = 'PhotoCaches';
	public $uses = array('PhotoCache');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('create_cache'));
	}
	
	public function create_cache($photocache_id) {
		// TODO - maybe put this everywhere
		ignore_user_abort(true);
		set_time_limit(0);
		
		$this->PhotoCache->finish_create_cache($photocache_id);
		exit();
	}
	
}