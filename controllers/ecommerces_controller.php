<?php
class EcommercesController extends AppController {
	public $name = 'Ecommerces';
	public $uses = array('PhotoAvailSize', 'PhotoFormat', 'PhotoPrintType', 'PhotoAvailSizesPhotoPrintType');
	public $layout = 'admin/ecommerces';


	public function beforeFilter() {
		parent::beforeFilter();


	}

	public function admin_index() {

	}
	
	public function admin_reset_print_sizes() {
		$this->PhotoAvailSize->restore_avail_photo_size_defaults();
		
		$this->Session->setFlash('Available print sizes reset.');
		
		$this->redirect('/admin/ecommerces/manage_print_sizes');
	}
	 
	public function admin_delete_print_size($photo_avail_size_id) {
		if (!$this->PhotoAvailSize->delete($photo_avail_size_id)) {
			$this->Session->setFlash('Failed to delete available photo size.');
			$this->major_error('Failed to delete available photo size.', array($photo_avail_size_id));
		}
		
		$this->redirect('/admin/ecommerces/manage_print_sizes');
	}
	
	public function admin_add_print_size($photo_avail_size_id = null) {
		if (!empty($this->data)) {
			if ( !isset($this->data['PhotoAvailSize']['photo_format_ids']) ) {
				$this->Session->setFlash('Please choose photo formats to apply the print size to.');
			} else if ( !isset($this->data['PhotoAvailSize']['short_side_length']) ) {
				$this->Session->setFlash('Please choose a short side length.');
			} else {
				$this->data['PhotoAvailSize']['photo_format_ids'] = implode(',', $this->data['PhotoAvailSize']['photo_format_ids']);

				$this->PhotoAvailSize->create();
				if (!$this->PhotoAvailSize->save($this->data)) {
					$this->Session->setFlash('Failed to add available photo size.');
					$this->major_error('Failed to save available photo size.', array($this->data));
				} else {
					$this->redirect('/admin/ecommerces/manage_print_sizes');
				}
			}
		} else if (isset($photo_avail_size_id)) {
			$this->data = $this->PhotoAvailSize->find('first', array(
				'conditions' => array(
					'PhotoAvailSize.id' => $photo_avail_size_id
				),
				'contain' => false
			));
		}

		$used_short_side_dimensions = $this->PhotoAvailSize->get_used_short_side_values();

		$short_side_values = $this->PhotoAvailSize->valid_short_side_values();

		$this->set(compact('short_side_values', 'used_short_side_dimensions'));
	}
	 
	public function admin_manage_print_sizes() {
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
		$photo_print_types = $this->PhotoPrintType->find('all', array(
			'order' => array(
				'PhotoPrintType.order ASC'
			),
			'contain' => false
		));
		
		$this->set(compact('photo_print_types'));
	}
	
	
	public function admin_ajax_set_print_type_order($photo_print_type_id, $new_order) {
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
		if (!$this->PhotoPrintType->delete($photo_print_type_id)) {
			$this->Session->setFlash('Failed to delete photo print type.');
			$this->major_error('Failed to delete photo print type.', compact('photo_print_type_id'));
		}
		
		$this->redirect('/admin/ecommerces/manage_print_types_and_pricing');
	}
		
		
	
	public function admin_add_print_type_and_pricing($photo_print_type_id = 0) {
		if (!empty($this->data)) { 
			///////////////////////////////////////////////
			// do validation on the data
			$passed_validation = true;
			$print_type_id = !empty($this->data['PhotoPrintType']['id']) ? $this->data['PhotoPrintType']['id'] : null ;
			$print_name = !empty($this->data['PhotoPrintType']['print_name']) ? $this->data['PhotoPrintType']['print_name'] : null ;
			$turnaround_time = !empty($this->data['PhotoPrintType']['turnaround_time']) ? $this->data['PhotoPrintType']['turnaround_time'] : '' ;
			
			if ($passed_validation && !isset($print_name)) {
				$this->Session->setFlash("Print name must be set.");
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
					$this->Session->setFlash("Failed to save photo print type.");
					$this->PhotoPrintType->major_error('Failed to save photo print type', compact('new_photo_type'));
				} else {
					// add into the PhotoPrintSizesPhotoPrintType join table
					$save_error = false; 
					if (!empty($this->data['PhotoAvailSizesPhotoPrintType'])) {
						foreach ($this->data['PhotoAvailSizesPhotoPrintType'] as $count => $curr_join_data) {
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
								$this->Session->setFlash("Failed to connect photo print type to photo print size.");
								$this->PhotoPrintType->major_error('Failed to connect photo print type to photo print size', compact('new_join_table_data'));
								$save_error = true;
								break;
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
}