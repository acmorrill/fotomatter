<?php
class EcommercesController extends AppController {
	public $name = 'Ecommerces';
	public $uses = array('PhotoAvailSize', 'PhotoFormat', 'PhotoPrintType');
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
	
	
	public function admin_add_print_type_and_pricing($photo_print_type_id = 0) {
		if (!empty($this->data)) { 
			$this->log($this->data, 'add_print_type_and_pricing');
			
			///////////////////////////////////////////////
			// do validation on the data
			$passed_validation = true;
			$print_name = !empty($this->data['PhotoPrintType']['print_name']) ? $this->data['PhotoPrintType']['print_name'] : null ;
			$turnaround_time = !empty($this->data['PhotoPrintType']['turnaround_time']) ? $this->data['PhotoPrintType']['turnaround_time'] : '' ;
			
			if ($passed_validation && !isset($print_name)) {
				$this->Session->setFlash("Print name must be set.");
				$passed_validation = false;
			}
			
//			if ($passed_validation && !isset($turnaround_time)) {
//				$this->Session->setFlash("");
//				$passed_validation = false;
//			}
			

			if ($passed_validation) {
				// create the new photo type
				$new_photo_type = array();
				$new_photo_type['PhotoPrintType']['print_name'] = $print_name;
				$new_photo_type['PhotoPrintType']['turnaround_time'] = $turnaround_time;
				$this->PhotoPrintType->create();
				$this->PhotoPrintType->save($new_photo_type);
				
				
				// add into the PhotoPrintSizesPhotoPrintType join table
				foreach ($this->data['PhotoAvailSizesPhotoPrintType'] as $curr_join_data) {
					
				}
				
				
				
//				[photo_avail_size_id] => 6
//				[non_pano_available] => on
//				[non_pano_price] => 40.00
//				[non_pano_shipping_price] => 40.00
//				[non_pano_custom_turnaround] => override
//				[non_pano_global_default] => on
//				[non_pano_force_settings] => on
//				[pano_available] => on
//				[pano_price] => 50.00
//				[pano_shipping_price] => 50.00
//				[pano_custom_turnaround] => override
//				[pano_global_default] => on
//				[pano_force_settings] => on
			}
			
			
			
		}
		
		
		$photo_avail_sizes_query = "
			SELECT * FROM photo_avail_sizes AS PhotoAvailSize
				LEFT JOIN photo_avail_sizes_photo_print_types AS PhotoAvailSizesPhotoPrintType
					ON (PhotoAvailSizesPhotoPrintType.photo_avail_size_id = PhotoAvailSize.id AND PhotoAvailSize.id = ':photo_print_type_id' )
				LEFT JOIN photo_print_types AS PhotoPrintType
					ON (PhotoAvailSizesPhotoPrintType.photo_print_type_id = PhotoPrintType.id)
			ORDER BY PhotoAvailSize.short_side_length ASC
		";
		
		$photo_avail_sizes = $this->PhotoAvailSize->query($photo_avail_sizes_query, array(
			'photo_print_type_id' => $photo_print_type_id
		));
		
		
		$this->set(compact('photo_avail_sizes'));
	}
}