<?php
class AccountsController extends AppController {
    
   public $uses = array('GlobalCountry', 'GlobalCountryState');
   
   
   public $components = array(
       'FotomatterBilling',
       'Session'
   );
    
    /**
    * action for the page to add/remove line items. 
    * @author Adam Holsinger
    */
   public function admin_index() {
       $line_items = $this->FotomatterBilling->get_info_account();
       
       
       $this->log($line_items, 'client_billing');
      
       $this->Session->delete('account_line_items');
       $this->Session->write('account_line_items', array('checked'=>array(), 'unchecked'=>array()));
       
       $this->Session->delete('account_info');
       $this->Session->write('account_info', $line_items);
       
       $this->set(compact(array('line_items')));
       $this->layout = 'admin/accounts';
   }
   
   /**
    * Gets called when a line item is selected or deselected
    * @return Json indicating that we have recorded whether that item is checked on unchecked
    * @author Adam Holsinger
    */
   public function ajax_setItemChecked() {
      
       $line_items = $this->Session->read('account_line_items');
       
       $line_item_id = $this->params['form']['id'];
       if ($this->params['form']['checked']) {
           if (isset($line_items['unchecked'][$line_item_id])) {
               unset($line_items['unchecked'][$line_item_id]);
           }
           $line_items['checked'][$line_item_id] = 'checked';
       } else {
           if (isset($line_items['checked'][$line_item_id])) {
               unset($line_items['checked'][$line_item_id]);
           }
           $line_items['unchecked'][$line_item_id] = 'unchecked';
       }
       $this->Session->write('account_line_items', $line_items);
       print(json_encode(array('code'=>true)));
       exit();
   }
   
   /**
    * Return a html select options for the country id specified
    * @param type $country_id Will return states for this country id
    */
   public function admin_ajax_get_states_for_country($country_code) {
       $states = $this->GlobalCountryState->get_states_by_country_code($country_code);
       $result['html'] = $this->element('admin/accounts/state_list', array('states'=>$states));
       $this->return_json($result);
   }
   
   /**
    * Ajax function that gets called to save client billing and send it to overlord
    * @return This function will either return a error or call ajax_finishLineChange
    * @author Adam Holsinger
    */
   public function admin_ajax_save_client_billing() {
       if (empty($this->data) == false) {
           $profile_id = $this->FotomatterBilling->save_payment_profile($this->data);
           
           $account_info = $this->Session->read('account_info');
           $account_info['Account']['authnet_profile_id'] = $profile_id;
           $this->Session->write('account_info', $account_info);
           $this->ajax_finishLineChange();
           exit();
       }
       $this->major_error('admin_ajax_save_client_billing was called without data');
       exit();
   }
   
   /**
    * Figure out exactly what changed from what they have selected and display that to the user. They will be warned as far
    * as how their bill is changing. 
    * @return Function will get the html for the account_change_finish_element. 
    * @author Adam Holsinger
    */
   public function ajax_finishLineChange() {
       //If payment needed collect authnet
       
       $account_info = $this->Session->read('account_info');
       $account_changes = $this->Session->read('account_line_items');
       
       if ($account_info['Account']['authnet_profile_id'] == false) {
           $return = array();
           $countries = $this->GlobalCountry->get_available_countries();
           $return['html'] = $this->element("admin/accounts/add_profile", array('countries'=>$countries));
           print(json_encode($return));
           exit();
       }
       
       //rekey the original array
       $tmp_array = array();
       $current_bill = 0;
       foreach($account_info['items'] as $line_item) {
           if ($line_item['AccountLineItem']['active']) {
               $current_bill += $line_item['AccountLineItem']['current_cost'];
           }
           $tmp_array[$line_item['AccountLineItem']['id']] = $line_item;
       }
       $account_info = $tmp_array;
       
       //compare checked items
       foreach ($account_changes['checked'] as $id => $change) {
           if ($account_info[$id]['AccountLineItem']['active']) {
               unset($account_changes['checked'][$id]);
           }
       }
       
       //compare unchecked items
       foreach ($account_changes['unchecked'] as $id => $change) {
           if ($account_info[$id]['AccountLineItem']['active'] == false) {
               unset($account_changes['unchecked'][$id]);
           }
       }
       
       $return = array();
       $return['html'] = $this->element('admin/accounts/account_change_finish', array('current_bill'=>$current_bill,
                                                                                'account_changes'=>$account_changes,
                                                                                'account_info'=>$account_info));
       print(json_encode($return));
       exit();
       
       
   }
   
   
    
    
}