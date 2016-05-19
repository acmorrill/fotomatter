<?php

class PhotoPrintType extends AppModel {
	public $name = 'PhotoPrintType';
	
	public $hasMany = array(
		'PhotoAvailSizesPhotoPrintType'
	);
	
	public $actsAs = array('Ordered' => array(
		'foreign_key' => false,
		'field' => 'order',
	));
	
	public function afterDelete() {
		$this->PhotoAvailSizesPhotoPrintType->deleteAll(array(
			'PhotoAvailSizesPhotoPrintType.photo_print_type_id' => $this->id
		), true, true);
	}
	
	
	public function combine_autofulfillment_print_list($print_fulfiller_print_type, $photo_avail_sizes) {
		switch($print_fulfiller_print_type['type']) {
			case 'fixed': // means sizes only chosen by the user (avail print sizes)
				if (!empty($print_fulfiller_print_type['PhotoAvailSizesPhotoPrintType'])) {
					foreach ($print_fulfiller_print_type['PhotoAvailSizesPhotoPrintType'] as $curr_print_avail_print_type) {
						if (isset($print_fulfiller_print_type['PrintFulfillerPrintTypeFixedSize'][$curr_print_avail_print_type['print_fulfiller_print_type_fixed_size_id']])) {
							$print_fulfiller_print_type['PrintFulfillerPrintTypeFixedSize'][$curr_print_avail_print_type['print_fulfiller_print_type_fixed_size_id']]['PhotoAvailSizesPhotoPrintType'] = $curr_print_avail_print_type;
						}
						
					}
				}
			
				// just set the display type as fixed
				foreach ($print_fulfiller_print_type['PrintFulfillerPrintTypeFixedSize'] as &$fixed_size) {
					$fixed_size['display_type'] = 'fixed';
				}
				return $print_fulfiller_print_type['PrintFulfillerPrintTypeFixedSize'];
			case 'dynamic': // means sizes only chosen by end user
				// display type already set to dynamic
				return $photo_avail_sizes;
			case 'fixeddynamic': // fixed dynamic means that there are both fixed (chosen by the printer) and dynamic (determined by the avail print sizes)
				if (!empty($print_fulfiller_print_type['PhotoAvailSizesPhotoPrintType'])) {
					foreach ($print_fulfiller_print_type['PhotoAvailSizesPhotoPrintType'] as $curr_print_avail_print_type) {
						if (isset($print_fulfiller_print_type['PrintFulfillerPrintTypeFixedSize'][$curr_print_avail_print_type['print_fulfiller_print_type_fixed_size_id']])) {
							$print_fulfiller_print_type['PrintFulfillerPrintTypeFixedSize'][$curr_print_avail_print_type['print_fulfiller_print_type_fixed_size_id']]['PhotoAvailSizesPhotoPrintType'] = $curr_print_avail_print_type;
						}
						
					}
				}
			
				// merge both 
				$merged_arrays = array_merge($print_fulfiller_print_type['PrintFulfillerPrintTypeFixedSize'], $photo_avail_sizes);
				foreach ($merged_arrays as &$merged_array) {
					if (isset($merged_array['short_side_inches'])) {
						$merged_array['display_type'] = 'fixed';
					}
				}
				usort($merged_arrays, array($this, 'sort_autofulfillment_array'));
				return $merged_arrays;
		}
	}
	
	private function sort_autofulfillment_array($a, $b) {
		$a_value;
		if (isset($a['PhotoAvailSize']['short_side_length'])) { $a_value = $a['PhotoAvailSize']['short_side_length']; }
		if (isset($a['short_side_inches'])) { $a_value = $a['short_side_inches']; }
		
		$b_value;
		if (isset($b['PhotoAvailSize']['short_side_length'])) { $b_value = $b['PhotoAvailSize']['short_side_length']; }
		if (isset($b['short_side_inches'])) { $b_value = $b['short_side_inches']; }
		
		if ($a_value == $b_value) {
			if (isset($a['PhotoAvailSize']['short_side_length']) && isset($b['PhotoAvailSize']['short_side_length'])) {
				return 0;
			} elseif (isset($a['short_side_inches']) && isset($b['short_side_inches'])) {
				return 0;
			} else {
				return isset($b['PhotoAvailSize']['short_side_length']) ? -1 : 1;
			}
		}
		return ($a_value < $b_value) ? -1 : 1;
	}
	
