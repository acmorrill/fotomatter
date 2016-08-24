<?php

require_once(ROOT . '/app/vendors/ShippingEstimator.php');

class Cart extends AppModel {

    public $useTable = false;

    public function update_cart_qty($updated_cart_items) {
        $this->Session = $this->get_session();

        if ($this->Session->check('Cart.items')) {
            $cart_items = $this->Session->read('Cart.items');
            foreach ($updated_cart_items as $updated_cart_item_key => $updated_cart_item_qty) {
                if ($updated_cart_item_qty == 0) {
                    unset($cart_items[$updated_cart_item_key]);
                } else {
                    $cart_items[$updated_cart_item_key]['qty'] = (int) $updated_cart_item_qty;
                }
            }
            $this->Session->write('Cart.items', $cart_items);
        }
    }

    public function remove_cart_item_by_index($i) {
        $this->Session = $this->get_session();

        if ($this->Session->check('Cart.items')) {
            $cart_items = $this->Session->read('Cart.items');
            $count = 0;
            foreach ($cart_items as $key => $cart_item) {
                if ($i == $count) {
                    unset($cart_items[$key]);
                    break;
                }
                $count++;
            }
            $this->Session->write('Cart.items', $cart_items);
        }
    }

    public function remove_cart_item_by_key($cart_key) {
        $this->Session = $this->get_session();

        if ($this->Session->check('Cart.items')) {
            $cart_items = $this->Session->read('Cart.items');
            unset($cart_items[$cart_key]);
            $this->Session->write('Cart.items', $cart_items);
        }
    }

    public function override_cart($cart_data) {
        $this->Session = $this->get_session();
        if (isset($cart_data['items'])) {
            $this->Session->write('Cart.items', $cart_data['items']);
        }
    }

    /**
     * 
     * @param type $photo_id
     * @param type $photo_print_type_id
     * @param type $short_side_inches
     * @param type $type - can be "autofixed", "autodynamic", or "self"
     * @param type $qty
     */
    public function add_to_cart($photo_id, $photo_print_type_id, $short_side_inches, $type, $qty = 1) {
        $this->Session = $this->get_session();


        $this->Photo = ClassRegistry::init('Photo');

        $extra_print_data = $this->Photo->get_extra_print_data($photo_id, $photo_print_type_id, $short_side_inches, $type);
        $long_side_inches = $extra_print_data['CurrentPrintData']['long_side_feet_inches'];
        $price = $extra_print_data['CurrentPrintData']['price'];
        $handling_price = $extra_print_data['CurrentPrintData']['handling_price'];
        $photo_print_type_name = $extra_print_data['PhotoPrintType']['print_name'];


        // get data for the new cart item
        $key = $this->get_cart_key($photo_id, $photo_print_type_id, $short_side_inches, $type);


        // update the cart
        $cart_items = array();
        if ($this->Session->check('Cart.items')) {
            $cart_items = $this->Session->read('Cart.items');
        }
        if (isset($cart_items[$key])) {
            $cart_items[$key]['qty'] += $qty;
        } else {
            $cart_items[$key]['qty'] = $qty;
        }
        $cart_items[$key]['photo_id'] = $photo_id;
        $cart_items[$key]['photo_print_type_id'] = $photo_print_type_id;
        $cart_items[$key]['print_type'] = $type;
        $cart_items[$key]['short_side_inches'] = $short_side_inches;
        $cart_items[$key]['long_side_inches'] = $long_side_inches;
        $cart_items[$key]['price'] = $price;
        $cart_items[$key]['handling_price'] = $handling_price;
        $cart_items[$key]['photo_print_type_name'] = $photo_print_type_name;
        $this->Session->write('Cart.items', $cart_items);
    }

    public function create_fake_cart_items() {
        $this->Session = $this->get_session();
        $this->Session->delete('Cart');
        $this->add_to_cart(21, 1, 11);
        $this->add_to_cart(21, 1, 11);
        $this->add_to_cart(23, 1, 11);
        $this->add_to_cart(24, 1, 11);
        $this->add_to_cart(27, 1, 11);
    }

    public function create_fake_cart_items_laptop() {
        $this->Session = $this->get_session();
        $this->Session->delete('Cart');
        $this->add_to_cart(32, 2, 2.5);
        $this->add_to_cart(32, 2, 2.5);
        $this->add_to_cart(29, 2, 2.5);
        $this->add_to_cart(25, 2, 2.5);
        $this->add_to_cart(27, 2, 2.5);
    }

