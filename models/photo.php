<?php

/**
 * TEST NOTES
 * 
 * -- make sure if the cdn-filename is set - then all of the following are set
 * 		-- cd-filename-forcache
 * 		-- cd-filename-smaller-forcache
 * 		-- pixel_width
 * 		-- pixel_height
 * 		-- forcache_pixel_width
 * 		-- forcache_pixel_height
 * 		-- smaller_forcache_pixel_width
 * 		-- smaller_forcache_pixel_height
 * 
 * 
 * --- test that convert will not uprez for either master cache or smaller master cache
 * --- test that if you upload a small original a larger cache file will still work (upres)
 * 
 * -- make sure only .jpeg or .jpg can be uploaded
 */
class Photo extends AppModel {

	public $name = 'Photo';
	public $belongsTo = array('PhotoFormat');
	public $displayField = 'display_title';
	public $hasMany = array(
		'PhotoCache' => array(
			'dependent' => true
		),
		'PhotoGalleriesPhoto' => array(
			'order' => array(
				"PhotoGalleriesPhoto.photo_gallery_id" => 'asc'
			),
			'dependent' => true
		)
	);
	public $hasAndBelongsToMany = array('Tag');
	public $limit_last_photo_apc_key;
	public $photos_count_apc_key;

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->limit_last_photo_apc_key = 'limit_last_photo_' . $_SERVER['local']['database'];
		$this->photos_count_apc_key = 'photos_count_' . $_SERVER['local']['database'];
		$this->photos_space_apc_key = 'photos_space_' . $_SERVER['local']['database'];
	}

	public function delete_all_photos() {
		$all_photos = $this->find('all', array(
			'contain' => false,
		));
		
		foreach ($all_photos as $curr_photo) {
			if ($this->delete($curr_photo['Photo']['id']) === false) {
				$this->major_error("failed to delete all photos at once", compact('curr_photo'));
				return false;
			}
		}
		
		return true;
	}
	
	public function get_first_photo_id() {
		$first_photo = $this->find('first', array(
			'contain' => false
		));

		if (!empty($first_photo['Photo']['id'])) {
			return $first_photo['Photo']['id'];
		} else {
			return 0;
		}
	}
	
	public function get_first_n_photos($limit, $return_full_photos = false) {
		$first_photos = $this->find('all', array(
			'limit' => $limit,
			'contain' => false,
		));

		if (!empty($first_photos)) {
			if ($return_full_photos) {
				$this->add_photo_format($first_photos);
				return $first_photos;
			}
			
			$photo_ids = Set::extract('/Photo/id', $first_photos);
			return $photo_ids;
		} else {
			return array();
		}
	}
        
	public function clear_apc_cache() {
		apc_delete($this->limit_last_photo_apc_key);
		apc_delete($this->photos_count_apc_key);
	}

	public function get_last_photo_id_based_on_limit() {
		if (apc_exists($this->limit_last_photo_apc_key)) {
			return apc_fetch($this->limit_last_photo_apc_key);
		}

		$max_nth_photo = false;
		if (empty($GLOBALS['current_on_off_features']['unlimited_photos'])) {
			$max_num_photos = LIMIT_MAX_FREE_PHOTOS - 1;
			$query = "
				SELECT id FROM photos as Photo
				ORDER BY Photo.id
				LIMIT 1 OFFSET  $max_num_photos
			";
			$max_nth_photo = $this->query($query);
			if (!empty($max_nth_photo[0]['Photo']['id'])) {
				$max_nth_photo = $max_nth_photo[0]['Photo']['id'];
			} else {
				$max_nth_photo = false;
			}
		}

		apc_store($this->limit_last_photo_apc_key, $max_nth_photo, 604800); // 1 week

		return $max_nth_photo;
	}

	public function beforeDelete() {
		parent::beforeDelete();
		$this->clear_apc_cache();


		$photo = $this->find("first", array(
			"conditions" => array("Photo.id" => $this->id),
			'contain' => false
		));


		$globally_shared = false;
		if ($photo['Photo']['is_globally_shared'] == 1) {
			$globally_shared = true;
		}

		if (!$globally_shared && isset($photo['Photo']['cdn-filename'])) {
			$this->CloudFiles = $this->get_cloud_file();

			if (!$this->CloudFiles->delete_object($photo['Photo']['cdn-filename'])) {
				$this->major_error("failed to delete object cdn-filename in photo before delete", $photo['Photo']['cdn-filename']);
			}
		}

		if (isset($photo['Photo']['cdn-filename-forcache'])) {
			if (!$globally_shared) {
				$this->CloudFiles = $this->get_cloud_file();
				if (!$this->CloudFiles->delete_object($photo['Photo']['cdn-filename-forcache'])) {
					$this->major_error("failed to delete object cdn-filename-forcache in photo before delete", $photo['Photo']['cdn-filename-forcache']);
				}
			}

			@ unlink(LOCAL_MASTER_CACHE . DS . $photo['Photo']['cdn-filename-forcache']);
		}

		if (isset($photo['Photo']['cdn-filename-smaller-forcache'])) {
			if (!$globally_shared) {
				$this->CloudFiles = $this->get_cloud_file();
				if (!$this->CloudFiles->delete_object($photo['Photo']['cdn-filename-smaller-forcache'])) {
					$this->major_error("failed to delete object cdn-filename-smaller-forcache in photo before delete", $photo['Photo']['cdn-filename-smaller-forcache']);
				}
			}

			@ unlink(LOCAL_SMALLER_MASTER_CACHE . DS . $photo['Photo']['cdn-filename-smaller-forcache']);
		}

		return true;
	}

	
	public function before_save_code($data) {
		$this->clear_apc_cache();


		if (!isset($data['Photo']['date_taken'])) {
			$data['Photo']['date_taken'] = date('Y-m-d');
		}



		////////////////////////////////////////////////////////////////////////////////////////////
		// if a file was uploaded then upload it to cloud files and then delete any previous file
		//	$data_from_array 
		if (isset($data['Photo']['cdn-filename']) && is_array($data['Photo']['cdn-filename']) && !empty($data['Photo']['cdn-filename']['tmp_name'])) {

			if (isset($data['Photo']['cdn-filename']['size'])) {
				$data['Photo']['file_size'] = $data['Photo']['cdn-filename']['size'] / 1000 / 1000; // file size is in megabytes
			} else {
				$data['Photo']['file_size'] = 0;
			}
			
			
			
			// fail if the file is greater than max upload size
			if (!isset($data['Photo']['file_size']) || $this->check_image_filesize($data['Photo']['file_size']) === false) {
				if (empty($GLOBALS['current_on_off_features']['auto_fulfillment'])) { // they don't have auto fufillment
					return sprintf(__("Image is bigger than the max size of %d megabytes", true), MAX_UPLOAD_SIZE_MEGS);
				} else {
					return sprintf(__("Image is bigger than the max size of %d megabytes", true), MAX_PAID_UPLOAD_SIZE_MEGS);
				}
			}
			

			
			/////////////////////////////////////////////////////////////
			// make sure the image is actually an image
			if ($this->check_image_extension($data['Photo']['cdn-filename']['name']) === false) {
				$this->major_error("tried to upload a file without a jpeg extension");
				return __("Incorrect file type", true);
			}


			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// make sure image is not bigger than is allowed to upload - dimension wise
			$tmp_name = $this->check_and_resize_image_dimensions($data['Photo']['cdn-filename']['tmp_name']);
			if ($tmp_name === false) { 
				if (empty($GLOBALS['current_on_off_features']['auto_fulfillment'])) { // they don't have auto fufillment
					return sprintf(__("An unknown error occured", true), MAX_PAID_MEGAPIXELS);
				} else {
					return sprintf(__("Image is bigger than the max size of %d megapixels", true), MAX_PAID_MEGAPIXELS);
				}
			}
			$data['Photo']['cdn-filename']['tmp_name'] = $tmp_name;
			list($width, $height, $type, $attr) = getimagesize($data['Photo']['cdn-filename']['tmp_name']);
			$data['Photo']['megapixels'] = $width * $height / 1000000;

			

			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// all the photo cache is now invalidated - so delete them if there were any
			if (isset($data['Photo']['id'])) {
				$this->delete_cache_files_for_photos($data['Photo']['id']);
			}



			$this->CloudFiles = $this->get_cloud_file();
			$file_name = $this->get_valid_filename($data['Photo']['cdn-filename']['name']);
			$tmp_location = $data['Photo']['cdn-filename']['tmp_name'];
			$mime_type = $data['Photo']['cdn-filename']['type'];

			
			if ($this->CloudFiles->put_object($file_name, $tmp_location, $mime_type)) {
				// file successfully uploaded - so now automatically set the photo format
				$data['Photo']['photo_format_id'] = $this->PhotoFormat->get_photo_format_id($height, $width);
				$data['Photo']['cdn-filename'] = $file_name;
				$data['Photo']['pixel_width'] = $width;
				$data['Photo']['pixel_height'] = $height;
				$data['Photo']['tag_attributes'] = $attr;
				$data['Photo']['style_attributes'] = "width: {$width}px; height: {$height}px;";

				// now remove the old cloud file if there was one
				if (isset($data['Photo']['id'])) {
					$this->delete_cloud_file_for_photo($data['Photo']['id']);
				}
				

				if (is_writable(TEMP_IMAGE_PATH)) {
					//////////////////////////////////////////////////////////////////////////////////////////
					// now create a smaller version of the file 
					// - (or bigger 1500x1500) for use in creating the 
					// - cache files later
					$this->create_master_cache_file($file_name, $tmp_location, $data);

					
					//////////////////////////////////////////////////////////////////////////////////////////
					// now create an even smaller version of the master cache file 
					// - for use in creating the thumbnail cache files later
					$this->create_smaller_master_cache_file($file_name, $tmp_location, $data);
				} else {
					$this->major_error("the temp image path is not writable for photo before save for smaller master cache file");
					return __("Failed to upload photo", true);
				}
			} else {
				$this->major_error("failed to put an object to cloud files on photo save", array($data['Photo']['cdn-filename'], $file_name, $tmp_location, $mime_type));
				return __("Failed to upload photo - please try again", true);
//				unset($data['Photo']['cdn-filename']);
			}
		}

		return $data;
	}
	
	
	public function create_smaller_master_cache_file($file_name, $big_image_path, &$data) {
		$max_width = SMALL_MASTER_CACHE_SIZE;
		$max_height = SMALL_MASTER_CACHE_SIZE;

		$smaller_cache_image_name = SMALLER_MASTER_CACHE_PREFIX . $file_name;

		$data['Photo']['cdn-filename-smaller-forcache'] = $smaller_cache_image_name;


		// the command line image magick way
		$image_file_name = $this->random_num();
		$new_image_temp_path = TEMP_IMAGE_PATH . DS . $image_file_name;
		if ($this->PhotoCache->convert($big_image_path, $new_image_temp_path, $max_width, $max_height, false) == false) {
			$this->major_error('failed to create smaller mastercache file in photo beforeSave', array($new_image_temp_path, $max_width, $max_height));
		}


		if (!file_exists($new_image_temp_path)) {
			//so if the master cache file would be bigger than the image, then the image itself is used for the master cache file
			copy($big_image_path, $new_image_temp_path);
		}

		// write to the smaller local master cache file
		$local_master_cache_path = LOCAL_SMALLER_MASTER_CACHE . DS . $smaller_cache_image_name;
		copy($new_image_temp_path, $local_master_cache_path);


		$master_cache_size = getimagesize($new_image_temp_path);
		list($mastercache_width, $mastercache_height, $mastercache_type, $mastercache_attr) = $master_cache_size;

		$mastercache_mime = $master_cache_size['mime'];
		$data['Photo']['smaller_forcache_pixel_width'] = $mastercache_width;
		$data['Photo']['smaller_forcache_pixel_height'] = $mastercache_height;

		if (!$this->CloudFiles->put_object($smaller_cache_image_name, $new_image_temp_path, $mastercache_mime)) {
			$this->major_error("failed to put smaller master cache image in photo beforeSave", $smaller_cache_image_name);
			unset($data['Photo']['cdn-filename-smaller-forcache']);
			unset($data['Photo']['smaller_forcache_pixel_width']);
			unset($data['Photo']['smaller_forcache_pixel_height']);
		}

		unlink($new_image_temp_path);
		
		return true;
	}
	
	
	public function create_master_cache_file($file_name, $big_image_path, &$data) {
		//////////////////////////////////////////////////////////////////////////////////////////
		// now create a smaller version of the file (or bigger 1500x1500) for use in creating the cache files later
		$max_width = LARGE_MASTER_CACHE_SIZE;
		$max_height = LARGE_MASTER_CACHE_SIZE;

		$cache_image_name = MASTER_CACHE_PREFIX . $file_name;

		$data['Photo']['cdn-filename-forcache'] = $cache_image_name;


		// the command line image magick way
		$image_file_name = $this->random_num();
		$new_image_temp_path = TEMP_IMAGE_PATH . DS . $image_file_name;
		if ($this->PhotoCache->convert($big_image_path, $new_image_temp_path, $max_width, $max_height, false) == false) {
			$this->major_error('failed to create mastercache file in photo beforeSave', array($new_image_temp_path, $max_width, $max_height));
		}


		if (!file_exists($new_image_temp_path)) {
			//so if the master cache file would be bigger than the image, then the image itself is used for the master cache file
			copy($big_image_path, $new_image_temp_path);
		}

		// write to the local master cache file
		$local_master_cache_path = LOCAL_MASTER_CACHE . DS . $cache_image_name;
		copy($new_image_temp_path, $local_master_cache_path);


		$master_cache_size = getimagesize($new_image_temp_path);
		list($mastercache_width, $mastercache_height, $mastercache_type, $mastercache_attr) = $master_cache_size;

		$mastercache_mime = $master_cache_size['mime'];
		$data['Photo']['forcache_pixel_width'] = $mastercache_width;
		$data['Photo']['forcache_pixel_height'] = $mastercache_height;

		if (!$this->CloudFiles->put_object($cache_image_name, $new_image_temp_path, $mastercache_mime)) {
			// TODO - if a put fails - we need to fail gracefully -- ie - inform the user the photo did not upload correctly
			$this->major_error("failed to put master cache image in photo beforeSave", $cache_image_name);
			unset($data['Photo']['cdn-filename-forcache']);
			unset($data['Photo']['forcache_pixel_width']);
			unset($data['Photo']['forcache_pixel_height']);
		}

		unlink($new_image_temp_path);
		
		return true;
	}
	
	
	public function delete_cloud_file_for_photo($photo_id) {
		$oldPhoto = $this->find('first', array(
			'conditions' => array('Photo.id' => $photo_id),
			'contain' => false
		));

		if ($oldPhoto && !empty($oldPhoto['Photo']['cdn-filename'])) {
			if (!$this->CloudFiles->delete_object($oldPhoto['Photo']['cdn-filename'])) {
				$this->major_error("failed to delete a cloud object in Photo beforeSave", $oldPhoto['Photo']);
			}
		}
		
		return true;
	}
	
	
	public function delete_cache_files_for_photos($photo_id) {
		$this->PhotoCache->deleteAll(array(
			'PhotoCache.photo_id' => $photo_id
				), true, true);


		// delete the local master cache files -- they are now invalidated
		$old_photo = $this->find('first', array(
			'conditions' => array(
				'Photo.id' => $photo_id
			),
			'contain' => false
		));

		// delete the local master cache files
		if (isset($old_photo['Photo']['cdn-filename-forcache'])) {
			unlink(LOCAL_MASTER_CACHE . DS . $old_photo['Photo']['cdn-filename-forcache']);
		}
		if (isset($old_photo['Photo']['cdn-filename-smaller-forcache'])) {
			unlink(LOCAL_SMALLER_MASTER_CACHE . DS . $old_photo['Photo']['cdn-filename-smaller-forcache']);
		}
		
		return true;
	}
	
	
	
	/*
	 * if the image dimensions is too big then create a new image that is resized
	 * - also return the new image path so the tmp image path can be replaced
	 */
	public function check_and_resize_image_dimensions($path) {
		list($width, $height, $type, $attr) = getimagesize($path);
		
		if (empty($GLOBALS['current_on_off_features']['auto_fulfillment'])) { // they don't have auto fufillment
			if ($width > FREE_MAX_RES || $height > FREE_MAX_RES) {
				if (is_writable(TEMP_IMAGE_PATH) == false) {
					$this->major_error("the temp image path is not writable for photo before save for smaller master cache file");
				}

				// the command line image magick way
				$image_file_name = $this->random_num();
				$new_image_temp_path = TEMP_IMAGE_PATH . DS . $image_file_name;
				if ($this->PhotoCache->convert($path, $new_image_temp_path, FREE_MAX_RES, FREE_MAX_RES, false) == false) {
					$this->major_error('failed to create mastercache file in photo beforeSave', array($new_image_temp_path, FREE_MAX_RES, FREE_MAX_RES));
				}


				if (!file_exists($new_image_temp_path)) {
					//so if the master cache file would be bigger than the image, then the image itself is used for the master cache file
					copy($path, $new_image_temp_path);
				}
				$path = $new_image_temp_path;
			}
		} else {
			$megapixels = $width * $height / 1000000;
			if ($megapixels > MAX_PAID_MEGAPIXELS) {
				 return false;
			}
		}
		
		return $path;
	}
	
	
	public function check_image_filesize($filesize) {
		if (empty($GLOBALS['current_on_off_features']['auto_fulfillment'])) { // they don't have auto fufillment
			if ($filesize > MAX_UPLOAD_SIZE_MEGS) {
				return false;
			}
		} else {
			if ($filesize > MAX_PAID_UPLOAD_SIZE_MEGS) {
				return false;
			}
		}
		
		return true;
	}
	
	public function check_image_extension($path) {
		/////////////////////////////////////////////////////////////
		// make sure the image is actually an image
		$security_path_info = pathinfo($path);
		if (empty($security_path_info['extension'])) {
			return false;
		}
		$lowercase_extension = strtolower($security_path_info['extension']);
		if ($lowercase_extension !== 'jpeg' && $lowercase_extension !== 'jpg') {
			return false;
		}
		
		return true;
	}
	
	
	public function afterSave($created) {
		//////////////////////////////////////////////////////////////////////////////////////////
		// now create all the prebuilt cache sizes
		if (isset($this->data['Photo']['cdn-filename-forcache']) && isset($this->data['Photo']['cdn-filename-smaller-forcache']) && isset($this->id)) {
			$this->PhotoPrebuildCacheSize = Classregistry::init('PhotoPrebuildCacheSize');
			$this->ThemePrebuildCacheSize = Classregistry::init('ThemePrebuildCacheSize');
			$all_cache_sizes = $this->PhotoPrebuildCacheSize->find('all', array(
				'contain' => false
			));
			
			$theme_cache_sizes = $this->ThemePrebuildCacheSize->get_prebuild_cache_sizes_current_theme();
			$combined_cache_sizes = array_merge($all_cache_sizes, $theme_cache_sizes);
			
			$this->create_default_photo_caches($combined_cache_sizes, $this->id);
		}

		return true;
	}
	
	public function create_default_photo_caches($combined_cache_sizes, $photo_id) {
		foreach ($combined_cache_sizes as $all_cache_size) {
			if (isset($all_cache_size['PhotoPrebuildCacheSize'])) {
				$curr_data = $all_cache_size['PhotoPrebuildCacheSize'];
			} else if (isset($all_cache_size['ThemePrebuildCacheSize'])) {
				$curr_data = $all_cache_size['ThemePrebuildCacheSize'];
			}

			$lock_name = "start_create_cache_{$photo_id}_{$curr_data['max_height']}_{$curr_data['max_width']}_{$curr_data['crop']}_{$curr_data['unsharp']}_{$_SERVER['local']['database']}";;
			$initLocked = $this->get_lock($lock_name, 8);
			if ($initLocked === false) {
				continue;
			}


			$photoCache = $this->PhotoCache->cache_size_exists($photo_id, $curr_data['max_width'], $curr_data['max_height'], $curr_data['crop'], $curr_data['unsharp']);
			if (!$photoCache) {
				$unsharp_amount = 0;
				if (!empty($curr_data['unsharp'])) {
					$unsharp_amount = $curr_data['unsharp'];
				}
				if (isset($curr_data['used_on_upload'])) { // means was a theme cache size to be built
					$this->ThemePrebuildCacheSize->increment_used_on_upload($curr_data['id']);
				}
				if ($curr_data['crop'] == false) {
					$curr_data['crop'] = false;
				} else {
					$curr_data['crop'] = true;
				}
				$photo_cache_id = $this->PhotoCache->prepare_new_cachesize($photo_id, $curr_data['max_height'], $curr_data['max_width'], true, $unsharp_amount, false, $curr_data['crop']);
			} else {
				$releaseLock = $this->release_lock($lock_name);
				continue;
			}

			$releaseLock = $this->release_lock($lock_name);

			ignore_user_abort(true);
			set_time_limit(0);
			$this->PhotoCache->finish_create_cache($photo_cache_id, false);
		}
	}

	public function create_theme_photo_caches($theme_config) {
		// keeps the test environment from waiting to finish this before starting other calls
		session_write_close();
		ignore_user_abort(true);
		set_time_limit(0);

		// disable cache saying we are at 0 percent
		$this->disable_photo_cache(0);

		// If using a 2 level menu, ONLY get galleries that are in the menu
		$where = 'WHERE';
		if (!empty($theme_config['admin_config']['main_menu']['levels']) && $theme_config['admin_config']['main_menu']['levels'] == 2) {
			$galleries = "SELECT DISTINCT `external_id` FROM (SELECT `external_id` FROM `site_two_level_menu_container_items` WHERE `external_model` = 'PhotoGallery' UNION SELECT `external_id` FROM `site_two_level_menus` WHERE `external_model` = 'PhotoGallery') AS temp";
			$where .= " `photo_gallery_id` IN ($galleries) AND";
		}

		//get photos that need to be cached
		$gallery_limit = 30;
		$limit = 500;
		$query = "SELECT `photo_id`, min(`photo_order`) as `photo_order` FROM `photo_galleries_photos` $where `photo_order` <= $gallery_limit GROUP BY `photo_id` ORDER BY `photo_order` LIMIT $limit";
		$photos = $this->query($query);
//		$this->log($photos, 'photos_create_theme_photo_caches');
//		
//		return;

		// get theme cache sizes
		$this->ThemePrebuildCacheSize = Classregistry::init('ThemePrebuildCacheSize');
		$theme_cache_sizes = $this->ThemePrebuildCacheSize->get_prebuild_cache_sizes_current_theme();
//		$this->log($theme_cache_sizes, 'sizes_create_theme_photo_caches');

		$minute_limit = 15;
		$time_start = new DateTime();
		foreach ($photos as $i=>$photo) {
			$this->create_default_photo_caches($theme_cache_sizes, $photo['photo_galleries_photos']['photo_id']);
			// update apc entry to show current percent
			$this->disable_photo_cache(intval(($i+1)/count($photos)*100));
			$time_since = $time_start->diff(new DateTime());
			if ($time_since->i >= $minute_limit) {
				break;
			}
		}
		$this->invalidate_and_clear_view_cache();
		$this->enable_photo_cache();
	}

	// a function to efficiently add photo format to a list of photos (without a bunch of extra queries)
	public function add_photo_format(&$photos) {
		$format_apc_key = 'format_apc_key';
		if (apc_exists($format_apc_key)) {
			$formats = apc_fetch($format_apc_key);
		} else {
			$this->PhotoFormat = ClassRegistry::init('PhotoFormat');
			$photo_formats = $this->PhotoFormat->find('all', array(
				'contain' => false
			));
			$formats = Set::combine($photo_formats, '{n}.PhotoFormat.id', '{n}.PhotoFormat');
			apc_store($format_apc_key, $formats, 604800); // 1 week
		}

		if (isset($photos[0])) {
			foreach ($photos as &$photo) {
				if (isset($photo['Photo']['photo_format_id'])) {
					$photo['Photo']['PhotoFormat'] = $formats[$photo['Photo']['photo_format_id']];
				}
			}
		} else {
			if (isset($photos['Photo'])) {
				$photos['Photo']['PhotoFormat'] = $formats[$photos['Photo']['photo_format_id']];
			}
		}
	}

	public function get_dummy_error_image_path($height, $width, $direct_output = false, $return_tag_attributes = false, $crop = false) {
		$this->PhotoCache = ClassRegistry::init('PhotoCache');

		return $this->PhotoCache->get_dummy_error_image_path($height, $width, $direct_output, $return_tag_attributes, $crop);
	}

	public function get_photo_path($photo_id, $height, $width, $unsharp_amount = 0, $return_tag_attributes = false, $crop = false) {
		if ($height <= 0 || $width <= 0) {
			$this->major_error('Called get photo path like a moron', compact('width', 'height'));
			return $this->PhotoCache->get_dummy_error_image_path($height, $width, false, $return_tag_attributes, $crop);
		}


		////////////////////////////////////////////////////////////////////////////////////////////////////
		// get extra conditions based on photo limit
//		$max_photo_id = $this->get_last_photo_id_based_on_limit();
//		if (!empty($max_photo_id) && $photo_id > $max_photo_id) {
//			$this->major_error('Called get photo path on limited photo', compact('width', 'height', 'photo_id'));
//			return $this->PhotoCache->get_dummy_error_image_path($height, $width, false, $return_tag_attributes, $crop);
//		}
		/////////////////////////////////
		// get the photo
		$the_photo = $this->find('first', array(
			'conditions' => array(
				'Photo.id' => $photo_id,
			),
			'contain' => false
		));


		//////////////////////////////////////////////////
		// if $the_photo is empty throw an error
		if (empty($the_photo)) {
			$this->major_error('Called get photo path on empty photo', compact('width', 'height', 'photo_id'));
			return $this->PhotoCache->get_dummy_error_image_path($height, $width, false, $return_tag_attributes, $crop);
		}


		// check to make sure the photo has a file attached 
		if (empty($the_photo['Photo']['cdn-filename-forcache']) || empty($the_photo['Photo']['cdn-filename']) || empty($the_photo['Photo']['cdn-filename-smaller-forcache'])) {
			$this->major_error('Called get photo path with empty filename', compact('width', 'height', 'the_photo'));
			return $this->PhotoCache->get_dummy_error_image_path($height, $width, false, $return_tag_attributes, $crop);
		}


		$photoCache = $this->PhotoCache->cache_size_exists($photo_id, $width, $height, $crop, $unsharp_amount);
		$return_url = '';
		if (!empty($photoCache)) {
			if ($photoCache['PhotoCache']['status'] == 'ready') {
				$return_url = $this->PhotoCache->get_full_path($photoCache['PhotoCache']['id'], $return_tag_attributes);
			} else if ($photoCache['PhotoCache']['status'] == 'processing') {
				$return_url = $this->PhotoCache->get_dummy_processing_image_path($height, $width, false, $return_tag_attributes, $crop);
			} else if ($photoCache['PhotoCache']['status'] == 'failed') {
				$return_url = $this->PhotoCache->get_dummy_error_image_path($height, $width, false, $return_tag_attributes, $crop);
			} else {
				$initLocked = $this->get_lock("finish_create_cache_" . $photoCache['PhotoCache']['id'] . "_" . $_SERVER['local']['database'], 8);
				if ($initLocked === false) {
					return $this->PhotoCache->get_dummy_processing_image_path($height, $width, false, $return_tag_attributes, $crop);
				}

				// grab again after lock - to make sure we are not conflicting
				$photoCache = $this->PhotoCache->cache_size_exists($photo_id, $width, $height, $crop, $unsharp_amount);

				if ($photoCache['PhotoCache']['status'] == 'queued') {
					if ($this->PhotoCache->is_photo_cache_disabled() === true) {
						$return_url = $this->PhotoCache->get_dummy_processing_image_path($height, $width, false, $return_tag_attributes, $crop);
					} else {
						// TODO - maybe return the prepare path if the status is queued and some time has passed
						// I don't think I need to do the TODO now that I've added locking to the finish create cache and this helper
						$return_url = $this->PhotoCache->get_existing_cache_create_url($photoCache['PhotoCache']['id'], $return_tag_attributes);
					}
				} else {
					$return_url = $this->PhotoCache->get_dummy_error_image_path($height, $width, false, $return_tag_attributes, $crop);
				}

				$releaseLock = $this->release_lock("finish_create_cache_" . $photoCache['PhotoCache']['id'] . "_" . $_SERVER['local']['database']);
			}
		} else {
			if ($this->PhotoCache->is_photo_cache_disabled() === true) {
				$return_url = $this->PhotoCache->get_dummy_processing_image_path($height, $width, false, $return_tag_attributes, $crop);
			} else {
				$start_create_cache_lock_key = "start_create_cache_{$photo_id}_{$height}_{$width}_{$crop}_{$unsharp_amount}_{$_SERVER['local']['database']}";
				$initLocked = $this->get_lock($start_create_cache_lock_key, 8);
				if ($initLocked === false) {
					return $this->PhotoCache->get_dummy_processing_image_path($height, $width, false, $return_tag_attributes, $crop);
				}
				// grab again after lock - to make sure we are not conflicting
				$photoCache = $this->PhotoCache->cache_size_exists($photo_id, $width, $height, $crop, $unsharp_amount);
				if (empty($photoCache)) {
					$return_url = $this->PhotoCache->prepare_new_cachesize($photo_id, $height, $width, false, $unsharp_amount, $return_tag_attributes, $crop);
				} else {
					$return_url = $this->PhotoCache->get_dummy_error_image_path($height, $width, false, $return_tag_attributes, $crop);
				}

				$releaseLock = $this->release_lock($start_create_cache_lock_key);
			}
		}

		$return_url = preg_replace('/\s+/', '', $return_url);

		if ($return_tag_attributes == true) {
			if (!empty($the_photo['Photo']['alt_text'])) {
				$return_url['alt_title_str'] = "alt='{$the_photo['Photo']['alt_text']}' title='{$the_photo['Photo']['alt_text']}'";
			} else {
				$return_url['alt_title_str'] = "";
			}
		}

		return $return_url;
	}
	
	public function get_admin_photo_icon_size($not_in_gallery_icon_size) {
		// figure out icon sizes
		$height = 110;
		$width = 110;
		$class = 'medium_icon_size';
		if ($not_in_gallery_icon_size == 'small') {
			$height = 60;
			$width = 60;
			$class = 'small_icon_size';
		} else if ($not_in_gallery_icon_size == 'medium') {
			$height = 110;
			$width = 110;
			$class = 'medium_icon_size';
		} else if ($not_in_gallery_icon_size == 'large') {
			$height = 155;
			$width = 155;
			$class = 'large_icon_size';
		}

		return array(
			'height' => $height,
			'width' => $width,
			'class' => $class,
		);
	}

	public function get_full_path($id) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');

		////////////////////////////////////////////////////////////////////////////////////////////////////
		// get extra conditions based on photo limit
