<?php
class AuthnetOrder extends CakeAuthnetAppModel {

	var $name = 'AuthnetOrder';
        
        /**
         * Save should not be called directly, only createOrder
         * 
         * @order <array> Data used to create the order, Your order should look something like this
         * 
         * $order = array(
         *    'authnet_profile_id'=>'{id of the profile to charge this order to, for now this is the only method of payment}',
         *    'total'=>{'order_total'}
         *    'foreign_model'=>'{(optional) the model that this order should belong to, such as a users table}',
         *    'foreign_key'=>'{{required if foreign_model is specified}, id of the foreign_model}',
         *    'tax'=>array(
         *          'amount'=>'',
         *          'name'=>'',
         *          'description
         *      ),
         *      'shipping'=>array(
         *          'amount',
         *          'name',
         *          'description',
         *      ),
         *    'line_items'=>array(
         *          array(
         *              'unit_cost'=>'{item cost}',
         *              'name'=>'{name}',
         *              'description'=>'{descrtiption},
         *              'foreign_model'=>{the model that corresponds to the item in the db that they just bought},
         *              'foreign_key'=>{id of the item},
         *              'authnet_line_item_type_id'=>'specify the type of line item'
         *          )
         *     )
         * )
         */
        public function createOrderForProfile($order) {
            //make sure all required items are there
            if ($this->_validate_order($order) === false) {
                return false;
            }
            
            $this->AuthnetProfile = ClassRegistry::init("AuthnetProfile");
            $profile_to_use = $this->AuthnetProfile->find('first', array(
                'conditions'=>array(
                    'AuthnetProfile.id'=>$order['authnet_profile_id']
                ),
                'contain'=>false
            ));
            
            //build the order
            $api_order = array(); //data to be sent to authnet
            $order_save_db = array(); //data to be saved in order table
            
            $api_order['amount'] = $order['total'];
            $order_save_db['AuthnetOrder']['total'] = $order['total'];
            
            //tax?
            if (isset($order['tax'])) {
                $api_order['tax'] = $order['tax'];
                $order_save_db['AuthnetOrder']['tax'] = $order['tax']['amount'];
            }
            
            //shipping?
            if (isset($order['shipping'])) {
                $api_order['shipping'] = $order['shipping'];
                $order_save_db['AuthnetOrder']['shipping'] = $order['shipping']['amount'];
            }
            
            foreach ($order['line_items'] as $line_item) {
                
                $attach_to_order['itemId'] = $line_item['foreign_key'];
                $attach_to_order['name'] = $line_item['name'];
                $attach_to_order['description'] = $line_item['description'];
                $attach_to_order['quantity'] = $line_item['quantity'];
                $attach_to_order['unitPrice'] = $line_item['unit_cost'];
                
                $api_order['lineItems'][] = $attach_to_order;
            }
            
            $api_order['customerProfileId'] = $profile_to_use['AuthnetProfile']['customerProfileId'];
            $api_order['customerPaymentProfileId'] = $profile_to_use['AuthnetProfile']['customerPaymentProfileId'];
            $data_to_send['transaction']['profileTransAuthCapture'] = $api_order;
            
            
            $authnet = $this->get_authnet_instance();
            try {
                $authnet->createCustomerProfileTransactionRequest($data_to_send);
                if ($authnet->isError()) {
                    $returnArr['success'] = false;
                    $returnArr['code'] = $authnet->get_code();
                    $returnArr['message'] = $authnet->get_message();
                    $this->authnet_error("request failed", $authnet->get_response());
                    return $returnArr;
                }
                
                if (isset($order['foreign_model'])) {
                    $order_save_db['AuthnetOrder']['foreign_model'] = $order['foreign_model'];
                }
                
                if (isset($order['foreign_key'])) {
                    $order_save_db['AuthnetOrder']['foreign_key'] = $order['foreign_key'];
                }
                $order_save_db['AuthnetOrder']['authnet_profile_id'] = $order['authnet_profile_id'];
                
                $this->create();
                if ($this->save($order_save_db) == false) {
                    $this->authnet_error('Could not save order', $order);
                    return false;
                }
                $this->AuthnetLineItem = ClassRegistry::init("AuthnetLineItem");
                
                foreach ($order['line_items'] as $item) {
                    $item_save['AuthnetLineItem']['unit_cost'] = $item['unit_cost'];
                    $item_save['AuthnetLineItem']['name'] = $item['name'];
                    $item_save['AuthnetLineItem']['description'] = $item['description'];
                    $item_save['AuthnetLineItem']['quantity'] = $item['quantity'];
                    $item_save['AuthnetLineItem']['foreign_model'] = $item['foreign_model'];
                    $item_save['AuthnetLineItem']['foreign_key'] = $item['foreign_key'];
                    $item_save['AuthnetLineItem']['authnet_order_id'] = $this->id;
                    $item_save['AuthnetLineItem']['authnet_line_item_type_id'] = $item['authnet_line_item_type_id'];
                    $this->AuthnetLineItem->create();
                    if ($this->AuthnetLineItem->save($item_save) == false) {
                        $this->authnet_error('could not save line item');
                        return false;
                    }
                }
                
                return true;
            } catch (Exception $e) {
                $this->authnet_error('an exception has occurred', $e->getMessage());
                return false;
            }
        }
        