    public function get_cart_key($photo_id, $photo_print_type_id, $short_side_inches, $type) {
        return 'photo_id:' . $photo_id . '|print_type_id:' . $photo_print_type_id . '|short_side_inches:' . $short_side_inches . "|print_type:$type";
    }

    public function get_cart_data() {
        $this->Session = $this->get_session();

        return $this->Session->read('Cart');
    }

    public function get_cart_items() {
        $cart_data = $this->get_cart_data();

        if (isset($cart_data['items'])) {
            return $cart_data['items'];
        } else {
            return array();
        }
    }

    public function get_cart_line_total($qty, $price) {
        return $qty * $price;
    }

    public function get_cart_total($cart_items = null) {
        if (!isset($cart_items)) {
            $cart_items = $this->get_cart_items();
        }

        $total = 0;
        $subtotal = $this->get_cart_subtotal($cart_items);
        $total += $subtotal;
        $total += $this->get_cart_shipping_total($cart_items);
        $total += $this->get_cart_tax($subtotal, $cart_items);

        return $total;
    }

    public function get_cart_subtotal($cart_items = null) {
        if (!isset($cart_items)) {
            $cart_items = $this->get_cart_items();
        }

        $total = 0;
        $total += $this->get_cart_items_total($cart_items);

        return $total;
    }

    public function get_cart_tax($subtotal = null, $cart_items = null) {
        if (!isset($cart_items)) {
            $cart_items = $this->get_cart_items();
        }

        $tax = 0;
        $this->SiteSetting = ClassRegistry::init('SiteSetting');
        $site_country_id = $this->SiteSetting->getVal('site_country_id', false);
        $site_state_id = $this->SiteSetting->getVal('site_state_id', false);
        $site_sales_tax_percentage = $this->SiteSetting->getVal('site_sales_tax_percentage', false);
        if (!empty($site_country_id) && !empty($site_state_id) && !empty($site_sales_tax_percentage)) {
            // grab shipping address data
            $address_data = $this->get_cart_shipping_address();

            if (isset($address_data['country_id']) && isset($address_data['state_id'])) {
                /////////////////////////////////////////////////////////////////////////////////
                //	if in USA -- DREW TODO - maybe upgrade later for other countries
                //	AND site country is same as shipping country
                //	AND site state is same as shipping state
                //	AND site_sales_tax_percentage is between 0 and 1
                //	THEN charge tax based on the subtotal
                //---------------------------------------------------------
                if ($site_country_id == 223 && $site_country_id == $address_data['country_id'] && $site_state_id == $address_data['state_id'] && $site_sales_tax_percentage > 0 && $site_sales_tax_percentage < 1) {
                    if (empty($subtotal)) {
                        $subtotal = $this->get_cart_subtotal();
                    }
                    $tax = round($subtotal * $site_sales_tax_percentage, 2);
                }
            }
        }

        return $tax;
    }

    public function get_cart_shipping_total($cart_items = null) {
        if (!isset($cart_items)) {
            $cart_items = $this->get_cart_items();
        }

        $shipping_total = 0;
        foreach ($cart_items as $cart_item) {
            $shipping_total += $cart_item['qty'] * $cart_item['handling_price'];
        }

        return $shipping_total;
    }

    public function get_cart_items_total($cart_items = null) {
        if (!isset($cart_items)) {
            $cart_items = $this->get_cart_items();
        }

        $items_total = 0;
        foreach ($cart_items as $cart_item) {
            $items_total += $cart_item['qty'] * $cart_item['price'];
        }

        return $items_total;
    }

    public function count_items_in_cart($cart_items = null) {
        if (!isset($cart_items)) {
            $cart_items = $this->get_cart_items();
        }

        $items_total = 0;
        foreach ($cart_items as $cart_item) {
            $items_total += (int) $cart_item['qty'];
        }

        return $items_total;
    }

    public function get_cart_credit_card_data() {
        $this->Session = $this->get_session();

        if ($this->Session->check('Cart.payment_info')) {
            return $this->Session->read('Cart.payment_info');
        }

        return false;
    }

