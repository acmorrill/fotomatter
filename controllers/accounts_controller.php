<?php
class AccountsController extends AppController {
    
   public $uses = array();
   
   public $components = array(
       'FotomatterBilling'
   );
    
   public function admin_index() {
       $line_items = $this->FotomatterBilling->remote_find(array(
           'model'=>'AccountLineItem',
           'type'=>'all',
           'params'=>array(
               
           )
       ));
      
       
       $this->set(compact(array('line_items')));
       $this->layout = 'admin/accounts';
   }
    
    
}