<?php
class PhotoCachesController extends AppController {
	public $name = 'PhotoCaches';
	public $uses = array('PhotoCache');
	
	public function create_cache($photocache_id) {
		
		$this->PhotoCache->finish_create_cache($photocache_id);
	}
	
}