    public function set_cart_credit_card_data($payment_data) {
        $this->Session = $this->get_session();

        $this->Session->write('Cart.payment_info', $payment_data);
//		$payment_data['credit_card_method']
//		$payment_data['last_four']
//		$payment_data['expiration_month']
//		$payment_data['expiration_year']
    }

    public function set_cart_shipping_address_data($shipping_data) {
        $this->Session = $this->get_session();

        $this->Session->write('Cart.shipping_address', $shipping_data);
    }

    public function set_cart_billing_address_data($billing_data) {
        $this->Session = $this->get_session();

        $this->Session->write('Cart.billing_address', $billing_data);
    }

    public function has_cart_address_data() {

        $billing_data = null;
        $shipping_data = null;
        if ($this->Session->check('Cart.billing_address')) {
            $billing_data = $this->Session->read('Cart.billing_address');
        }
        if ($this->Session->check('Cart.shipping_address')) {
            $shipping_data = $this->Session->read('Cart.shipping_address');
        }

        // DREW TODO - maybe we should validate the cart address data here

        if (!empty($billing_data) && !empty($shipping_data)) {
            return true;
        }

        return false;
    }

    public function has_cart_shipping_address_data() {
        $shipping_data = null;
        if ($this->Session->check('Cart.shipping_address')) {
            $shipping_data = $this->Session->read('Cart.shipping_address');
        }

        // DREW TODO - maybe we should validate the cart address data here

        if (!empty($shipping_data)) {
            return true;
        }

        return false;
    }

    public function has_cart_billing_address_data() {
        $billing_data = null;
        if ($this->Session->check('Cart.billing_address')) {
            $billing_data = $this->Session->read('Cart.billing_address');
        }

        // DREW TODO - maybe we should validate the cart address data here

        if (!empty($billing_data)) {
            return true;
        }

        return false;
    }

    public function prepopulate_cart_by_user($user) {
        if (isset($user['User']['id'])) {
            $this->AuthnetProfile = ClassRegistry::init('AuthnetProfile');
            $authnet_profile = $this->AuthnetProfile->find('first', array(
                'conditions' => array(
                    'AuthnetProfile.user_id' => $user['User']['id'],
                ),
                'contain' => false,
            ));

            if (!empty($authnet_profile)) {
                $this->GlobalCountry = ClassRegistry::init('GlobalCountry');
                $this->GlobalCountryState = ClassRegistry::init('GlobalCountryState');

                $billing_address_data = array();
                $billing_address_data['firstname'] = $authnet_profile['AuthnetProfile']['billing_firstname'];
                $billing_address_data['lastname'] = $authnet_profile['AuthnetProfile']['billing_lastname'];
                $billing_address_data['address1'] = $authnet_profile['AuthnetProfile']['billing_address'];
                $billing_address_data['address2'] = ''; // DREW TODO - maybe change authnet profile to use address1 and address2
                $billing_address_data['city'] = $authnet_profile['AuthnetProfile']['billing_city'];
                $billing_address_data['zip'] = $authnet_profile['AuthnetProfile']['billing_zip'];
                $billing_address_data['country_id'] = $this->GlobalCountry->get_country_id_by_name($authnet_profile['AuthnetProfile']['billing_country']);
                $billing_address_data['state_id'] = $this->GlobalCountryState->get_state_id_by_name($authnet_profile['AuthnetProfile']['billing_state']);
                $billing_address_data['phone'] = isset($authnet_profile['AuthnetProfile']['billing_phoneNumber']) ? $authnet_profile['AuthnetProfile']['billing_phoneNumber'] : '';


                $shipping_address_data = array();
                $shipping_address_data['firstname'] = $authnet_profile['AuthnetProfile']['shipping_firstname'];
                $shipping_address_data['lastname'] = $authnet_profile['AuthnetProfile']['shipping_lastname'];
                $shipping_address_data['address1'] = $authnet_profile['AuthnetProfile']['shipping_address'];
                $shipping_address_data['address2'] = ''; // DREW TODO - maybe change authnet profile to use address1 and address2
                $shipping_address_data['city'] = $authnet_profile['AuthnetProfile']['shipping_city'];
                $shipping_address_data['zip'] = $authnet_profile['AuthnetProfile']['shipping_zip'];
                $shipping_address_data['country_id'] = $this->GlobalCountry->get_country_id_by_name($authnet_profile['AuthnetProfile']['shipping_country']);
                $shipping_address_data['state_id'] = $this->GlobalCountryState->get_state_id_by_name($authnet_profile['AuthnetProfile']['shipping_state']);
                $shipping_address_data['phone'] = isset($authnet_profile['AuthnetProfile']['shipping_phoneNumber']) ? $authnet_profile['AuthnetProfile']['shipping_phoneNumber'] : '';

                $this->set_cart_billing_address_data($billing_address_data);
                $this->set_cart_shipping_address_data($shipping_address_data);


                // save cart cc info
                $cc_data = array();
                $cc_data['credit_card_method'] = $authnet_profile['AuthnetProfile']['payment_method'];
                $cc_data['last_four'] = $authnet_profile['AuthnetProfile']['payment_cc_last_four'];
                $expiration_timestamp = strtotime($authnet_profile['AuthnetProfile']['payment_expirationDate']);
                $cc_data['expiration_month'] = date('n', $expiration_timestamp);
                $cc_data['expiration_year'] = date('Y', $expiration_timestamp);

                $this->set_cart_credit_card_data($cc_data);
            }
        }
    }