        /**
         * Make sure that we have good data passed to createOrder
         * @param type $order - data passed to createOrder
         * @return boolean
         */
        private function _validate_order($order) {if (empty($order['authnet_profile_id'])) {
                $this->authnet_error('Tried to create an order without specifing a profile to charge it', $order);
                return false;
            }
            
            if (empty($order['foreign_model']) == false && empty($order['foreign_key'])) {
                $this->authnet_error('Trying to create an order, specified foreign_model but did not specify key.', $order);
                return false;
            }
            
            if (empty($order['line_items']) || is_array($order['line_items']) == false) {
                $this->authnet_error('Trying to create an order, but did no specify line items', $order);
                return false;
            }
            
            $this->AuthnetLineItemType = ClassRegistry::Init("AuthnetLineItemType");
            $line_items = $this->AuthnetLineItemType->find('list', array(
               'fields'=>array(
                   'AuthnetLineItemType.id'
               ) 
            ));
            $check_cost = 0;
            if (isset($order['tax'])) {
                $check_cost += $order['tax']['amount'];
            }
            
            if (isset($order['shipping'])) {
                $check_cost += $order['shipping']['amount'];
            }
            
            foreach ($order['line_items'] as $line_item) {
                if (in_array($line_item['authnet_line_item_type_id'], $line_items) == false) {
                    $this->authnet_error('Could not find line item type for this item', $line_item);
                    return false;
                }
                
                if (empty($line_item['unit_cost'])) {
                    $this->authnet_error('No cost for this item', $line_item);
                    return false;
                }
                
                if (empty($line_item['name'])) {
                    $this->authnet_error('No name provided', $line_item);
                    return false;
                }
                
                if (empty($line_item['description'])) {
                    $this->authnet_error('no description provided', $line_item);
                    return false;
                }
                
                if (empty($line_item['quantity'])) {
                    $this->authnet_error('no quantity specified', $line_item);
                    return false;
                }
                $check_cost += $line_item['quantity'] * $line_item['unit_cost'];
                
                if (empty($line_item['foreign_model'])) {
                    $this->authnet_error('did not specify the model to attach to a line item', $line_item);
                    return false;
                }
                
                if (empty($line_item['foreign_key'])) {
                    $this->authnet_error('did not specify the id to attach to a line item', $line_item);
                    return false;
                }
            }
            
            if ($order['total'] != $check_cost) {
                $this->authnet_error('Tried to create order, calculated cost did not equal cost of line items. Total should be' . $check_cost, $order);
                return false;
            }
            
            $this->AuthnetProfile = ClassRegistry::init("AuthnetProfile");
            $profile_exists = $this->AuthnetProfile->find('count', array(
               'conditions'=>array(
                   'AuthnetProfile.id'=>$order['authnet_profile_id']
               ),
               'contain'=>false
            ));
            if($profile_exists == false) {
                $this->authnet_error('Could not find profile for payment.', $line_item);
                return false;
            }
            return true;    
        }
        
        
}