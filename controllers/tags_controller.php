<?php
class TagsController extends AppController {
    
	public $layout = 'admin/sidebar_less';
	
	public function admin_index() {
		$curr_page = 'photos';
		
		$this->set(compact('curr_page'));
	}
   
	
}