	public function create_new_photo_print_type($type, $print_fulfiller_id = null, $print_fulfiller_print_type_id = null, $print_fulfiller_print_type = array(), $print_name = 'New Print') {
		$data = array();
		$data['PhotoPrintType']['print_name'] = $print_name;
		$data['PhotoPrintType']['turnaround_time'] = '3 Weeks';
		$data['PhotoPrintType']['print_fulfillment_type'] = $type;
		$data['PhotoPrintType']['print_fulfiller_id'] = $print_fulfiller_id;
		$data['PhotoPrintType']['print_fulfiller_print_type_id'] = $print_fulfiller_print_type_id;
		
		
		$this->create();
		$this->save($data);
		
		if (!empty($print_fulfiller_print_type['PrintFulfillerPrintTypeFixedSize'])) {
			foreach ($print_fulfiller_print_type['PrintFulfillerPrintTypeFixedSize'] as $fixed_size_id => $fixed_size) {
				$photo_avail_sizes_photo_print_type = array();
				$photo_avail_sizes_photo_print_type['PhotoAvailSizesPhotoPrintType']['photo_avail_size_id'] = 0;
				$photo_avail_sizes_photo_print_type['PhotoAvailSizesPhotoPrintType']['print_fulfiller_print_type_fixed_size_id'] = $fixed_size_id;
				$photo_avail_sizes_photo_print_type['PhotoAvailSizesPhotoPrintType']['photo_print_type_id'] = $this->id;
				$photo_avail_sizes_photo_print_type['PhotoAvailSizesPhotoPrintType']['fixed_available'] = 0;
				$photo_avail_sizes_photo_print_type['PhotoAvailSizesPhotoPrintType']['fixed_price'] = 0;
				$photo_avail_sizes_photo_print_type['PhotoAvailSizesPhotoPrintType']['fixed_handling_price'] = 0;
				$photo_avail_sizes_photo_print_type['PhotoAvailSizesPhotoPrintType']['fixed_custom_turnaround'] = 14;
				$photo_avail_sizes_photo_print_type['PhotoAvailSizesPhotoPrintType']['fixed_global_default'] = 1;
				$photo_avail_sizes_photo_print_type['PhotoAvailSizesPhotoPrintType']['fixed_force_settings'] = 1;
				$this->PhotoAvailSizesPhotoPrintType->create();
				$this->PhotoAvailSizesPhotoPrintType->save($photo_avail_sizes_photo_print_type);
			}
		}
		
		return $this->id;
	}
	
	
	/*
	 * returns true or string on error
	 */
	public function validate_and_save_print_type($data, $is_self_fulfillment = true) {
		///////////////////////////////////////////////
		// do validation on the data
		$print_type_id = !empty($data['PhotoPrintType']['id']) ? $data['PhotoPrintType']['id'] : null ;
		$print_name = !empty($data['PhotoPrintType']['print_name']) ? $data['PhotoPrintType']['print_name'] : null ;
		$turnaround_time = !empty($data['PhotoPrintType']['turnaround_time']) ? $data['PhotoPrintType']['turnaround_time'] : '' ;
		$print_fulfillment_type = !empty($data['PhotoPrintType']['print_fulfillment_type']) ? $data['PhotoPrintType']['print_fulfillment_type'] : '' ;

		if (!isset($print_name)) {
			$print_name = "New Print Type";
		}
		if (empty($turnaround_time)) {
			$turnaround_time = '3 Weeks';
		}

		$return_data = array();

		// create the new photo type
		$new_photo_type = array();
		$new_photo_type['PhotoPrintType']['id'] = $print_type_id;
		$new_photo_type['PhotoPrintType']['print_name'] = $print_name;
		$new_photo_type['PhotoPrintType']['turnaround_time'] = $turnaround_time;
		$new_photo_type['PhotoPrintType']['print_fulfillment_type'] = $print_fulfillment_type;
		$this->create();
		if (!$this->save($new_photo_type)) {
			$this->major_error('Failed to save photo print type', compact('new_photo_type'));
			return __("Failed to save photo print type.", true);
		} else {
			$new_photo_type = $this->find('first', array(
				'conditions' => array(
					'PhotoPrintType.id' => $this->id,
				),
				'contain' => false
			));
			
			// add into the PhotoPrintSizesPhotoPrintType join table
			if (!empty($data['PhotoAvailSizesPhotoPrintType'])) {
				$curr_join_data = $data['PhotoAvailSizesPhotoPrintType'];
				
				////////////////////////////////////////
				// validate data
				if (isset($curr_join_data['non_pano_price']) && !is_numeric($curr_join_data['non_pano_price'])) {
					unset($curr_join_data['non_pano_price']);
				}
				if (isset($curr_join_data['non_pano_shipping_price']) && !is_numeric($curr_join_data['non_pano_shipping_price'])) {
					unset($curr_join_data['non_pano_shipping_price']);
				}
				if (isset($curr_join_data['pano_price']) && !is_numeric($curr_join_data['pano_price'])) {
					unset($curr_join_data['pano_price']);
				}
				if (isset($curr_join_data['pano_shipping_price']) && !is_numeric($curr_join_data['pano_shipping_price'])) {
					unset($curr_join_data['pano_shipping_price']);
				}
				if (empty($curr_join_data['non_pano_available'])) {
					$curr_join_data['non_pano_available'] = 0;
				} else {
					$curr_join_data['non_pano_available'] = 1;
				}
				if (empty($curr_join_data['pano_available'])) {
					$curr_join_data['pano_available'] = 0;
				} else {
					$curr_join_data['pano_available'] = 1;
				}
				if ($curr_join_data['non_pano_custom_turnaround'] == $turnaround_time) { unset($curr_join_data['non_pano_custom_turnaround']); }
				if ($curr_join_data['pano_custom_turnaround'] == $turnaround_time) { unset($curr_join_data['pano_custom_turnaround']); }
				
				
				if (!isset($curr_join_data['photo_avail_size_id']) || ($curr_join_data['non_pano_available'] != 1 && $curr_join_data['pano_available'] != 1) ) { // means we need to remove the join table entry instead
					$this->PhotoAvailSizesPhotoPrintType->deleteAll(array(
						'PhotoAvailSizesPhotoPrintType.id' => $curr_join_data['id']
					), true, true);
					$return_data['row_deleted'] = true;
				} else { // save the join table entry
					$new_join_table_data = array();
					if (empty($curr_join_data['id'])) {
						$curr_join_data['id'] = null;
					}
					$new_join_table_data['id'] = $curr_join_data['id'];
					$new_join_table_data['photo_avail_size_id'] = $curr_join_data['photo_avail_size_id'];
					$new_join_table_data['photo_print_type_id'] = $this->id;
					$new_join_table_data['non_pano_available'] = $curr_join_data['non_pano_available'];
					if ($new_join_table_data['non_pano_available'] === 1) {
						$new_join_table_data['non_pano_price'] = !empty($curr_join_data['non_pano_price']) ? $curr_join_data['non_pano_price'] : '0.00';
						$new_join_table_data['non_pano_shipping_price'] = !empty($curr_join_data['non_pano_shipping_price']) ? $curr_join_data['non_pano_shipping_price'] : '0.00';
						$new_join_table_data['non_pano_custom_turnaround'] = (!empty($curr_join_data['non_pano_custom_turnaround']) && $curr_join_data['non_pano_custom_turnaround'] != $turnaround_time) ? $curr_join_data['non_pano_custom_turnaround'] : '';
						$new_join_table_data['non_pano_global_default'] = !empty($curr_join_data['non_pano_global_default']) ? 1 : 0;
						$new_join_table_data['non_pano_force_settings'] = !empty($curr_join_data['non_pano_force_settings']) ? 1 : 0;
					} else {
						$new_join_table_data['non_pano_price'] = '0.00';
						$new_join_table_data['non_pano_shipping_price'] = '0.00';
						$new_join_table_data['non_pano_custom_turnaround'] = '';
						$new_join_table_data['non_pano_global_default'] = 1;
						$new_join_table_data['non_pano_force_settings'] = 1;
					}
					$new_join_table_data['pano_available'] = $curr_join_data['pano_available'];
					if ($new_join_table_data['pano_available'] === 1) {
						$new_join_table_data['pano_price'] = !empty($curr_join_data['pano_price']) ? $curr_join_data['pano_price'] : '0.00';
						$new_join_table_data['pano_shipping_price'] = !empty($curr_join_data['pano_shipping_price']) ? $curr_join_data['pano_shipping_price'] : '0.00';
						$new_join_table_data['pano_custom_turnaround'] = (!empty($curr_join_data['pano_custom_turnaround']) && $curr_join_data['pano_custom_turnaround'] != $turnaround_time) ? $curr_join_data['pano_custom_turnaround'] : '';
						$new_join_table_data['pano_global_default'] = !empty($curr_join_data['pano_global_default']) ? 1 : 0;
						$new_join_table_data['pano_force_settings'] = !empty($curr_join_data['pano_force_settings']) ? 1 : 0;
					} else {
						$new_join_table_data['pano_price'] = '0.00';
						$new_join_table_data['pano_shipping_price'] = '0.00';
						$new_join_table_data['pano_custom_turnaround'] = '';
						$new_join_table_data['pano_global_default'] = 1;
						$new_join_table_data['pano_force_settings'] = 1;
					}
					$new_join_table_data_save['PhotoAvailSizesPhotoPrintType'] = $new_join_table_data;



					$this->PhotoAvailSizesPhotoPrintType->create();
					if (!$this->PhotoAvailSizesPhotoPrintType->save($new_join_table_data_save)) {
						$this->major_error('Failed to connect photo print type to photo print size', compact('new_join_table_data'));
						return __("Failed to connect photo print type to photo print size.", true);
					} 
					$new_join_table_data['id'] = $this->PhotoAvailSizesPhotoPrintType->id;
					$new_photo_type['PhotoAvailSizesPhotoPrintType'] = $new_join_table_data;
				}
			}
		}
		
		
		return $new_photo_type; // means no errors - return string means error
	}
}