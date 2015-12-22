<?php
class EcommercesController extends AppController {
	public $name = 'Ecommerces';
	public $uses = array('PhotoAvailSize', 'PhotoFormat', 'PhotoPrintType', 'PhotoAvailSizesPhotoPrintType', 'Cart', 'Photo', 'User', 'cake_authnet.AuthnetProfile', 'cake_authnet.AuthnetOrder', 'GlobalCountryState', 'GlobalCountry');
	public $layout = 'admin/ecommerces';
	public $paginate = array(
		'conditions' => array(
			'OR' => array(
				'AuthnetOrder.one_time_charge' => 0,
				'AND' => array(
					'AuthnetOrder.one_time_charge' => 1,
					'AuthnetOrder.one_time_response_code' => 1,
				)
			),
		),
		'limit' => 10,
		'order' => array(            
			'AuthnetOrder.created' => 'desc',
		),
	);

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('view_cart', 'add_to_cart', 'checkout_login_or_guest', 'checkout_get_address', 'get_available_states_for_country_options', 'checkout_finalize_payment', 'change_fe_password', 'checkout_thankyou', 'check_frontend_cart', 'remove_from_cart', 'remove_cart_item_by_index', 'remove_cart_item_by_key', 'update_cart_qty'));

		
		/////////////////////////////////////////////
		// limit ecommerce
		if (in_array($this->action, array(
			'admin_manage_print_sizes',
			'admin_manage_print_types_and_pricing',
			'admin_order_management',
			'admin_get_paid',
			'admin_index',
		))) {
			$this->FeatureLimiter->limit_view($this, 'basic_shopping_cart', 'ecommerce'); // $controller, $feature_ref_name, $element_path in /elements/admin/limit_views
		} else {
			$this->FeatureLimiter->limit_function($this, 'basic_shopping_cart'); // $controller, $feature_ref_name
		}
		
		
