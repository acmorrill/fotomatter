<?php
class EcommercesController extends AppController {
     public $name = 'Ecommerces';
     public $uses = array('PhotoAvailSize', 'PhotoFormat');
	 public $layout = 'admin/ecommerces';
	 
	 
	 public function beforeFilter() {
		 parent::beforeFilter();
		 
		 
	 }
	 
	 public function admin_index() {
		 
	 }
	 
	 public function admin_manage_print_sizes() {
		 $photo_avail_sizes = $this->PhotoAvailSize->find('all', array(
			'contain' => false
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
		 
	 }
}