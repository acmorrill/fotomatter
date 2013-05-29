<?php
class AccountsController extends AppController {
    
   public $uses = array('GlobalCountry', 'GlobalCountryState');
   
   
   public $components = array(
       'FotomatterBilling',
       'Session',
       'Validation'
   );
    
    /**
    * action for the page to add/remove line items. 
    * @author Adam Holsinger
    */
   
   
   public function admin_index() {
       $line_items = $this->FotomatterBilling->get_info_account();
       
      
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
       $result['html'] = $this->element('admin/accounts/state_list', array('country_code'=>$country_code));
       $this->return_json($result);
   }
   
   /**
    * Ajax function that gets called to save client billing and send it to overlord
    * @return This function will either return a error or call ajax_finishLineChange
    * @author Adam Holsinger
    */
   public function admin_ajax_save_client_billing() {
       if (empty($this->data) == false) {
           try {
               $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_firstname', __('You must provide your first name.', true));
               $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_lastname', __('You must provide your last name.', true));
               $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_address', __('You must provide your address.', true));
               $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_city', __('You must provide your city.', true));
               
               $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_zip', __('You must provide your zip code.', true));
               $this->Validation->validate('valid_cc_no_type', $this->data['AuthnetProfile']['payment_cardNumber'], 'billing_cardNumber', __('Your credit card was not entered or not in a valid format.', true));
               $this->data['AuthnetProfile']['str_date'] = '01/' . $this->data['AuthnetProfile']['expiration']['month'] . '/' . $this->data['AuthnetProfile']['expiration']['year'];
               $this->Validation->validate('date_is_future', $this->data['AuthnetProfile'], 'str_date', __('Your date provided was invalid or not in the future.', true)); //Ok in theory this should never be hit cause they are selects
               $this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'payment_cardCode', __('Your csv code was either blank or invalid.', true));
           
           } catch (Exception $e) {
               $this->Session->setFlash($e->getMessage());
               $return['html'] = $this->get_add_profile_form($this->data);
               print(json_encode($return));
               exit();
           }
           
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
   
   private function get_add_profile_form($current_data=array()) {
        $return = array();
        $countries = $this->GlobalCountry->get_available_countries();
        return $this->element("admin/accounts/add_profile", array('countries'=>$countries, 'current_data'=>$current_data));
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
           $return['html'] = $this->get_add_profile_form();
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
       
       $this->Session->delete('final_account_changes');
       $this->Session->write('final_account_changes', $account_changes);
              
       $return = array();
       $return['html'] = $this->element('admin/accounts/account_change_finish', array('current_bill'=>$current_bill,
                                                                                'account_changes'=>$account_changes,
                                                                                'account_info'=>$account_info));
       print(json_encode($return));
       exit();
   }
   
   /**
    * this function is called to send the final account change to overlord
    * @return <html> The html of the summary page
    */
   public function admin_ajax_finish_account_change() {
       $account_changes = array();
       if ($this->Session->check('final_account_changes')) {
           $account_changes = $this->Session->read('final_account_changes');
       } else {
           $return['code'] = false;
           $this->major_error('Expected account changes not set in session.');
           $this->return_json($return);
       }
       
       $account_info = array();
       if ($this->Session->check('account_info')){
           $account_info = $this->Session->read('account_info');
       } else {
           $return['code'] = false;
           $this->major_error('Expected to have account_line_items set in session');
           $this->return_json($return);
       }
       
       $change_to_send = array();
       foreach($account_changes['checked'] as $key => $item_to_add) {
           $change_to_send['add'][] = $account_info['items'][$key];
       }
       
       foreach ($account_changes['unchecked'] as $key => $item_to_remove) {
           $change_to_send['remove'][] = $account_info['items'][$key];
       }
     
       $result = $this->FotomatterBilling->makeAccountChanges($change_to_send);
       $return['code'] = true;
       $this->return_json($return);
   }
   
   
    
    
}