    public function get_cart_billing_address() {
        $this->Session = $this->get_session();

        if ($this->Session->check('Cart.billing_address')) {
            $billing_address = $this->Session->read('Cart.billing_address');

            $billing_address['country_name'] = '';
            if (!empty($billing_address['country_id'])) {
                $this->GlobalCountry = ClassRegistry::init('GlobalCountry');
                $country = $this->GlobalCountry->find('first', array(
                    'conditions' => array(
                        'GlobalCountry.id' => $billing_address['country_id'],
                    ),
                    'contain' => false,
                ));
                $billing_address['country_name'] = $country['GlobalCountry']['country_name'];
            }

            $billing_address['state_name'] = '';
            if (!empty($billing_address['state_id'])) {
                $this->GlobalCountryState = ClassRegistry::init('GlobalCountryState');
                $state = $this->GlobalCountryState->find('first', array(
                    'conditions' => array(
                        'GlobalCountryState.id' => $billing_address['state_id'],
                    ),
                    'contain' => false,
                ));
                $billing_address['state_name'] = $state['GlobalCountryState']['state_name'];
            }

            return $billing_address;
        } else {
            return array();
        }
    }

    public function get_cart_shipping_address() {
        $this->Session = $this->get_session();

        if ($this->Session->check('Cart.shipping_address')) {
            $shipping_address = $this->Session->read('Cart.shipping_address');

            $shipping_address['country_name'] = '';
            if (!empty($shipping_address['country_id'])) {
                $this->GlobalCountry = ClassRegistry::init('GlobalCountry');
                $country = $this->GlobalCountry->find('first', array(
                    'conditions' => array(
                        'GlobalCountry.id' => $shipping_address['country_id'],
                    ),
                    'contain' => false,
                ));
                $shipping_address['country_name'] = $country['GlobalCountry']['country_name'];
            }

            $shipping_address['state_name'] = '';
            if (!empty($shipping_address['state_id'])) {
                $this->GlobalCountryState = ClassRegistry::init('GlobalCountryState');
                $state = $this->GlobalCountryState->find('first', array(
                    'conditions' => array(
                        'GlobalCountryState.id' => $shipping_address['state_id'],
                    ),
                    'contain' => false,
                ));
                $shipping_address['state_name'] = $state['GlobalCountryState']['state_name'];
            }


            return $shipping_address;
        } else {
            return array();
        }
    }

    public function cart_empty() {
        $cart_items = $this->get_cart_items();

        if (empty($cart_items)) {
            return true;
        } else {
            return false;
        }
    }

    public function destroy_cart() {
        $this->Session = $this->get_session();

        $this->Session->delete('Cart');
    }

    public function get_cart_shipping_estimate() {
        $cart_data = $this->get_cart_data();
        
        $shipping_estimator = new \Ups\ShippingEstimator();
        
        
        // validate an address
//        $address_validation = $shipping_estimator->check_address();
//        $this->log($address_validation, "address_validation");
        
        
        // get shipping rates
        $shipping_estimate = $shipping_estimator->get_shipping_price();
        $this->log($shipping_estimate, 'shipping_estimate');
        
        

        print_r($cart_data);
    }

}
