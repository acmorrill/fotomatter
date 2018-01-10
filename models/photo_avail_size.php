<?php

class PhotoAvailSize extends AppModel {
	public $name = 'PhotoAvailSize';

	public $hasMany = array(
		'PhotoAvailSizesPhotoPrintType'
	);
	
	public function get_photo_avail_sizes($photo_print_type_id) {
		$photo_avail_sizes_query = "
			SELECT * FROM photo_avail_sizes AS PhotoAvailSize
				LEFT JOIN photo_avail_sizes_photo_print_types AS PhotoAvailSizesPhotoPrintType
					ON (PhotoAvailSizesPhotoPrintType.photo_avail_size_id = PhotoAvailSize.id AND PhotoAvailSizesPhotoPrintType.photo_print_type_id = :photo_print_type_id )
			ORDER BY PhotoAvailSize.short_side_length ASC
		";
		$photo_avail_sizes = $this->query($photo_avail_sizes_query, array(
			'photo_print_type_id' => $photo_print_type_id
		));
		
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// add in the predicted long size depending on format, short side and square inches
		foreach ($photo_avail_sizes as &$final_size) {
			if ($final_size['PhotoAvailSize']['photo_format_ids'] == '1,2,3') {
				$final_size['PhotoAvailSize']['has_non_pano'] = true;
				$final_size['PhotoAvailSize']['has_pano'] = false;
			} else {
				$final_size['PhotoAvailSize']['has_non_pano'] = false;
				$final_size['PhotoAvailSize']['has_pano'] = true;
			}
			$this->set_predicted_size_by_format($final_size['PhotoAvailSize']);
		}
		
		return $photo_avail_sizes;
	}
	
	public function set_predicted_size_by_format(&$photo_avail_size) {
		if ($photo_avail_size['has_pano'] == true) {
			$photo_avail_size['min_long_side_length'] = $photo_avail_size['short_side_length'] * 2;
			$photo_avail_size['max_long_side_length'] = $photo_avail_size['short_side_length'] * 4;
		} else {
			$photo_avail_size['min_long_side_length'] = $photo_avail_size['short_side_length'];
			$photo_avail_size['max_long_side_length'] = $photo_avail_size['short_side_length'] * 2;
		}
		
		$photo_avail_size['min_sq_inches'] = $photo_avail_size['short_side_length'] * $photo_avail_size['min_long_side_length'];
		$photo_avail_size['max_sq_inches'] = $photo_avail_size['short_side_length'] * $photo_avail_size['max_long_side_length'];
		$photo_avail_size['avg_long_side_length'] = ($photo_avail_size['min_long_side_length'] + $photo_avail_size['max_long_side_length']) / 2;
		$photo_avail_size['avg_sq_inches'] = ($photo_avail_size['min_sq_inches'] + $photo_avail_size['max_sq_inches']) / 2;
		
		return $photo_avail_size;
	}
	
	
	public function print_size_has_non_pano($print_type) {
		return (isset($print_type['PhotoAvailSize']['photo_format_ids']) && strpos($print_type['PhotoAvailSize']['photo_format_ids'], '1,2,3') !== false);
	}
	
	public function print_size_has_pano($print_type) {
		return (isset($print_type['PhotoAvailSize']['photo_format_ids']) && strpos($print_type['PhotoAvailSize']['photo_format_ids'], '4,5') !== false);
	}
	
	
	
	public function restore_avail_photo_size_defaults() {
		$defaults = array(
			array('short_size' => 2,),
			array('short_size' => 2.5,),
			array('short_size' => 3,),
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
			$new_photo_avail_size['photo_format_ids'] = implode(',', $reg_format_ids);
			$this->create();
			$this->save($new_photo_avail_size);
			
			if (isset($default['add_pano'])) {
				$new_photo_avail_size['photo_format_ids'] = implode(',', $pano_format_ids);
				$this->create();
				$this->save($new_photo_avail_size);
			}
			
		}
		
		
		//  make sure setting the default also removes items from photo_avail_sizes_photo_print_types
		$query = "
			DELETE FROM photo_avail_sizes_photo_print_types
			WHERE photo_avail_size_id NOT IN (
				SELECT id FROM photo_avail_sizes
			)
		";
		$this->PhotoAvailSizesPhotoPrintType->query($query);
	}
	
	public function get_used_short_side_values() {
		$all_values = $this->find('all', array(
			'contain' => false
		));
		
		$short_side_used = array();
		$format_used = array();
		foreach ($all_values as $key => $value) {
			if ($value['PhotoAvailSize']['photo_format_ids'] == '1,2,3') {
				$short_side_used[$value['PhotoAvailSize']['short_side_length']]['non_pano'] = true;
				$format_used['non_pano'][$value['PhotoAvailSize']['short_side_length']] = true;
			}
			if ($value['PhotoAvailSize']['photo_format_ids'] == '4,5') {
				$short_side_used[$value['PhotoAvailSize']['short_side_length']]['pano'] = true;
				$format_used['pano'][$value['PhotoAvailSize']['short_side_length']] = true;
			}
		}
		
		return compact('short_side_used', 'format_used');
	}
	
	public function valid_short_side_values() {
		$valid_sides = array(
			2,
			2.5,
			3,
			3.5,
		);
		for ($i = 4; $i <= 96; $i++) {
			$valid_sides[] = $i;
		}
		
		return $valid_sides;
	}
	
	
	public function afterDelete() {
		$this->PhotoAvailSizesPhotoPrintType->deleteAll(array(
			'PhotoAvailSizesPhotoPrintType.photo_avail_size_id' => $this->id
		), true, true);
	}
	
	
	
	
	
}