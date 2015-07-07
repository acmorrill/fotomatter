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
		if (is_array($data['Photo']['cdn-filename']) && !empty($data['Photo']['cdn-filename']['tmp_name'])) {

			// fail if the file is greater than max upload size
			if (!isset($data['Photo']['cdn-filename']['size']) || $this->check_image_filesize($data['Photo']['cdn-filename']['size']) === false) {
				return sprintf(__("Image is bigger than the max size of %d megabytes", true), MAX_UPLOAD_SIZE_MEGS);
			}

			
			/////////////////////////////////////////////////////////////
			// make sure the image is actually an image
			if ($this->check_image_extension($data['Photo']['cdn-filename']['name']) === false) {
				$this->major_error("tried to upload a file without a jpeg extension");
				return __("Incorrect file type", true);
			}


			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// make sure image is not bigger than is allowed to upload - dimension wise
			$data['Photo']['cdn-filename']['tmp_name'] = $this->check_and_resize_image_dimensions($data['Photo']['cdn-filename']['tmp_name']);
			list($width, $height, $type, $attr) = getimagesize($data['Photo']['cdn-filename']['tmp_name']);

			

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
				}
			} else {
				$this->major_error("failed to put an object to cloud files on photo save", array($data['Photo']['cdn-filename'], $file_name, $tmp_location, $mime_type));
				unset($data['Photo']['cdn-filename']);
			}
		} else {
			unset($data['Photo']['cdn-filename']);
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
		
		return $path;
	}
	
	
	public function check_image_filesize($filesize) {
		$maxmegabytes = MAX_UPLOAD_SIZE_MEGS * 1024 * 1024;
		if ($filesize > $maxmegabytes) {
			return false;
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
			$all_cache_sizes = $this->PhotoPrebuildCacheSize->find('all', array(
				'contain' => false
			));
			foreach ($all_cache_sizes as $all_cache_size) {
				$lock_name = "start_create_cache_" . $this->id . "_" . $_SERVER['local']['database'];
				$initLocked = $this->get_lock($lock_name, 8);
				if ($initLocked === false) {
					continue;
				}

				$conditions = array(
					'PhotoCache.photo_id' => $this->id,
					'PhotoCache.max_height' => $all_cache_size['PhotoPrebuildCacheSize']['max_height'],
					'PhotoCache.max_width' => $all_cache_size['PhotoPrebuildCacheSize']['max_width'],
					'PhotoCache.crop' => 1,
				);

				$photoCache = $this->PhotoCache->find('first', array(
					'conditions' => $conditions,
					'contain' => false
				));
				if (!$photoCache) {
					$photo_cache_id = $this->PhotoCache->prepare_new_cachesize($this->id, $all_cache_size['PhotoPrebuildCacheSize']['max_height'], $all_cache_size['PhotoPrebuildCacheSize']['max_width'], true);
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

		return true;
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
				if (isset($photo['Photo'])) {
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

	public function get_photo_path($photo_id, $height, $width, $unsharp_amount = null, $return_tag_attributes = false, $crop = false) {
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


		$conditions = array(
			'PhotoCache.photo_id' => $photo_id,
			'PhotoCache.max_height' => $height,
			'PhotoCache.max_width' => $width,
			'PhotoCache.crop' => ($crop === true) ? 1 : 0,
		);


		$photoCache = $this->PhotoCache->find('first', array(
			'conditions' => $conditions,
			'contain' => false
		));
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
				$photoCache = $this->PhotoCache->find('first', array(
					'conditions' => $conditions,
					'contain' => false
				));

				if ($photoCache['PhotoCache']['status'] == 'queued') {
					// TODO - maybe return the prepare path if the status is queued and some time has passed
					// I don't think I need to do the TODO now that I've added locking to the finish create cache and this helper
					$return_url = $this->PhotoCache->get_existing_cache_create_url($photoCache['PhotoCache']['id'], $return_tag_attributes);
				} else {
					$return_url = $this->PhotoCache->get_dummy_error_image_path($height, $width, false, $return_tag_attributes, $crop);
				}

				$releaseLock = $this->release_lock("finish_create_cache_" . $photoCache['PhotoCache']['id'] . "_" . $_SERVER['local']['database']);
			}
		} else {
			$initLocked = $this->get_lock("start_create_cache_" . $photo_id . "_" . $_SERVER['local']['database'], 8);
			if ($initLocked === false) {
				return $this->PhotoCache->get_dummy_processing_image_path($height, $width, false, $return_tag_attributes, $crop);
			}
			// grab again after lock - to make sure we are not conflicting
			$photoCache = $this->PhotoCache->find('first', array(
				'conditions' => $conditions,
				'contain' => false
			));
			if (empty($photoCache)) {
				$return_url = $this->PhotoCache->prepare_new_cachesize($photo_id, $height, $width, false, $unsharp_amount, $return_tag_attributes, $crop);
			} else {
				$return_url = $this->PhotoCache->get_dummy_error_image_path($height, $width, false, $return_tag_attributes, $crop);
			}

			$releaseLock = $this->release_lock("start_create_cache_" . $photo_id . "_" . $_SERVER['local']['database']);
		}

		return preg_replace('/\s+/', '', $return_url);
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
		$join_format_requirment = 'non_pano';
		if ($this->photo_has_pano_format($photo)) {
			$join_format_requirment = 'pano';
		}
		$where_clause = "
			WHERE 
				PrintTypeJoin.{$join_format_requirment}_available = 1
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
		// set the default data
		foreach ($photo_sellable_prints as &$photo_sellable_print) {
			$photo_sellable_print['DefaultPrintData'] = $this->get_long_side_length($photo, $photo_sellable_print['PhotoAvailSize']['short_side_length']);
			$photo_sellable_print['DefaultPrintData']['default_available'] = $photo_sellable_print['PrintTypeJoin']["{$join_format_requirment}_global_default"];
			$photo_sellable_print['DefaultPrintData']['price'] = $photo_sellable_print['PrintTypeJoin']["{$join_format_requirment}_price"];
			$photo_sellable_print['DefaultPrintData']['shipping_price'] = $photo_sellable_print['PrintTypeJoin']["{$join_format_requirment}_shipping_price"];
			$photo_sellable_print['DefaultPrintData']['custom_turnaround'] = $photo_sellable_print['PrintTypeJoin']["{$join_format_requirment}_custom_turnaround"];
			$photo_sellable_print['DefaultPrintData']['force_defaults'] = $photo_sellable_print['PrintTypeJoin']["{$join_format_requirment}_force_settings"];
		}


		/////////////////////////////////////
		// set the current values
		foreach ($photo_sellable_prints as &$photo_sellable_print) {
			$photo_sellable_print['CurrentPrintData'] = $this->get_long_side_length($photo, $photo_sellable_print['PhotoAvailSize']['short_side_length']);
			if (!empty($photo_sellable_print['PhotoSellablePrint']['override_for_photo']) && $photo_sellable_print['DefaultPrintData']['force_defaults'] !== '1') {
				$photo_sellable_print['CurrentPrintData']['available'] = isset($photo_sellable_print['PhotoSellablePrint']['available']) ? $photo_sellable_print['PhotoSellablePrint']['available'] : $photo_sellable_print['DefaultPrintData']['default_available'];
				$photo_sellable_print['CurrentPrintData']['price'] = isset($photo_sellable_print['PhotoSellablePrint']['price']) ? $photo_sellable_print['PhotoSellablePrint']['price'] : $photo_sellable_print['DefaultPrintData']['price'];
				$photo_sellable_print['CurrentPrintData']['shipping_price'] = isset($photo_sellable_print['PhotoSellablePrint']['shipping_price']) ? $photo_sellable_print['PhotoSellablePrint']['shipping_price'] : $photo_sellable_print['DefaultPrintData']['shipping_price'];
				$photo_sellable_print['CurrentPrintData']['custom_turnaround'] = (isset($photo_sellable_print['PhotoSellablePrint']['custom_turnaround']) && $photo_sellable_print['PhotoSellablePrint']['custom_turnaround'] != '') ? $photo_sellable_print['PhotoSellablePrint']['custom_turnaround'] : $photo_sellable_print['DefaultPrintData']['custom_turnaround'];
				$photo_sellable_print['CurrentPrintData']['override_for_photo'] = '1';
			} else {
				$photo_sellable_print['CurrentPrintData']['available'] = $photo_sellable_print['DefaultPrintData']['default_available'];
				$photo_sellable_print['CurrentPrintData']['price'] = $photo_sellable_print['DefaultPrintData']['price'];
				$photo_sellable_print['CurrentPrintData']['shipping_price'] = $photo_sellable_print['DefaultPrintData']['shipping_price'];
				$photo_sellable_print['CurrentPrintData']['custom_turnaround'] = $photo_sellable_print['DefaultPrintData']['custom_turnaround'];
				$photo_sellable_print['CurrentPrintData']['override_for_photo'] = '0';
			}

			if (empty($photo_sellable_print['CurrentPrintData']['custom_turnaround'])) {
				$photo_sellable_print['CurrentPrintData']['custom_turnaround'] = $photo_sellable_print['PhotoPrintType']['turnaround_time'];
			}
		}


		return $photo_sellable_prints;
	}

	public function get_extra_print_data($photo_id, $photo_print_type_id, $short_side_inches) {
		$sellable_print_sizes = $this->get_sellable_print_sizes_by_id($photo_id, $photo_print_type_id);

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

		$long_side_feet_inches = $this->decimalToFraction($long_side_inches);

		return compact('short_side_inches', 'long_side_inches', 'long_side_feet_inches');

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

}