//		$this->front_end_auth = array('checkout_get_address');
	}

	public function check_frontend_cart() {
		$this->return_json($this->Cart->count_items_in_cart());
	}
	
	public function admin_index() {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		if (!empty($this->data)) {
			try {
				if (!empty($this->data['site_country_id'])) {
					$this->SiteSetting->setVal('site_country_id', $this->data['site_country_id']);
				} else {
					$this->SiteSetting->clearVal('site_country_id');
				}
				if (!empty($this->data['site_state_id'])) {
					$this->SiteSetting->setVal('site_state_id', $this->data['site_state_id']);
				} else {
					$this->SiteSetting->clearVal('site_state_id');
				}
				if (!empty($this->data['site_sales_tax_percentage'])) {
					$this->Validation->validate('is_decimal_percent', $this->data, 'site_sales_tax_percentage', 'The sales tax must be a decimal value between 0 and 1. For example - 6% would be .06');
					
					$this->SiteSetting->setVal('site_sales_tax_percentage', $this->data['site_sales_tax_percentage']);
				} else {
					$this->SiteSetting->clearVal('site_sales_tax_percentage');
				}
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage(), 'admin/flashMessage/error');
				return;
			}
		}
		
		
		$this->data['site_country_id'] = $this->SiteSetting->getVal('site_country_id', false);
		$this->data['site_state_id'] = $this->SiteSetting->getVal('site_state_id', false);
		$this->data['site_sales_tax_percentage'] = $this->SiteSetting->getVal('site_sales_tax_percentage', false);
	}
	
	
	public function admin_payout_orders() {
		$this->PaypalReimbursementLog = ClassRegistry::init('PaypalReimbursementLog');
		
		/////////////////////////////////////////////////
		// do this whole function while locked
		//---------------------------------------------------------------------
		$lock_name = 'paying_out_orders';
		$lock = $this->PaypalReimbursementLog->get_lock($lock_name, 8);
			if ($lock === false) {
				// DREW TODO - fail here
				$this->redirect('/admin/ecommerces/get_paid/');
			}
		
		
			///////////////////////////////////////////////////////////////
			// do the locked code
			$payable_order_ids = $this->data['payout_order_ids'];


			// make sure all the payable order ids are still payable
			if ($this->AuthnetOrder->are_orders_payable($payable_order_ids) === false) {
				$this->PaypalReimbursementLog->release_lock($lock_name);
				$this->redirect('/admin/ecommerces/get_paid/');
			} 


			
			///////////////////////////////////////////////////////////////////
			// actually send out the payment for the orders via paypal 
			// (if this fails need to set everything back the way it was)
			$logged_in_user = $this->Auth->user();
			if (empty($logged_in_user['User']['email_address'])) {
				$this->major_error('2 No email address to get paid via paypal with!', compact('logged_in_user', 'payable_order_ids'), 'high');
				$this->Session->setFlash(__('Cannot get paid on orders. Please contact support.', true), 'admin/flashMessage/error');
				$this->PaypalReimbursementLog->release_lock($lock_name);
				$this->redirect('/admin/ecommerces/get_paid/');
			}
			$user_email_address = $logged_in_user['User']['email_address'];
			$order_total_data = $this->AuthnetOrder->get_order_totals($payable_order_ids);
			// mark all the payable orders as being in the process of paying
			$this->AuthnetOrder->set_orders_pay_out_status($payable_order_ids, 'processing');
//			print_r($order_total_data);
//			print_r($logged_in_user);
//			print_r($payable_order_ids);
			$send_payment_result = $this->AuthnetOrder->send_photographer_payment_via_paypal($order_total_data['total'], $logged_in_user, $payable_order_ids);
//			$this->log($send_payment_result, 'send_payment_result'); // START HERE TOMORROW TO TEST PAYPAY GET PAID
			if ($send_payment_result === false) {
				$this->AuthnetOrder->set_orders_pay_out_status($payable_order_ids, 'not_paid'); // DREW TODO - maybe mark as error?
				$this->major_error('Failed to reimmburse for orders', compact('logged_in_user', 'payable_order_ids', 'amount'), 'high');
				$this->Session->setFlash(__('Cannot get paid on orders. Please contact support.', true), 'admin/flashMessage/error');
				$this->PaypalReimbursementLog->release_lock($lock_name);
				$this->redirect('/admin/ecommerces/get_paid/');
			}
			
			
			// payment worked so now mark orders as paid
			$this->AuthnetOrder->set_orders_pay_out_status($payable_order_ids, 'paid');
		$this->PaypalReimbursementLog->release_lock($lock_name);
		//---------------------------------------------------------------------
		
		
		$this->Session->setFlash(sprintf(__("A payment of $%s was sent to %s via Paypal.", true), $order_total_data['total'], $user_email_address), 'admin/flashMessage/success');
		$this->redirect('/admin/ecommerces/order_management/');
	}
	
	public function admin_get_paid() {
		// DREW TODO remove this - just for testing
//		$this->AuthnetOrder->set_orders_pay_out_status(array('48','49','50','51','52'), 'not_paid');
		
		// get all the payableorders
		$payable_orders = $this->AuthnetOrder->get_payable_orders();
		
		
		// find users email address that will get paypal email
		$logged_in_user = $this->Auth->user();
		$payable_paypal_email_address = '';
		if (empty($logged_in_user['User']['email_address'])) {
			$this->major_error('No email address to get paid via paypal with!', compact('logged_in_user', 'payable_orders'), 'high');
			$this->Session->setFlash(__('Cannot get paid on orders. Please contact support.', true), 'admin/flashMessage/error');
		} else {
			$payable_paypal_email_address = $logged_in_user['User']['email_address'];
		}
		
		
		$order_ids = Set::extract($payable_orders, '{n}.AuthnetOrder.id');
		
		
		/////////////////////////////////////////////////////////////////////////
		// get the payable amount minus the 3%
		$order_total_data = $this->AuthnetOrder->get_order_totals($order_ids);
		
		
		$this->set(compact('payable_orders', 'payable_paypal_email_address', 'order_total_data'));
	}
	
	public function admin_reset_print_sizes() {
		$this->HashUtil->set_new_hash('ecommerce');
		
		$this->PhotoAvailSize->restore_avail_photo_size_defaults();
		
		$this->Session->setFlash(__('Available print sizes reset.', true), 'admin/flashMessage/success');
		
		$this->redirect('/admin/ecommerces/manage_print_sizes');
	}
	 
	public function admin_delete_print_size($photo_avail_size_id) {
		$this->HashUtil->set_new_hash('ecommerce');
		
		if (!$this->PhotoAvailSize->delete($photo_avail_size_id)) {
			$this->Session->setFlash(__('Failed to delete available photo size.', true), 'admin/flashMessage/error');
			$this->major_error('Failed to delete available photo size.', array($photo_avail_size_id));
		}
		
		$this->redirect('/admin/ecommerces/manage_print_sizes');
	}
	
	public function admin_add_print_size($photo_avail_size_id = '') {
		$this->HashUtil->set_new_hash('ecommerce');
		
		if (!empty($this->data)) {
			if ( !isset($this->data['PhotoAvailSize']['photo_format_ids']) ) {
				$this->Session->setFlash(__('Please choose photo orientations to apply the print size to.', true), 'admin/flashMessage/error');
			} else if ( !isset($this->data['PhotoAvailSize']['short_side_length']) ) {
				$this->Session->setFlash(__('Please choose a short side length.', true), 'admin/flashMessage/error');
			} else {
				$this->data['PhotoAvailSize']['photo_format_ids'] = implode(',', $this->data['PhotoAvailSize']['photo_format_ids']);

				$this->PhotoAvailSize->create();
				if (!$this->PhotoAvailSize->save($this->data)) {
					$this->Session->setFlash(__('Failed to add available photo size.', true), 'admin/flashMessage/success');
					$this->major_error('Failed to save available photo size.', array($this->data));
				} else {
					$this->redirect('/admin/ecommerces/manage_print_sizes');
				}
			}
		}
		
		if (isset($photo_avail_size_id)) {
			$this->data = $this->PhotoAvailSize->find('first', array(
				'conditions' => array(
					'PhotoAvailSize.id' => $photo_avail_size_id
				),
				'contain' => false
			));
		}

		
		$used_short_side_dimensions = $this->PhotoAvailSize->get_used_short_side_values();
		if (isset($this->data['PhotoAvailSize']['short_side_length'])) {
			unset($used_short_side_dimensions[$this->data['PhotoAvailSize']['short_side_length']]);
		}

		$short_side_values = $this->PhotoAvailSize->valid_short_side_values();

		$this->set(compact('short_side_values', 'used_short_side_dimensions', 'photo_avail_size_id'));
	}
	 
	public function admin_manage_print_sizes() {
		$this->HashUtil->set_new_hash('ecommerce');
		
		$photo_avail_sizes = $this->PhotoAvailSize->find('all', array(
		'contain' => false,
		'order' => array(
			'PhotoAvailSize.short_side_length ASC'
		)
		));

		$photo_formats = $this->PhotoFormat->find('all', array(
			'contain' => false
		));
		$photo_formats = Set::combine($photo_formats, '{n}.PhotoFormat.id', '{n}');

		foreach ($photo_avail_sizes as &$photo_avail_size) {
			$format_ids = explode(',', $photo_avail_size['PhotoAvailSize']['photo_format_ids']);

			foreach ($format_ids as $format_id) {
				$photo_avail_size['PhotoFormat'][] = $photo_formats[$format_id]['PhotoFormat'];
			}
		}


		$this->set(compact('photo_avail_sizes', 'photo_formats'));
	}

	public function admin_manage_print_types_and_pricing() {
		$this->HashUtil->set_new_hash('ecommerce');
		
		$photo_print_types = $this->PhotoPrintType->find('all', array(
			'order' => array(
				'PhotoPrintType.order ASC'
			),
			'contain' => false
		));
		
		$this->set(compact('photo_print_types'));
	}
	
	public function admin_order_management() {
		$this->HashUtil->set_new_hash('ecommerce');
		
		$authnet_orders = $this->paginate('AuthnetOrder');    
		
		$this->set(compact('authnet_orders'));
	}
	
	public function admin_approve_order($authnet_order_id) {
		$finalize_order_result = $this->AuthnetOrder->approve_order($authnet_order_id);
			
		
		$return_arr = array();
		if ($finalize_order_result === false) {
			$return_arr['success'] = true;
			
			$this->Session->setFlash(__('Failed to approve order. Please contact support.', true), 'admin/flashMessage/error');
		}
		
		$this->redirect('/admin/ecommerces/fulfill_order/'.$authnet_order_id.'/');
	}
	
	public function admin_fulfill_order($authnet_order_id) {
		$this->HashUtil->set_new_hash('ecommerce');
		
		$authnet_order = $this->AuthnetOrder->find('first', array(
			'conditions' => array(
				'AuthnetOrder.id' => $authnet_order_id,
			),
			'contain' => array(
				'AuthnetProfile',
				'AuthnetLineItem',
			),
		));
		
		foreach ($authnet_order['AuthnetLineItem'] as &$line_item) {
			$extra_data = explode("|", $line_item['name']);
			$line_item['photo_id'] = $extra_data[0];
			$line_item['print_type_id'] = $extra_data[1];
			$line_item['short_side_inches'] = $extra_data[2];
			$line_item['extra_data'] = $this->Photo->get_extra_print_data($line_item['photo_id'], $line_item['print_type_id'], $line_item['short_side_inches']);
		}
		
		$transaction_id = $authnet_order['AuthnetOrder']['transaction_id'];
		$is_voidable = $this->AuthnetOrder->transaction_voidable($authnet_order_id);
		$is_voided = $this->AuthnetOrder->transaction_voided($authnet_order_id);
		$is_refundable = $this->AuthnetOrder->transaction_refundable($authnet_order_id);
		$is_refunded = $this->AuthnetOrder->transaction_refunded($authnet_order_id);
		$order_status = $this->AuthnetOrder->order_status($authnet_order_id);
		
		$this->set(compact('authnet_order_id', 'authnet_order', 'is_voidable', 'is_voided', 'is_refundable', 'is_refunded', 'order_status'));
	}
	
	public function admin_void_order($authnet_order_id) {
		if (!$this->AuthnetOrder->transaction_voidable($authnet_order_id)) {
			$this->Session->setFlash(__('Failed to void order. Please contact support.', true), 'admin/flashMessage/error');
			$this->AuthnetOrder->major_error('Tried to void an unvoidable order.', compact('authnet_order_id'));
			$this->redirect('/admin/ecommerces/fulfill_order/'.$authnet_order_id);
			return false;
		}
		
		
		$void_result = $this->AuthnetOrder->void_transaction($authnet_order_id);
		
		
		if ($void_result === false) {
			$this->Session->setFlash(__('Failed to void order. Please contact support.', true), 'admin/flashMessage/error');
		}
		
		$this->redirect('/admin/ecommerces/fulfill_order/'.$authnet_order_id);
	}
	
	public function admin_refund_order($authnet_order_id) {
		if (!$this->AuthnetOrder->transaction_refundable($authnet_order_id)) {
			$this->Session->setFlash(__('Failed to refund order. Please contact support.', true), 'admin/flashMessage/error');
			$this->AuthnetOrder->major_error('Tried to refund an unrefundable order.', compact('authnet_order_id'), 'high');
			$this->redirect('/admin/ecommerces/fulfill_order/'.$authnet_order_id);
			return false;
		}
		
		$refund_result = $this->AuthnetOrder->refund_transaction($authnet_order_id);
		
		if ($refund_result === false) {
			$this->Session->setFlash(__('Failed to refund order. Please contact support.', true), 'admin/flashMessage/error');
		}
		
		$this->redirect('/admin/ecommerces/fulfill_order/'.$authnet_order_id);
	}
	
	public function admin_ajax_set_print_type_order($photo_print_type_id, $new_order) {
		$this->HashUtil->set_new_hash('ecommerce');
		
		$returnArr = array();
		
		if ($this->PhotoPrintType->moveto($photo_print_type_id, $new_order)) {
			$returnArr['code'] = 1;
			$returnArr['message'] = 'photo print type order changed successfully';
		} else {
			$returnArr['code'] = -1;
			$returnArr['message'] = $this->PhotoPrintType->major_error('failed to change photo print type order', compact('photo_print_type_id', 'new_order'));
		}
		
		$this->return_json($returnArr);
	}
	
	public function admin_delete_print_type($photo_print_type_id) {
		
		$this->HashUtil->set_new_hash('ecommerce');
		
		if (!$this->PhotoPrintType->delete($photo_print_type_id)) {
			$this->Session->setFlash(__('Failed to delete photo print type.', true), 'admin/flashMessage/error');
			$this->major_error('Failed to delete photo print type.', compact('photo_print_type_id'));
		}
		
		$this->redirect('/admin/ecommerces/manage_print_types_and_pricing');
	}
		
	
	public function admin_add_print_type_and_pricing($photo_print_type_id = 0) {
		$this->HashUtil->set_new_hash('ecommerce');
		
		if (!empty($this->data)) { 
			///////////////////////////////////////////////
			// do validation on the data
			$passed_validation = true;
			$print_type_id = !empty($this->data['PhotoPrintType']['id']) ? $this->data['PhotoPrintType']['id'] : null ;
			$print_name = !empty($this->data['PhotoPrintType']['print_name']) ? $this->data['PhotoPrintType']['print_name'] : null ;
			$turnaround_time = !empty($this->data['PhotoPrintType']['turnaround_time']) ? $this->data['PhotoPrintType']['turnaround_time'] : '' ;
			
			if ($passed_validation && !isset($print_name)) {
				$this->Session->setFlash(__("Print name must be set.", true), 'admin/flashMessage/error');
				$passed_validation = false;
			}
			

			if ($passed_validation) {
				// create the new photo type
				$new_photo_type = array();
				$new_photo_type['PhotoPrintType']['id'] = $print_type_id;
				$new_photo_type['PhotoPrintType']['print_name'] = $print_name;
				$new_photo_type['PhotoPrintType']['turnaround_time'] = $turnaround_time;
				$this->PhotoPrintType->create();
				if (!$this->PhotoPrintType->save($new_photo_type)) {
					$this->Session->setFlash(__("Failed to save photo print type.", true), 'admin/flashMessage/error');
					$this->PhotoPrintType->major_error('Failed to save photo print type', compact('new_photo_type'));
				} else {
					// add into the PhotoPrintSizesPhotoPrintType join table
					$save_error = false; 
					if (!empty($this->data['PhotoAvailSizesPhotoPrintType'])) {
						foreach ($this->data['PhotoAvailSizesPhotoPrintType'] as $count => $curr_join_data) {
//							$this->log($curr_join_data, 'curr_join_data');
							
							if (!isset($curr_join_data['photo_avail_size_id'])) { // means we need to remove the join table entry instead
								$this->PhotoAvailSizesPhotoPrintType->deleteAll(array(
									'PhotoAvailSizesPhotoPrintType.id' => $curr_join_data['id']
								), true, true);
							} else { // save the join table entry
								$new_join_table_data = array();
								if (empty($curr_join_data['id'])) {
									$curr_join_data['id'] = null;
								}
								$new_join_table_data['id'] = $curr_join_data['id'];
								$new_join_table_data['photo_avail_size_id'] = $curr_join_data['photo_avail_size_id'];
								$new_join_table_data['photo_print_type_id'] = $this->PhotoPrintType->id;
								$new_join_table_data['non_pano_available'] = isset($curr_join_data['non_pano_available']) ? 1 : 0;
								if ($new_join_table_data['non_pano_available'] === 1) {
									$new_join_table_data['non_pano_price'] = !empty($curr_join_data['non_pano_price']) ? $curr_join_data['non_pano_price'] : '0.00';
									$new_join_table_data['non_pano_shipping_price'] = !empty($curr_join_data['non_pano_shipping_price']) ? $curr_join_data['non_pano_shipping_price'] : '0.00';
									$new_join_table_data['non_pano_custom_turnaround'] = !empty($curr_join_data['non_pano_custom_turnaround']) ? $curr_join_data['non_pano_custom_turnaround'] : '';
									$new_join_table_data['non_pano_global_default'] = isset($curr_join_data['non_pano_global_default']) ? 1 : 0;
									$new_join_table_data['non_pano_force_settings'] = isset($curr_join_data['non_pano_force_settings']) ? 1 : 0;
								} else {
									$new_join_table_data['non_pano_price'] = '0.00';
									$new_join_table_data['non_pano_shipping_price'] = '0.00';
									$new_join_table_data['non_pano_custom_turnaround'] = '';
									$new_join_table_data['non_pano_global_default'] = 0;
									$new_join_table_data['non_pano_force_settings'] = 0;
								}
								$new_join_table_data['pano_available'] = isset($curr_join_data['pano_available']) ? 1 : 0;
								if ($new_join_table_data['pano_available'] === 1) {
									$new_join_table_data['pano_price'] = !empty($curr_join_data['pano_price']) ? $curr_join_data['pano_price'] : '0.00';
									$new_join_table_data['pano_shipping_price'] = !empty($curr_join_data['pano_shipping_price']) ? $curr_join_data['pano_shipping_price'] : '0.00';
									$new_join_table_data['pano_custom_turnaround'] = !empty($curr_join_data['pano_custom_turnaround']) ? $curr_join_data['pano_custom_turnaround'] : '';
									$new_join_table_data['pano_global_default'] = isset($curr_join_data['pano_global_default']) ? 1 : 0;
									$new_join_table_data['pano_force_settings'] = isset($curr_join_data['pano_force_settings']) ? 1 : 0;
								} else {
									$new_join_table_data['pano_price'] = '0.00';
									$new_join_table_data['pano_shipping_price'] = '0.00';
									$new_join_table_data['pano_custom_turnaround'] = '';
									$new_join_table_data['pano_global_default'] = 0;
									$new_join_table_data['pano_force_settings'] = 0;
								}


								$this->PhotoAvailSizesPhotoPrintType->create();
								if (!$this->PhotoAvailSizesPhotoPrintType->save($new_join_table_data)) {
									$this->Session->setFlash(__("Failed to connect photo print type to photo print size.", true), 'admin/flashMessage/error');
									$this->PhotoPrintType->major_error('Failed to connect photo print type to photo print size', compact('new_join_table_data'));
									$save_error = true;
									break;
								} 
							}
						}
					}
					if ($save_error === false) {
						$this->redirect('/admin/ecommerces/manage_print_types_and_pricing/');
					}
				}
			}
			
			
			
		}
		
		
		$photo_avail_sizes_query = "
			SELECT * FROM photo_avail_sizes AS PhotoAvailSize
				LEFT JOIN photo_avail_sizes_photo_print_types AS PhotoAvailSizesPhotoPrintType
					ON (PhotoAvailSizesPhotoPrintType.photo_avail_size_id = PhotoAvailSize.id AND PhotoAvailSizesPhotoPrintType.photo_print_type_id = :photo_print_type_id )
			ORDER BY PhotoAvailSize.short_side_length ASC
		";
		
		
		$photo_avail_sizes = $this->PhotoAvailSize->query($photo_avail_sizes_query, array(
			'photo_print_type_id' => $photo_print_type_id
		));
		
		$photo_print_type = $this->PhotoPrintType->find('first', array(
			'conditions' => array(
				'PhotoPrintType.id' => $photo_print_type_id
			),
			'contain' => false
		));
		
		
		
		$this->set(compact('photo_avail_sizes', 'photo_print_type'));
	}
	
	public function add_to_cart() {
		
		///////////////////////////////////////////////
		// make sure ids are valid
			if (!isset($this->data['PhotoPrintType']['id']) || !isset($this->data['Photo']['id']) || !isset($this->data['Photo']['short_side_inches'])) {
				$this->major_error("photo_print_type_id or photo_id or short_side_inches not set in add to cart", array('data' => $this->data, 'params' => $this->params));
				$this->Session->setFlash(__('Error adding item to cart.', true), 'admin/flashMessage/error');
				$this->redirect($this->referer());
				exit();
			}

			$photo_print_type_id = $this->data['PhotoPrintType']['id'];
			$photo_id = $this->data['Photo']['id'];
			$short_side_inches = $this->data['Photo']['short_side_inches'];
			
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// make sure a limited photo is not added to the cart
			$max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
			if (!empty($max_photo_id) && $photo_id > $max_photo_id) {
				$this->major_error("tried to add limited photo to the cart", array('data' => $this->data, 'params' => $this->params));
				$this->Session->setFlash(__('Error adding item to cart.', true), 'admin/flashMessage/error');
				$this->redirect($this->referer());
				exit();
			}
			

			$photo_exists = $this->Photo->find('first', array(
				'conditions' => array(
					'Photo.id' => $photo_id
				),
				'contain' => false
			));
			if (empty($photo_exists)) {
				$this->major_error("photo_id not connected to real photo", array('data' => $this->data, 'params' => $this->params));
				$this->Session->setFlash(__('Error adding item to cart.', true), 'admin/flashMessage/error');
				$this->redirect($this->referer());
				exit();
			}
			$print_type_exists = $this->PhotoPrintType->find('first', array(
				'conditions' => array(
					'PhotoPrintType.id' => $photo_print_type_id
				),
				'contain' => false
			));
			if (empty($print_type_exists)) {
				$this->major_error("photo_print_type_id not connected to real print type", array('data' => $this->data, 'params' => $this->params));
				$this->Session->setFlash(__('Error adding item to cart.', true), 'admin/flashMessage/error');
				$this->redirect($this->referer());
				exit();
			}
			
			// DREW TODO - validate the short side inches 
			// $short_side_inches
			
			// DREW TODO - validate the long side inches
			
			
			// DREW TODO - add in the price to the calculation
			// validate the price againts the print type and size
			
			
			$qty = 1;
			if (!empty($this->data['qty'])) {
				$qty = $this->data['qty'];
			}
		// end validation
		
			
			
		$this->Cart->add_to_cart($photo_id, $photo_print_type_id, $short_side_inches, $qty);
		if (empty($this->data['redirect_url'])) {
			$this->redirect('/ecommerces/view_cart/');
		} else {
			$this->redirect($this->data['redirect_url']);
		}
	}
	
	public function update_cart_qty() {
		if (!empty($this->data['cart_items'])) {
			$this->Cart->update_cart_qty($this->data['cart_items']);
		}
		
		$this->redirect('/ecommerces/view_cart');
	}
	
	public function view_cart() {
//		$this->Cart->create_fake_cart_items(); // DREW TODO - delete this line
		//$this->Cart->create_fake_cart_items_laptop(); // DREW TODO - delete this line
		
		$this->ThemeRenderer->render($this);
	}
	
	public function cart_empty_redirect() { 
		$this->redirect('/ecommerces/view_cart');
		exit();
	}
		
	
	/**
	 *	Change the frontend password
	 * 
	 * @param type $user_id
	 * @param type $passed_modified_hash 
	 */
	public function change_fe_password($user_id, $passed_modified_hash) {
		$change_password_user = $this->User->find('first', array(
			'conditions' => array(
				'User.id' => $user_id,
			),
			'contain' => false,
		));
		
		$can_change_password = false;
		if (!empty($change_password_user)) {
			$modified_hash = openssl_digest($change_password_user['User']['modified'].FORGOT_PASSWORD_SALT, 'sha512');
			
			if ($modified_hash === $passed_modified_hash) {
				$can_change_password = true;
			}
		}
		
		
		if ($can_change_password === true && isset($this->data['User']['new_password']) && isset($this->data['User']['new_password_repeat'])) {
			try {
				$this->Validation->validate('valid_password', $this->data['User'], 'new_password', 'Please enter a valid password.');
				$this->Validation->validate('password_match', $this->data['User']['new_password'], $this->data['User']['new_password_repeat'], 'The passwords must match.');
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage(), 'admin/flashMessage/error');
				$this->ThemeRenderer->render_default($this, '/elements/change_password');
				return;
			}
			
			// actually change the password
			$new_password_hash = Security::hash($this->data['User']['new_password'], null, true);
			$change_password_user['User']['password'] = $new_password_hash;
			unset($change_password_user['User']['modified']);
			if (!$this->User->save($change_password_user)) {
				$this->Session->setFlash(__("Failed to change password.", true), 'admin/flashMessage/error');
				$this->User->major_error('Failed to change front end user password.', compact('change_password_user'));
			} else {
				$this->Session->setFlash(__("Password changed.", true), 'admin/flashMessage/success');
			}
		}
		
		
		$this->set(compact('can_change_password', 'user_id', 'passed_modified_hash'));
		$this->ThemeRenderer->render_default($this, '/elements/change_password');
	}
	
	
	public function checkout_login_or_guest() {
		if (isset($this->data['Cart'])) {
			$restored_cart_data = unserialize(base64_decode($this->data['Cart']));
			$this->Cart->override_cart($restored_cart_data);
		}
		
		
		if ($this->Cart->cart_empty()) {
			 $this->cart_empty_redirect();
		}
		
		// we are sending the forgot password email
		if (isset($this->data['User']['forgot_password_email'])) {
			$forgot_password_email = $this->data['User']['forgot_password_email'];
			
			
			// check to make sure the email is a valid email for a user
			$change_password_user = $this->User->find('first', array(
				'conditions' => array(
					'User.email_address' => $forgot_password_email,
				),
				'contain' => false,
			));
			
			if (empty($change_password_user)) {
				$this->Session->setFlash(__('Email does not belong to a valid user.', true), 'admin/flashMessage/error');
			} else {
				$this->FotomatterEmail->send_forgot_password_email($this, $change_password_user);
			}
		}
		
		
		// we are logging in
		if (isset($this->data['User']['password'])) {
			if ($this->Auth->login()) {
				$this->redirect('/ecommerces/checkout_finalize_payment');
			} else {
				$this->Session->setFlash(__('Invalid login credentials.', true), 'admin/flashMessage/error');
			}
		}
		
		
		$logged_in = $this->is_logged_in_frontend();
		if ($logged_in) {
			if (!$this->Cart->has_cart_shipping_address_data()) {
				$this->redirect('/ecommerces/checkout_get_address/');
			}
			
			$this->redirect('/ecommerces/checkout_finalize_payment');
		}

		$this->ThemeRenderer->render($this);
	}
	
	public function checkout_get_address() {
		if ($this->Cart->cart_empty()) {
			$this->cart_empty_redirect();
		}
		
//		if ($logged_in) { // DREW TODO
			// get logged in user info to popuplate the cart address data with
//		}
		
		
		if (!empty($this->data)) {
			// validate the data
				try {
					// validate billing address // DREW TODO - remove this
//					$this->Validation->validate('not_empty', $this->data, 'BillingAddress', __('Billing address must be passed.', true));
//					$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'firstname', __('Billing first name is required.', true));
//					$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'lastname', __('Billing last name is required.', true));
//					$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'address1', __('Billing address is required.', true));
//					$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'city', __('Billing city is required.', true));
//					$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'zip', __('Billing zip code is required.', true));
//					$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'country_id', __('Billing country is required.', true));
//					if (isset($this->data['BillingAddress']['state_id']) && $this->data['BillingAddress']['state_id'] !== 'no_state') {
//						$this->Validation->validate($this, 'not_empty', $this->data['BillingAddress'], 'state_id', __('Billing state is required.', true));
//					}

					// validate shipping address
					$this->Validation->validate('not_empty', $this->data, 'ShippingAddress', __('Shipping address must be passed.', true));
					$this->Validation->validate('not_empty', $this->data['ShippingAddress'], 'firstname', __('Shipping first name is required.', true));
					$this->Validation->validate('not_empty', $this->data['ShippingAddress'], 'lastname', __('Shipping last name is required.', true));
					$this->Validation->validate('not_empty', $this->data['ShippingAddress'], 'address1', __('Shipping address is required.', true));
					$this->Validation->validate('not_empty', $this->data['ShippingAddress'], 'city', __('Shipping city is required.', true));
					$this->Validation->validate('not_empty', $this->data['ShippingAddress'], 'zip', __('Shipping zip code is required.', true));
					$this->Validation->validate('not_empty', $this->data['ShippingAddress'], 'country_id', __('Shipping country is required.', true));
					// DREW TODO - fix a bug where state doesn't have to be set to save the shipping address
					if (isset($this->data['ShippingAddress']['state_id']) && $this->data['ShippingAddress']['state_id'] !== 'no_state') {
						$this->Validation->validate($this, 'not_empty', $this->data['ShippingAddress'], 'state_id', __('Shipping state is required.', true));
					}
				} catch (Exception $e) {
					$this->Session->setFlash($e->getMessage(), 'admin/flashMessage/error');
					$this->ThemeRenderer->render($this);
					return;
				}
				
			// save the data into the cart session
//			$billing_data = $this->data['BillingAddress']; // DREW TODO - remove this
//			if (isset($this->data['ShippingAddress']['same_as_billing'])) {
//				$shipping_data = $billing_data;
//				$shipping_data['same_as_billing'] = true;
//			} else {
				$shipping_data = $this->data['ShippingAddress'];
				$shipping_data['same_as_billing'] = false;
//			}
			$this->Cart->set_cart_shipping_address_data($shipping_data);
			
			$this->redirect('/ecommerces/checkout_finalize_payment');
		}
		
		
		$this->ThemeRenderer->render($this);
	}
	
	public function checkout_finalize_payment() {
		if ($this->Cart->cart_empty()) {
			 $this->cart_empty_redirect();
		}
		
		
		$logged_in_user = $this->Auth->user();
		$logged_in = (!empty($logged_in_user) && isset($logged_in_user['User']['admin']) && $logged_in_user['User']['admin'] != 1) ? true : false ;
		if ($logged_in !== true) { 
			$logged_in_user = array();
		}
		$this->set('logged_in', $logged_in);
		
		
		
		if ($logged_in === true) { 
			// get logged in user info to populate address and cc info
			$this->Cart->prepopulate_cart_by_user($logged_in_user);
		}
		
		
//		$this->log($this->Session->read('Cart'), 'checkout_finalize_payment');
		if (!$this->Cart->has_cart_shipping_address_data()) {
			$this->redirect('/ecommerces/checkout_get_address/');
		}
		
		
		
		if (!empty($this->data)) {
			$billing_address = $this->data['BillingAddress'];
			$this->set('billing_address', $billing_address);
			
			
			// setup the data variable based on the cart
			$cart_payment_data = $this->Cart->get_cart_credit_card_data();
			if (!empty($cart_payment_data['last_four'])) {
				$this->data['Payment']['last_four'] = $cart_payment_data['last_four'];
			}
			
			// validate the data
			try {
				// validate create account data
				if (!empty($this->data['CreateAccount']['email_address'])) {
					$this->Validation->validate('valid_email', $this->data['CreateAccount'], 'email_address', __('Email address invalid.', true));
					$this->Validation->validate('not_empty', $this->data['CreateAccount'], 'password', __('Please enter an account password.', true));
					$this->Validation->validate('not_empty', $this->data['CreateAccount'], 'repeat_password', __('Please reenter the password.', true));
					$this->Validation->validate('valid_password', $this->data['CreateAccount'], 'password', __('Account password must be at least 8 characters long.', true));
					$this->Validation->validate('password_match', $this->data['CreateAccount']['password'], $this->data['CreateAccount']['repeat_password'], __('Account repeat password does not match', true));
					
					// check to see if the user email address is available
					$exists = $this->User->find('first', array(
						'conditions' => array('User.email_address' => $this->data['CreateAccount']['email_address']),
						'contain' => false,
					));
					
					if (!empty($exists)) {
						throw new Exception('Email address already taken.');
					}
				}
				
				// validate billing address // DREW TODO - remove this
				$this->Validation->validate('not_empty', $this->data, 'BillingAddress', __('Billing address must be passed.', true));
				$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'firstname', __('Billing first name is required.', true));
				$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'lastname', __('Billing last name is required.', true));
				$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'address1', __('Billing address is required.', true));
				$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'city', __('Billing city is required.', true));
				$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'zip', __('Billing zip code is required.', true));
				$this->Validation->validate('not_empty', $this->data['BillingAddress'], 'country_id', __('Billing country is required.', true));
				if (isset($this->data['BillingAddress']['state_id']) && $this->data['BillingAddress']['state_id'] !== 'no_state') {
					$this->Validation->validate($this, 'not_empty', $this->data['BillingAddress'], 'state_id', __('Billing state is required.', true));
				}
				
				
				// validate cc info
				$this->Validation->validate('in_array', $this->data['Payment']['credit_card_method'], array('visa', 'mastercard', 'discover', 'amex'), __('Invalid payment method.', true));
				////////////////////////////////////////////////////////////
				// don't worry about cc if not logged in or cc not empty
				// this is so that the card ending charged thing will work
				if ($logged_in === false || !empty($this->data['Payment']['card_number'])) {
					$this->Validation->validate('valid_cc', $this->data['Payment']['card_number'], $this->data['Payment']['credit_card_method'], __('Invalid credit card number.', true));
					$this->Validation->validate('valid_cc_code', $this->data['Payment'], 'security_code', __('Invalid security code.', true));
				}
				// make sure the expiration is in the future
				$expiration_timestamp = mktime(0, 0, 0, (int)$this->data['Payment']['expiration_month'], 1, (int)$this->data['Payment']['expiration_year']);
				$expiration_str = date('Y-m-d H:i:s', $expiration_timestamp);
				if (time() > $expiration_timestamp) {
					throw new Exception('Invalid card expiration.');
				}
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage(), 'admin/flashMessage/error');
				$this->ThemeRenderer->render($this);
				return;
			}
			
			
			/////////////////////////////////////////////////////
			// create a user if need be 
			// also create a CIM account for the user - and charge
			// amount to CIM account
			// otherwise just charge straight to authorize.net
			if (!empty($this->data['CreateAccount']['email_address']) || $logged_in === true) {
				$new_user = array();
				if ($logged_in === true) {
					$user_id = $logged_in_user['User']['id'];
					$authnet_data = $this->AuthnetProfile->find('first', array(
						'conditions' => array(
							'AuthnetProfile.user_id' => $user_id,
						),
						'contain' => false,
					));
				} else {
					$authnet_data = array();
					$user_id = $this->User->create_user($this->data['CreateAccount']['email_address'], $this->data['CreateAccount']['password'], false); // NOTE - the email address was validated above
					
					
					//////////////////////////////////////////////////////////////////////////////////////////////////
					// now log the new user in here
					$new_user = $this->User->find('first', array(
						'conditions' => array(
							'User.id' => $user_id,
						),
						'contain' => false
					));
					$new_user['User']['password'] = $this->data['CreateAccount']['password'];
				}
				
				// try and save the credit card data to authorize.net CIM
				$shipping_address = $this->Cart->get_cart_shipping_address();
				$authnet_data['AuthnetProfile']['user_id'] = $user_id;
				$authnet_data['AuthnetProfile']['billing_firstname'] = $billing_address['firstname'];
				$authnet_data['AuthnetProfile']['billing_lastname'] = $billing_address['lastname'];
				$authnet_data['AuthnetProfile']['billing_address'] = $billing_address['address1']." ".$billing_address['address2'];
				$authnet_data['AuthnetProfile']['billing_city'] = $billing_address['city'];
				$authnet_data['AuthnetProfile']['billing_state'] = $this->GlobalCountryState->get_state_name_by_id($billing_address['state_id']);
				$authnet_data['AuthnetProfile']['billing_zip'] = $billing_address['zip'];
				$authnet_data['AuthnetProfile']['billing_country'] = $this->GlobalCountry->get_country_name_by_id($billing_address['country_id']);
				$authnet_data['AuthnetProfile']['billing_phoneNumber'] = isset($billing_address['phoneNumber']) ? $billing_address['phoneNumber'] : '' ;
				$authnet_data['AuthnetProfile']['payment_cardNumber'] = $this->data['Payment']['card_number'];
				$authnet_data['AuthnetProfile']['payment_expirationDate'] = $expiration_str;
				$authnet_data['AuthnetProfile']['payment_cardCode'] = $this->data['Payment']['security_code'];
				$authnet_data['AuthnetProfile']['shipping_firstname'] = $shipping_address['firstname'];
				$authnet_data['AuthnetProfile']['shipping_lastname'] = $shipping_address['lastname'];
				$authnet_data['AuthnetProfile']['shipping_address'] = $shipping_address['address1']." ".$shipping_address['address2'];
				$authnet_data['AuthnetProfile']['shipping_city'] = $shipping_address['city'];
				$authnet_data['AuthnetProfile']['shipping_state'] = $shipping_address['state_name'];
				$authnet_data['AuthnetProfile']['shipping_zip'] = $shipping_address['zip'];
				$authnet_data['AuthnetProfile']['shipping_country'] = $shipping_address['country_name'];
				$authnet_data['AuthnetProfile']['payment_cc_last_four'] = (!empty($this->data['Payment']['card_number'])) ? substr($this->data['Payment']['card_number'], -4, 4) : $this->data['Payment']['last_four'];
				$authnet_data['AuthnetProfile']['payment_method'] = $this->data['Payment']['credit_card_method'];
				

				$this->AuthnetProfile->create();
				if (isset($authnet_data['AuthnetProfile']['id'])) {
					$authnet_result = $this->AuthnetProfile->save_profile($authnet_data);
				} else {
					$authnet_result = $this->AuthnetProfile->process_new_profile($authnet_data);
				}

				if ($authnet_result === false || (is_array($authnet_result) && isset($authnet_result['success']) && $authnet_result['success'] === false) )  {
					// failed to create authnet profile so we need to delete the user we created above if it was a new user
					if (!empty($new_user)) {
						$this->User->delete($new_user['User']['id']);
					}
					
					
					if (isset($authnet_result['code']) && $authnet_result['code'] == 'E00027') {
						$this->Session->setFlash(__('Your credit card was declined.', true), 'admin/flashMessage/error');
					} else {
						$this->Session->setFlash(__('Failed to create credit card profile.', true), 'admin/flashMessage/error');
						$this->major_error('Failed to save credit card info. Please contact Fotomatter support.', compact('authnet_result'), 'low');
					}
					$this->ThemeRenderer->render($this);
					return;
				}
				$authnet_data['AuthnetProfile']['id'] = $this->AuthnetProfile->id;


				// actually charge for the order
				if (!$this->AuthnetOrder->charge_cart_to_cim($authnet_data['AuthnetProfile']['id'])) {
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					// failed to create charge authnet profile so we need to delete the user we created above if it was a new user
					// also we need to delete the authnet profile we created above
					// this way the user can just adjust the user info on the page again
					if (!empty($new_user)) {
						$this->User->delete($new_user['User']['id']);
					}
//					if ($this->AuthnetProfile->delete($this->AuthnetProfile->id) === false) {
//						$this->major_error('failed to delete authnetprofile that was just created but had a payfail');
//					}
					
					
					$this->Session->setFlash(__('Failed to charge credit card.', true), 'admin/flashMessage/error');
					$this->major_error('Failed to charge credit card from CIM for frontend cart.', array(), 'low');
					$this->ThemeRenderer->render($this);
					return;
				}
				
				
				////////////////////////////////////////////////////
				// everything worked so login the new user
				$this->Auth->login($new_user);
				
				
				// this means that the purchase was a success so we need to empty the cart now
				$this->Cart->destroy_cart();
				$this->redirect('/ecommerces/checkout_thankyou');
			} else {
				$authnet_data = array();
				
				// try charge to credit card as a one time charge
				$shipping_address = $this->Cart->get_cart_shipping_address();
				$authnet_data['AuthnetProfile']['billing_firstname'] = $billing_address['firstname'];
				$authnet_data['AuthnetProfile']['billing_lastname'] = $billing_address['lastname'];
				$authnet_data['AuthnetProfile']['billing_address'] = $billing_address['address1']." ".$billing_address['address2'];
				$authnet_data['AuthnetProfile']['billing_city'] = $billing_address['city'];
				$authnet_data['AuthnetProfile']['billing_state'] = $this->GlobalCountryState->get_state_name_by_id($billing_address['state_id']);
				$authnet_data['AuthnetProfile']['billing_zip'] = $billing_address['zip'];
				$authnet_data['AuthnetProfile']['billing_country'] = $this->GlobalCountry->get_country_name_by_id($billing_address['country_id']);
				$authnet_data['AuthnetProfile']['billing_phoneNumber'] = isset($billing_address['phoneNumber']) ? $billing_address['phoneNumber'] : '' ;
				$authnet_data['AuthnetProfile']['payment_cardNumber'] = $this->data['Payment']['card_number'];
				$authnet_data['AuthnetProfile']['payment_expirationDate'] = $expiration_str;
				$authnet_data['AuthnetProfile']['payment_cardCode'] = $this->data['Payment']['security_code'];
				$authnet_data['AuthnetProfile']['shipping_firstname'] = $shipping_address['firstname'];
				$authnet_data['AuthnetProfile']['shipping_lastname'] = $shipping_address['lastname'];
				$authnet_data['AuthnetProfile']['shipping_address'] = $shipping_address['address1']." ".$shipping_address['address2'];
				$authnet_data['AuthnetProfile']['shipping_city'] = $shipping_address['city'];
				$authnet_data['AuthnetProfile']['shipping_state'] = $shipping_address['state_name'];
				$authnet_data['AuthnetProfile']['shipping_zip'] = $shipping_address['zip'];
				$authnet_data['AuthnetProfile']['shipping_country'] = $shipping_address['country_name'];
				$authnet_data['AuthnetProfile']['payment_cc_last_four'] = substr($this->data['Payment']['card_number'], -4, 4);
				$authnet_data['AuthnetProfile']['payment_method'] = $this->data['Payment']['credit_card_method'];
				
				$result_data = $this->AuthnetOrder->one_time_charge($authnet_data);
				
				
				if (empty($result_data) || (isset($result_data['success']) && $result_data['success'] !== true)) {
					if (isset($result_data['declined']) && $result_data['declined'] === true) {
						$this->Session->setFlash(__('Transaction declined.', true), 'admin/flashMessage/error');
					} else {
						$this->Session->setFlash(__('An unknown error occured processing the transaction.', true), 'admin/flashMessage/error');
					}
					
					$this->ThemeRenderer->render($this);
					return;
				} 
				
				// this means that the purchase was a success so we need to empty the cart now
				$this->Cart->destroy_cart();
				$this->redirect('/ecommerces/checkout_thankyou');
			}
		}
		
		// setup the data variable based on the cart
		$cart_payment_data = $this->Cart->get_cart_credit_card_data();
		if (!empty($cart_payment_data)) {
			$this->data['Payment'] = $cart_payment_data;
			if (!isset($this->data['Payment']['last_four'])) {
				$this->data['Payment']['last_four'] = '';
			}
		}
		
		$this->ThemeRenderer->render($this);
	}
	
	
	public function remove_cart_item_by_index($i) {
		$this->Cart->remove_cart_item_by_index($i);
		$this->redirect('/ecommerces/view_cart/');
	}
	
	public function remove_cart_item_by_key($cart_key) {
		$this->Cart->remove_cart_item_by_key($cart_key);
		$this->redirect('/ecommerces/view_cart/');
	}
	
	
	
	public function checkout_thankyou() {
		// this means that the purchase was a success so we need to empty the cart now
		$this->Cart->destroy_cart();
		
		$this->ThemeRenderer->render($this);
	}
	
	
	public function get_available_states_for_country_options($country_id, $state_id = '') {
		$state_option_html = '';
		$data = array();
		$this->GlobalCountryState = ClassRegistry::init('GlobalCountryState');

		// NOTE: this call is APC cached so no need to cache the below
		$states = $this->GlobalCountryState->get_states_by_country($country_id);

		$data['count'] = count($states);
		if ($data['count'] > 0) {
			$state_option_html .= "<option value=''>".__('Choose a State', true)."</option>";
		} else {
			$state_option_html .= "<option value='no_state'>&nbsp;</option>";
		}
		foreach ($states as $state) {
			$selected = '';
			if (isset($state_id) && $state_id == $state['GlobalCountryState']['id']) {
				$selected = 'selected="selected"';
			}

			$state_option_html .= "<option value='{$state['GlobalCountryState']['id']}' $selected >{$state['GlobalCountryState']['state_name']}</option>";
		}
		$data['html'] = $state_option_html;
		
		
		$this->return_json($data);
	}
	
	
	
}