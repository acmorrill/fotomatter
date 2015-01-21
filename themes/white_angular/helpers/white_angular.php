<?php

class WhiteAngularHelper extends AppHelper {
	public $helpers = array('Photo');
	
	public function get_image_center_y($x) {
		return ((-395/318)*$x) + 150;
	}
	
	public function get_image_center_x($y) {
		return ((-318/395)*$y) + (9540/79);
	}
	
	public $prev_left = null;
	public function process_angular_photo_data(&$photo) {
		$blank = false;
		$alt_img_src = null;
		if (isset($photo['blank_photo']) || !isset($photo['Photo'])) {
			$blank = true;
			$width = '720';
			$height = '500';
			$img_src['url'] = '/img/blank_image.png';
			$img_src['width'] = $width;
			$img_src['height'] = $height;
			$img_src['tag_attributes'] = "width='$width' height='$height'";
			$img_src['style_attributes'] = "width: {$width}px; height: {$height}px;";
		} else {
			$width = null;
			$height = null;
			$alt_width = null;
			$alt_height = null;

			switch ($photo['Photo']['PhotoFormat']['ref_name']) {
				case 'square':
					$width = 636;
					$height = 636;
					break;
				case 'portrait':
					$width = 636;
					$height = 680;
					$alt_width = 636;
					$alt_height = 3000;
					break;
				case 'landscape':
					$width = 720;
					$height = 3000;
					break;
				case 'panoramic':
					$height = 300;
					$width = 3000;
					break;
				case 'vertical_panoramic':
					$width = 3000;
					$height = 680;
					$alt_width = 636;
					$alt_height = 3000;
					break;
			}
			$photo['classes'][] = $photo['Photo']['PhotoFormat']['ref_name'];
			$img_src = $this->Photo->get_photo_path($photo['Photo']['id'], $height, $width, .4, true); 
			if (isset($alt_width) && isset($alt_height)) {
				$alt_img_src = $this->Photo->get_photo_path($photo['Photo']['id'], $alt_height, $alt_width, .4, true); 
			}
		}
		
		
		$cover_width = 988;
		$cont_left_add = -250;
		
		
		$total_width = $img_src['width'] + 20;
		$total_height = $img_src['height'] + 20;
		$alt_total_width = null;
		$alt_total_height = null;
		if (isset($alt_img_src)) {
			$alt_total_width = $alt_img_src['width'] + 20;
			$alt_total_height = $alt_img_src['height'] + 20;
		}

		// figure out the position of the left cover
		$distance_from_middle = 68;
		$distance_to_close = 210;
		$cover_width_left = 360 - $distance_from_middle - $cover_width;
		$cover_width_right = 360 + $distance_from_middle;

		if (!isset($this->prev_left)) {
			$left = 0;
		} else {
			$left = $this->prev_left + $cont_left_add;
		}
		$this->prev_left = $left;
		
		
		return compact('blank', 'img_src', 'alt_img_src', 'total_width', 'total_height', 'alt_total_width', 'alt_total_height', 'distance_to_close', 'cover_width_left', 'cover_width_right', 'left');
	}
	
	public function process_photos_for_angular_slide(&$photos) {
		/////////////////////////////////////////////////////////
		// mark start photos as real photos
		foreach ($photos as $key => $photo) {
			$photos[$key]['classes'][] = 'actual_image';
			$photos[$key]['actual_image'] = true;
		}

		/////////////////////////////////////////////////////////
		// mark first and last real photos for convenience in js
		reset($photos);
		$first_key = key($photos);
		$photos[$first_key]['classes'][] = 'first';
		end($photos);
		$last_key = key($photos);
		$photos[$last_key]['classes'][] = 'last';


		/////////////////////////////////////////////////////////
		// add photos for endless circle illusion
		$num_to_pad = 0;
		$total_real_photos = count($photos);
		if ($total_real_photos >= 7) {
			$num_to_pad = 3;
		} else if ($total_real_photos >= 6) {
			$num_to_pad = 2;
		} else if ($total_real_photos >= 5) {
			$num_to_pad = 1;
		}
		$first_n_pad_photos = array();
		$last_n_pad_photos = array();
		if ($num_to_pad > 0) {
			// grab nub from beginning and end
			$first_n_pad_photos = array_slice($photos, 0, $num_to_pad);
			$last_n_pad_photos = array_slice($photos, -$num_to_pad);
		}
		foreach ($last_n_pad_photos as &$last_n_pad_photo) {
			unset($last_n_pad_photo['classes']);
			unset($last_n_pad_photo['actual_image']);
			$last_n_pad_photo['classes'][] = 'fake_image';
			$last_n_pad_photo['classes'][] = 'before';
		}
		for ($i = count($last_n_pad_photos) - 1; $i >= 0; $i--) {
			array_unshift($photos, $last_n_pad_photos[$i]);
		}
		foreach ($first_n_pad_photos as &$first_n_pad_photo) {
			unset($first_n_pad_photo['classes']);
			unset($first_n_pad_photo['actual_image']);
			$first_n_pad_photo['classes'][] = 'fake_image';
			$first_n_pad_photo['classes'][] = 'after';
		}
		foreach ($first_n_pad_photos as $first_n_pad_photo) {
			array_push($photos, $first_n_pad_photo);
		}



		//////////////////////////////////////////////////////////////
		// add 4 blank onto beginning and end
		array_unshift($photos, array('blank_photo' => true, 'classes' => array()));
		array_unshift($photos, array('blank_photo' => true, 'classes' => array()));
		array_unshift($photos, array('blank_photo' => true, 'classes' => array()));
		array_unshift($photos, array('blank_photo' => true, 'classes' => array()));
		array_push($photos, array('blank_photo' => true, 'classes' => array()));
		array_push($photos, array('blank_photo' => true, 'classes' => array()));
		array_push($photos, array('blank_photo' => true, 'classes' => array()));
		array_push($photos, array('blank_photo' => true, 'classes' => array()));


		// reverse photos just for this theme (because of the way the js animations are done)
		$photos = array_reverse($photos);
	}
	
	
	
}