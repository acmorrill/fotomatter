<?php

class PhotoAvailSize extends AppModel {
	public $name = 'PhotoAvailSize';
	
	
	public function restore_avail_photo_size_defaults() {
		$defaults = array(
			array('short_size' => 2.5,),
			array('short_size' => 3.5,),
			array('short_size' => 4,),
			array('short_size' => 5,),
			array('short_size' => 8,),
			array('short_size' => 10,'add_pano' => true,),
			array('short_size' => 11,),
			array('short_size' => 16,'add_pano' => true,),
			array('short_size' => 20,),
			array('short_size' => 22,'add_pano' => true,),
			array('short_size' => 24,),
			array('short_size' => 26,'add_pano' => true,),
			array('short_size' => 29,'add_pano' => true,),
			array('short_size' => 30,),
			array('short_size' => 40,),
			array('short_size' => 44,),
			array('short_size' => 48,),
		);
		
		$this->PhotoFormat = ClassRegistry::init('PhotoFormat');
		$reg_formats = $this->PhotoFormat->find('all', array(
			'conditions' => array(
				'PhotoFormat.ref_name' => array('landscape', 'portrait', 'square')
			),
			'contain' => false
		));
		$reg_format_ids = Set::extract('/PhotoFormat/id', $reg_formats);
		
		$pano_formats = $this->PhotoFormat->find('all', array(
			'conditions' => array(
				'PhotoFormat.ref_name' => array('panoramic', 'vertical_panoramic')
			),
			'contain' => false
		));
		$pano_format_ids = Set::extract('/PhotoFormat/id', $pano_formats);
		
		$this->query('TRUNCATE TABLE photo_avail_sizes');
		
		foreach ($defaults as $default) {
			$new_photo_avail_size = array();
			$new_photo_avail_size['short_side_length'] = $default['short_size'];
			if (isset($default['add_pano'])) {
				$new_photo_avail_size['photo_format_ids'] = implode(',', $reg_format_ids) . ',' . implode(',', $pano_format_ids);
			} else {
				$new_photo_avail_size['photo_format_ids'] = implode(',', $reg_format_ids);
			}
			
			$this->create();
			$this->save($new_photo_avail_size);
		}
	}
}