//		$max_photo_id = $this->get_last_photo_id_based_on_limit();
//		if (!empty($max_photo_id) && $id > $max_photo_id) {
//			$this->major_error('Called get_full_path on limited photo', compact('id'));
//			return '';
//		}

		$photo = $this->find('first', array(
			'conditions' => array('Photo.id' => $id),
			'contain' => false,
			'fields' => array('Photo.cdn-filename')
		));

		return $this->SiteSetting->getImageContainerUrl() . $photo['Photo']['cdn-filename'];
	}

	public function get_valid_filename($name) {
		$prefix = "fullsize";

		// remove spaces
		$name = str_replace(" ", "", $name);

		// remove underscores
		$name = str_replace("_", "", $name);

		
		$name_pathinfo = pathinfo($name);

		
		// find a name that doesn't already exist
		$name = $prefix . "_" . substr(md5(String::uuid()), 0, 20) . "_" . substr($name_pathinfo['filename'], 0, 45) . ".jpg";
		return $name;
	}

	private function get_cloud_file() {
		if (!isset($this->CloudFiles)) {
			App::import('Component', 'CloudFiles');
			$this->CloudFiles = new CloudFilesComponent();
		}

		return $this->CloudFiles;
	}

	public function photo_has_pano_format($photo) {
		$format_ref_name = isset($photo['PhotoFormat']['ref_name']) ? $photo['PhotoFormat']['ref_name'] : (isset($photo['Photo']['PhotoFormat']['ref_name']) ? $photo['Photo']['PhotoFormat']['ref_name'] : '' );
		if ($format_ref_name === 'panoramic' || $format_ref_name === 'vertical_panoramic') {
			return true;
		}

		return false;
	}

	/**
	 * This is the final sellable prints based on defaults and all values overridden by the current photo
	 * 
	 * 
	 * @param type $photo_id
	 */
	public function get_enabled_photo_sellable_prints($photo_id) {
		////////////////////////////////////////////////////////////////////////////////////////////////////
		// get extra conditions based on photo limit
//		$max_photo_id = $this->get_last_photo_id_based_on_limit();
//		if (!empty($max_photo_id) && $photo_id > $max_photo_id) {
//			$this->major_error('Called get_enabled_photo_sellable_prints on limited photo', compact('photo_id'));
//			return array();
//		}


		$sellable_datas = $this->get_sellable_print_sizes_by_id($photo_id);
//                $this->log($sellable_datas, 'sellable_datas');


		$combined_data = array();
		$count = 0;
		foreach ($sellable_datas as $sellable_data) {
			if (isset($sellable_data['CurrentPrintData']['available']) && $sellable_data['CurrentPrintData']['available'] == '1') {
				$new_combined_data = array();
				$new_combined_data = $sellable_data['CurrentPrintData'];
				$new_combined_data['print_type'] = $sellable_data['PhotoPrintType']['print_name'];
				$new_combined_data['print_type_id'] = $sellable_data['PhotoPrintType']['id'];
				$new_combined_data['photo_avail_sizes_photo_print_type_id'] = $sellable_data['PrintTypeJoin']['id'];
				$combined_data[$sellable_data['PhotoPrintType']['print_name']]['print_type_id'] = $sellable_data['PhotoPrintType']['id'];
				$combined_data[$sellable_data['PhotoPrintType']['print_name']]['items'][] = $new_combined_data;

				$count++;
			}
		}


//		return $sellable_datas;
		return $combined_data;
	}

	public function get_sellable_print_sizes_by_id($photo_id, $photo_print_type_id = null) {
		////////////////////////////////////////////////////////////////////////////////////////////////////
		// get extra conditions based on photo limit
//		$max_photo_id = $this->get_last_photo_id_based_on_limit();
//		if (!empty($max_photo_id) && $photo_id > $max_photo_id) {
//			$this->major_error('Called get_sellable_print_sizes_by_id on limited photo', compact('photo_id'));
//			return array();
//		}

		$photo = $this->find('first', array(
			'conditions' => array(
				'Photo.id' => $photo_id
			),
			'contain' => false
		));


		$this->add_photo_format($photo);

		return $this->get_sellable_print_sizes($photo, $photo_print_type_id);
	}

	/**
	 * This the size based on the default data - and also the overriddable data
	 * 
	 * @param type $photo
	 * @return type
	 */
	public function get_sellable_print_sizes($photo, $photo_print_type_id = null) {
		$where_clause = "
                    WHERE 
                        PrintTypeJoin.available = 1
		";
		if (isset($photo_print_type_id)) {
                    $photo_print_type_id = (int) $photo_print_type_id;
                    $where_clause .= "
                            AND PhotoPrintType.id = :photo_print_type_id
                    ";
		}
		$photo_sellable_print_query = "
                    SELECT * FROM photo_avail_sizes_photo_print_types AS PrintTypeJoin
                            LEFT JOIN photo_print_types AS PhotoPrintType
                                    ON (PhotoPrintType.id = PrintTypeJoin.photo_print_type_id)
                            LEFT JOIN photo_avail_sizes AS PhotoAvailSize
                                    ON (PhotoAvailSize.id = PrintTypeJoin.photo_avail_size_id)
                            LEFT JOIN photo_sellable_prints AS PhotoSellablePrint
                                    ON (PhotoSellablePrint.photo_avail_sizes_photo_print_type_id = PrintTypeJoin.id AND PhotoSellablePrint.photo_id = :photo_id)
                            $where_clause
                    ORDER BY PhotoPrintType.order, PhotoAvailSize.short_side_length
		";
		$this->PhotoAvailSizesPhotoPrintType = ClassRegistry::init('PhotoAvailSizesPhotoPrintType');
		$photo_sellable_prints = $this->PhotoAvailSizesPhotoPrintType->query($photo_sellable_print_query, array(
                    'photo_id' => $photo['Photo']['id'],
                    'photo_print_type_id' => $photo_print_type_id,
		));
                
                
                
                

		/////////////////////////////////////
		// set the current values
		foreach ($photo_sellable_prints as &$photo_sellable_print) {
                    $photo_sellable_print['CurrentPrintData'] = array();
                    
                    //////////////////////////////////////////////////////
                    // add in the overlord print fulfiller data
                    if ($photo_sellable_print['PhotoPrintType']['print_fulfillment_type'] != 'self') {
                        $photo_sellable_print['CurrentPrintData']['print_fulfiller'] = $this->overlord_account_info['print_fulfillers_indexed'][$photo_sellable_print['PhotoPrintType']['print_fulfiller_id']];
                        $photo_sellable_print['CurrentPrintData']['print_fulfiller_print_type'] = $photo_sellable_print['CurrentPrintData']['print_fulfiller']['PrintFulfillerPrintType'][$photo_sellable_print['PhotoPrintType']['print_fulfiller_print_type_id']];
                        if (!empty($photo_sellable_print['PrintTypeJoin']['print_fulfiller_print_type_fixed_size_id'])) {
                            $photo_sellable_print['CurrentPrintData']['print_fulfiller_print_type_fixed_size'] = $photo_sellable_print['CurrentPrintData']['print_fulfiller_print_type']['PrintFulfillerPrintTypeFixedSize'][$photo_sellable_print['PrintTypeJoin']['print_fulfiller_print_type_fixed_size_id']];
                        }
                        unset($photo_sellable_print['CurrentPrintData']['print_fulfiller_print_type']['PrintFulfillerPrintTypeFixedSize']);
                        unset($photo_sellable_print['CurrentPrintData']['print_fulfiller']['PrintFulfillerPrintType']);
                    }
                    
                    //////////////////////////////////////////////////////////////////////////////////////
                    // decide if the photo should use overridden settings or the global settings
                    $use_photo_override = !empty($photo_sellable_print['PhotoSellablePrint']['override_for_photo']) && $photo_sellable_print['PrintTypeJoin']['force_defaults'] !== '1';
                    
                    
                    ////////////////////////////////////////////////////////
                    // add in data based on the fixed, dynamic, self
                    //   ie - price, cost, short_side_length, long_side_length
                    //      - sq_inches
                    $photo_sellable_print['CurrentPrintData'] += $this->get_extra_current_print_data($photo_sellable_print, $photo, $use_photo_override);
                    
                    
                    $this->log($photo_sellable_print['CurrentPrintData'], 'CurrentPrintData');
                    
                    
                    
                    /////////////////////////////////////////////////////////////////////////
                    // if the settings are overridden for the current photo and force settings for size are not set
                    //    -- use the overridden values
                    // otherwise
                    //    -- use the settings for the print size
                    if ($use_photo_override) {
                        $photo_sellable_print['CurrentPrintData']['available'] = isset($photo_sellable_print['PhotoSellablePrint']['available']) ? $photo_sellable_print['PhotoSellablePrint']['available'] : $photo_sellable_print['PrintTypeJoin']['default_available'];
                        $photo_sellable_print['CurrentPrintData']['handling_price'] = isset($photo_sellable_print['PhotoSellablePrint']['shipping_price']) ? $photo_sellable_print['PhotoSellablePrint']['shipping_price'] : $photo_sellable_print['PrintTypeJoin']['shipping_price'];
                        $photo_sellable_print['CurrentPrintData']['custom_turnaround'] = (isset($photo_sellable_print['PhotoSellablePrint']['custom_turnaround']) && $photo_sellable_print['PhotoSellablePrint']['custom_turnaround'] != '') ? $photo_sellable_print['PhotoSellablePrint']['custom_turnaround'] : $photo_sellable_print['PrintTypeJoin']['custom_turnaround'];
                        $photo_sellable_print['CurrentPrintData']['override_for_photo'] = '1';
                    } else {
                        $photo_sellable_print['CurrentPrintData']['available'] = $photo_sellable_print['PrintTypeJoin']["global_default"];
                        $photo_sellable_print['CurrentPrintData']['handling_price'] = $photo_sellable_print['PrintTypeJoin']["handling_price"];
                        $photo_sellable_print['CurrentPrintData']['custom_turnaround'] = $photo_sellable_print['PrintTypeJoin']["custom_turnaround"];
                        $photo_sellable_print['CurrentPrintData']['override_for_photo'] = '0';
                    }

                    if (empty($photo_sellable_print['CurrentPrintData']['custom_turnaround'])) {
                        $photo_sellable_print['CurrentPrintData']['custom_turnaround'] = $photo_sellable_print['PhotoPrintType']['turnaround_time'];
                    }
		}


		return $photo_sellable_prints;
	}
        
        public function get_extra_current_print_data($photo_sellable_print, $photo, $photo_override = false) {
            if ($photo_override) {
                $price = $photo_sellable_print['PrintTypeJoin']["price"];
            } else {
                $price = isset($photo_sellable_print['PhotoSellablePrint']['price']) ? $photo_sellable_print['PhotoSellablePrint']['price'] : $photo_sellable_print['PrintTypeJoin']["price"];
            }
            
            switch($photo_sellable_print['PrintTypeJoin']['photo_print_type']) {
                case "autofixed":
                    $short_side_inches = $photo_sellable_print['CurrentPrintData']['print_fulfiller_print_type_fixed_size']['short_side_inches'];
                    $long_side_inches = $photo_sellable_print['CurrentPrintData']['print_fulfiller_print_type_fixed_size']['long_side_inches'];
                    $sq_inches = $short_side_inches * $long_side_inches;
                    $cost = $photo_sellable_print['CurrentPrintData']['print_fulfiller_print_type_fixed_size']['cost'];
                    break;
                case "autodynamic":
                    $short_side_inches = $photo_sellable_print['PhotoAvailSize']['short_side_length'];
                    $long_side_inches = $this->get_long_side_length($photo, $photo_sellable_print['PhotoAvailSize']['short_side_length']);
                    $sq_inches = $short_side_inches * $long_side_inches;
                    $cost = $sq_inches * $photo_sellable_print['CurrentPrintData']['print_fulfiller_print_type']['dynamic_cost_sq_inch'];
                    break;
                case "self":
                    $short_side_inches = $photo_sellable_print['PhotoAvailSize']['short_side_length'];
                    $long_side_inches = $this->get_long_side_length($photo, $photo_sellable_print['PhotoAvailSize']['short_side_length']);
                    $sq_inches = $short_side_inches * $long_side_inches;
                    $cost = 0;
                    break;
            }
            
            $long_side_feet_inches = $this->decimalToFraction($long_side_inches);
            
            return compact('price', 'cost', 'short_side_inches', 'long_side_inches', 'long_side_feet_inches', 'sq_inches');
        }

	public function get_extra_print_data($photo_id, $photo_print_type_id, $short_side_inches) {
		$sellable_print_sizes = $this->get_sellable_print_sizes_by_id($photo_id, $photo_print_type_id);
//		$this->log($sellable_print_sizes, 'sellable_print_sizes');

		$extra_print_data = array();
		foreach ($sellable_print_sizes as $key => $sellable_print_size) {
			if ($sellable_print_size['PhotoAvailSize']['short_side_length'] == $short_side_inches) {
				$extra_print_data = $sellable_print_sizes[$key];
				break;
			}
		}

		return $extra_print_data;
	}
        
	public function get_long_side_length($photo, $short_side_length) {
		$width = $photo['Photo']['pixel_width'];
		$height = $photo['Photo']['pixel_height'];

		$format_ref_name = isset($photo['PhotoFormat']['ref_name']) ? $photo['PhotoFormat']['ref_name'] : $photo['Photo']['PhotoFormat']['ref_name'];

		if (in_array($format_ref_name, array('landscape', 'panoramic', 'square'))) {
			$start_short_side = $height;
			$start_long_side = $width;
		} else {
			$start_short_side = $width;
			$start_long_side = $height;
		}

		$short_side_inches = $short_side_length;
		$long_side_inches = ( $short_side_length * $start_long_side ) / $start_short_side;

		return $long_side_inches;

		// DREW TODO - also add a conversion from inches to meters and centimeters
//		$this->log('============================', 'get_long_side_length');
//		$this->log($format_ref_name, 'get_long_side_length');
//		$this->log($width, 'get_long_side_length');
//		$this->log($height, 'get_long_side_length');
//		$this->log($short_side_length, 'get_long_side_length');
//		$this->log($calc_long_side, 'get_long_side_length');
//		$this->log('============================', 'get_long_side_length');
	}

	function decimalToFraction($decimalInch) {
		if ($decimalInch < .03125) {
			return "";
		}

		// separate out decimal from whole number
		$pWhole = explode('.', $decimalInch);
		$pWhole = $pWhole[0];
		$pDecimal = $decimalInch - $pWhole;

		// create list of numbers to round to
		$fractionOption = array();
		$fractionOption['0/16'] = 0;
		$fractionOption['1/16'] = 0.0625;
		$fractionOption['1/8'] = 0.125;
		$fractionOption['3/16'] = 0.1875;
		$fractionOption['1/4'] = 0.25;
		$fractionOption['5/16'] = 0.3125;
		$fractionOption['3/8'] = 0.375;
		$fractionOption['7/16'] = 0.4375;
		$fractionOption['1/2'] = 0.5;
		$fractionOption['9/16'] = 0.5625;
		$fractionOption['5/8'] = 0.625;
		$fractionOption['11/16'] = 0.6875;
		$fractionOption['3/4'] = 0.75;
		$fractionOption['13/16'] = 0.8125;
		$fractionOption['7/8'] = 0.875;
		$fractionOption['15/16'] = 0.9375;
		$fractionOption['16/16'] = 1;

		// find closest number to round to
		foreach ($fractionOption as $k => $v) {
			$tmpV[$k] = abs($pDecimal - $v);
		}
		asort($tmpV, SORT_NUMERIC);
		list($inch, $decimal) = each($tmpV);

		// clean up for edge values
		$inch = ($inch == '0/16') ? '' : $inch;
		// round off to nearest whole number if 16/16
		if ($inch == '16/16') {
			$inch = '';
			$pWhole++;
		}

		// strip inch and return fraction formatted in css
		$finalText = "";
		if ($inch != '') {
			$tFrac = explode('/', $inch);
			$fraction = "<span style=\"font-size: 75%; vertical-align: .5ex;\">$tFrac[0]</span>&#8260;<span style=\"font-size: 75%;\">$tFrac[1]</span>";
			$finalText .= $pWhole . ' ' . $fraction . "\" ";
		} else {
			$fraction = '';
			$finalText .= $pWhole . "\" ";
		}

		return $finalText;
	}

	public function count_total_photos($cache = false) {
		if ($cache === true) {
			if (apc_exists($this->photos_count_apc_key)) {
				return apc_fetch($this->photos_count_apc_key);
			}
		}


		$total = $this->find('count');
		if ($cache === true) {
			apc_store($this->photos_count_apc_key, $total, 10800); // 3 hours
		}

		return $total;
	}
	
	
	public function get_total_photo_used_space($cache = false) {
		if ($cache === true) {
			if (apc_exists($this->photos_space_apc_key)) {
				return apc_fetch($this->photos_space_apc_key);
			}
		}


		$sum_query = "SELECT SUM(`file_size`) as used_space_megabytes FROM photos;";
		$sum_result = $this->query($sum_query);
		$used_space_megabytes = 0;
		if (isset($sum_result[0][0]['used_space_megabytes'])) {
			$used_space_megabytes = $sum_result[0][0]['used_space_megabytes'];
		}
		
		if ($cache === true) {
			apc_store($this->photos_space_apc_key, $used_space_megabytes, 10800); // 3 hours
		}

		return $used_space_megabytes;
	}
	
	public function get_max_photo_space() {
		if (empty($GLOBALS['current_on_off_features']['unlimited_storage'])) {
			return MAX_MEGEBYTES_SPACE;
		} else {
			return MAX_UPPER_LIMIT_MEGEBYTES_SPACE;
		}
	}

}
