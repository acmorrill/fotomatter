<?php
/**
 * Description of domains_controller
 *
 * @author aholsinger
 */
class DomainsController extends Appcontroller {

	public $uses = array();
	
	public $layout = 'admin/accounts';
	
	public function admin_index() {
		
	}
	
	public function search() {
		print_r(json_decode(file_get_contents("php://input"), true));
		print_r($this->params['form']);
		exit();
		
		
	}
}