<?php
class AccountsController extends AppController {
    
   public $uses = array();
   
   public $components = array(
       'FotomatterBilling'
   );
    
   public function admin_index() {
       $line_items = $this->FotomatterBilling->get_info_account();
 
       $this->set(compact(array('line_items')));
       $this->layout = 'admin/accounts';
   }
    
    
}