<?php
class PhotoCache extends AppModel {
	public $name = 'PhotoCache';
	public $belongsTo = array('Photo');
	
	public function beforeDelete() {
		parent::beforeDelete();
		
		$photo_cache = $this->find("first", array(
			"conditions" => array("PhotoCache.id" => $this->id),
			'contain' => false
		));
		
		if (isset($photo_cache['PhotoCache']['cdn-filename'])) {
			$this->CloudFiles = $this->get_cloud_file();
			
			if (!$this->CloudFiles->delete_object($photo_cache['PhotoCache']['cdn-filename'])) {
				$this->major_error("failed to delete object cdn-filename in photo cache before delete", $photo_cache);
			}
		}
		
		return true;
	}
	
	
	public function get_dummy_error_image_path($height, $width, $direct_output = false, $return_tag_attributes = false, $crop = false) {
		$image_name = 'photo_404.jpg';
		$dummy_image_path = ROOT.DS.APP_DIR.DS.'webroot/img/photo_default/'.$image_name;
		$dummy_image_url_path = '/img/photo_default/'.$image_name;
		$cache_path = ROOT.DS.APP_DIR.DS.'webroot/img/photo_default/caches';
		$url_cache_path = '/img/photo_default/caches';
		
		return $this->get_dummy_image_path($height, $width, $image_name, $dummy_image_path, $dummy_image_url_path, $cache_path, $url_cache_path, $direct_output, $return_tag_attributes, $crop);
	}
	
	public function get_dummy_processing_image_path($height, $width, $direct_output = false, $return_tag_attributes = false, $crop = false) {
		$image_name = 'photo_processing.jpg';
		$dummy_image_path = ROOT.DS.APP_DIR.DS.'webroot/img/photo_default/'.$image_name;
		$dummy_image_url_path = '/img/photo_default/'.$image_name;
		$cache_path = ROOT.DS.APP_DIR.DS.'webroot/img/photo_default/caches';
		$url_cache_path = '/img/photo_default/caches';
		
		return $this->get_dummy_image_path($height, $width, $image_name, $dummy_image_path, $dummy_image_url_path, $cache_path, $url_cache_path, $direct_output, $return_tag_attributes, $crop);
	}
	
	private function get_dummy_image_path($height, $width, $image_name, $dummy_image_path, $dummy_image_url_path, $cache_path, $url_cache_path, $direct_output, $return_tag_attributes = false, $crop = false) { 
		$bothEmpty = empty($height) && empty($width);
		$onlyWidth = !empty($width) && empty($height);
		$onlyHeight = empty($width) && !empty($height);
		$bothSet = !empty($width) && !empty($height);
		
		// return the full photo path
		if ($bothEmpty) {
			if ($direct_output == false) {
				if ($return_tag_attributes == true) {
					list($width, $height, $type, $tag_attributes) = getimagesize($dummy_image_path);
					
					return array(
						'url' => $dummy_image_url_path,
						'tag_attributes' => $tag_attributes,
						'style_attributes' => "width: {$width}px; height: {$height}px;",
						'alt_title_str' => "",
						'width' => $width,
						'height' => $height,
					);
				} else {
					return $dummy_image_url_path;
				}
			} else {
				$image_size = getimagesize($dummy_image_path);
				//list($image_width, $image_height, $image_type, $image_attr) = $image_size;
				$image_mime = $image_size['mime'];

				header('Content-Description: File Transfer');
				header("Content-type: $image_mime");
				header('Content-Disposition: attachment; filename='.basename($dummy_image_path));
				header('Content-Transfer-Encoding: binary');
				header('Pragma: public');
				header('Content-Length: ' . filesize($dummy_image_path));
				ob_clean();
				flush();
				readfile($dummy_image_path);
				exit();
			}
		} 
		// get a cache smaller than width
		else if ($onlyWidth) {
			$folder = $width;
			$height = '';
		} 
		// get a cache smaller than height
		else if ($onlyHeight) {
			$folder = 'x'.$height;
			$width = '';
		} 
		// get a cache smaller than width and height
		else if ($bothSet) {
			$folder = $width.'x'.$height;
		}
		
		
		$image_path = $cache_path.DS.$folder.DS.$image_name;
		$url_image_path = $url_cache_path.DS.$folder.DS.$image_name;
		
		if (!file_exists($image_path)) {
			if (!is_dir($cache_path.DS.$folder)) {
				mkdir($cache_path.DS.$folder, 0775);
			}
			
			if ($this->convert($dummy_image_path, $image_path, $width, $height, true, null, $crop) == false) {
				$this->major_error('failed to create dummy image cache in get_dummy_image_path', array($dummy_image_path, $image_path, $width, $height));
			}
		}
		
		if ($direct_output == false) {
			if ($return_tag_attributes == true) {
				list($width, $height, $type, $tag_attributes) = getimagesize($image_path);

				return array(
					'url' => $url_image_path,
					'tag_attributes' => $tag_attributes,
					'style_attributes' => "width: {$width}px; height: {$height}px;",
					'alt_title_str' => "",
					'width' => $width,
					'height' => $height,
				);
			} else {
				return $url_image_path;
			}
		} else {
			$image_size = getimagesize($image_path);
			//list($image_width, $image_height, $image_type, $image_attr) = $image_size;
			$image_mime = $image_size['mime'];
			
			header('Content-Description: File Transfer');
			header("Content-type: $image_mime");
			header('Content-Disposition: attachment; filename='.basename($image_path));
			header('Content-Transfer-Encoding: binary');
			header('Pragma: public');
			header('Content-Length: ' . filesize($image_path));
			ob_clean();
			flush();
			readfile($image_path);
			exit();
		}
	}
	
	public function get_full_path($id, $return_tag_attributes = false, $force_ssl = null) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		$photo_cache = $this->find('first', array(
			'conditions' => array('PhotoCache.id' => $id),
			'contain' => false,
			'fields' => array('PhotoCache.cdn-filename', 'PhotoCache.tag_attributes', 'PhotoCache.pixel_width', 'PhotoCache.pixel_height')
		));
               
		if ($return_tag_attributes === true) {
			return array(
				'url' => $this->SiteSetting->getImageContainerUrl($force_ssl).$photo_cache['PhotoCache']['cdn-filename'],
				'tag_attributes' => $photo_cache['PhotoCache']['tag_attributes'],
				'style_attributes' => "width: {$photo_cache['PhotoCache']['pixel_width']}px; height: {$photo_cache['PhotoCache']['pixel_height']}px;",
				'width' => $photo_cache['PhotoCache']['pixel_width'],
				'height' => $photo_cache['PhotoCache']['pixel_height'],
			);
		} else {
			return $this->SiteSetting->getImageContainerUrl($force_ssl).$photo_cache['PhotoCache']['cdn-filename'];
		}
	}
	
	public function prepare_new_cachesize($photo_id, $height, $width, $raw_id = false, $unsharp_amount = 0, $return_tag_attributes = false, $crop = false) {
		$data['PhotoCache']['photo_id'] = $photo_id;
		$data['PhotoCache']['max_height'] = $height;
		$data['PhotoCache']['max_width'] = $width;
		$data['PhotoCache']['crop'] = ($crop === true) ? 1 : 0;
		$data['PhotoCache']['status'] = 'queued';
		if (isset($unsharp_amount)) {
			$data['PhotoCache']['unsharp_amount'] = $unsharp_amount;
		}
		
		$this->create();
		if ($this->save($data) == false) {
			$this->major_error('failed to prepare new cache size', $data);
			return false;
		} else {
			if ($raw_id == true) {
				return $this->id;
			} else {
				if ($return_tag_attributes === true) {
					$calculated_data = $this->predict_cache_tag_attributes_for_photo_cache($this->id);
					return array(
						'url' => '/photo_caches/create_cache/'.$this->id."/?firsttime=true",
						'tag_attributes' => $calculated_data['tag_attributes'],
						'style_attributes' => "width: {$calculated_data['width']}px; height: {$calculated_data['height']}px;",
						'width' => $calculated_data['width'],
						'height' => $calculated_data['height'],
					);
				} else {
					return '/photo_caches/create_cache/'.$this->id.'/?firsttime=true';
				}
			}
		}
	}
	
	public function predict_cache_tag_attributes_for_photo_cache($photo_cache_id) {
		$curr_photo_cache = $this->find('first', array(
			'conditions' => array(
				'PhotoCache.id' => $photo_cache_id
			),
			'contain' => array(
				'Photo'
			)
		));
		
		
		// if the cache is cropped then just return the easily calculated size
		if ($curr_photo_cache['PhotoCache']['crop'] == '1') {
			return array(
				'tag_attributes' =>	'width="'.$curr_photo_cache['PhotoCache']['max_width'].'" height="'.$curr_photo_cache['PhotoCache']['max_height'].'"',
				'style_attributes' => "width: {$curr_photo_cache['PhotoCache']['max_width']}px; height: {$curr_photo_cache['PhotoCache']['max_height']}px;",
				'width' => $curr_photo_cache['PhotoCache']['max_width'],
				'height' => $curr_photo_cache['PhotoCache']['max_height'],
			);
		}
		
		
		$forcache_pixel_width = $curr_photo_cache['Photo']['forcache_pixel_width'];
		$forcache_pixel_height = $curr_photo_cache['Photo']['forcache_pixel_height'];
		$max_cache_width = $curr_photo_cache['PhotoCache']['max_width'];
		$max_cache_height = $curr_photo_cache['PhotoCache']['max_height'];
		
//		debug($forcache_pixel_width);
//		debug($forcache_pixel_height);
//		debug($max_cache_width);
//		debug($max_cache_height);
		
		$W_width = $max_cache_width;
		$W_height = round(($W_width * $forcache_pixel_height) / $forcache_pixel_width);
		$H_height = $max_cache_height;
		$H_width = round(($H_height * $forcache_pixel_width) / $forcache_pixel_height);
		
		$use_height = ($H_height * $H_width) < ($W_width * $W_height);
		
		if ($use_height) {
			return array(
				'tag_attributes' =>	'width="'.$H_width.'" height="'.$H_height.'"',
				'style_attributes' => "width: {$H_width}px; height: {$H_height}px;",
				'width' => $H_width,
				'height' => $H_height,
			);
		} else {
			return array(
				'tag_attributes' =>	'width="'.$W_width.'" height="'.$W_height.'"',
				'style_attributes' => "width: {$W_width}px; height: {$W_height}px;",
				'width' => $W_width,
				'height' => $W_height,
			);
		}
	}
	
	public function get_existing_cache_create_url($photo_cache_id, $return_tag_attributes = false) {
		if ($return_tag_attributes === true) {
			$calculated_data = $this->predict_cache_tag_attributes_for_photo_cache($photo_cache_id);
			return array(
				'url' => '/photo_caches/create_cache/'.$photo_cache_id.'/?firsttime=false',
				'tag_attributes' => $calculated_data['tag_attributes'],
				'style_attributes' => "width: {$calculated_data['width']}px; height: {$calculated_data['height']}px;",
				'width' => $calculated_data['width'],
				'height' => $calculated_data['height'],
			);
		} else {
			return '/photo_caches/create_cache/'.$photo_cache_id.'/?firsttime=false';
		}
	}
	
	public function finish_create_cache($photocache_id, $direct_output = true) {
		$photoCache = $this->find('first', array(
			'conditions' => array(
				'PhotoCache.id' => $photocache_id
			),
			'contain' => array(
				'Photo'
			)
		));
		
		if (!$photoCache) {
			$this->major_error('got into finish_create_cache and the photo cache file was invalid');
			return;
		}

		$this->invalidate_and_clear_view_cache();
		
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// if the photo cache is already done then just return it here
		if ($direct_output && $photoCache['PhotoCache']['status'] == 'ready') {
			$cache_full_path = $this->get_full_path($photoCache['PhotoCache']['id'], false, 'nonssl');
			
			if (empty($cache_full_path)) {
				$this->major_error('finish create cache ready and direct output full path empty', compact('cache_full_path', 'photocache_id', 'photoCache'));
				return $this->get_dummy_processing_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], $direct_output, false, $photoCache['PhotoCache']['crop']);
			}
			
			$cache_full_path_size = getimagesize($cache_full_path);
			$cache_full_path_mime = $cache_full_path_size['mime'];
			
			//header('Content-Description: File Transfer');
			header("Content-type: $cache_full_path_mime");
			//header('Content-Disposition: attachment; filename='.basename($new_cache_image_path));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			//header('Content-Length: ' . filesize($new_cache_image_path));
			ob_clean();
			flush();
			readfile($cache_full_path);
			return;
		}
		
		$photo_cache_id = $photoCache['PhotoCache']['id'];
		$initLocked = $this->get_lock("finish_create_cache_".$photo_cache_id . "_" . $_SERVER['local']['database'], 8);
		if ($initLocked === false) {
			// DREW TODO - should maybe put a major_error here
			if ( !empty($photoCache['PhotoCache']['max_height']) || !empty($photoCache['PhotoCache']['max_width']) ) {
				return $this->get_dummy_processing_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], $direct_output, false, $photoCache['PhotoCache']['crop']);
			} else {
				return;
			}
		}


		if ($photoCache['PhotoCache']['status'] != 'queued') {
			$releaseLock = $this->release_lock("finish_create_cache_".$photo_cache_id . "_" . $_SERVER['local']['database']);
			if ( !empty($photoCache['PhotoCache']['max_height']) || !empty($photoCache['PhotoCache']['max_width']) ) {
				return $this->get_dummy_processing_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], $direct_output, false, $photoCache['PhotoCache']['crop']);
			} else {
				return;
			}
		}

		$cache_prefix = 'cache_';
		$photoCache['PhotoCache']['status'] = 'processing';
		$this->save($photoCache);
		$releaseLock = $this->release_lock("finish_create_cache_".$photo_cache_id . "_" . $_SERVER['local']['database']);
		
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// after this point need to reset the photo cache status on fail of anything
		
		
		// TODO - these may not be necessary anymore (cus height and width are both requered to be set)
		$max_height_set = isset($photoCache['PhotoCache']['max_height']);
		$max_width_set = isset($photoCache['PhotoCache']['max_width']);
		$max_height = isset($photoCache['PhotoCache']['max_height']) ? $photoCache['PhotoCache']['max_height'] : 0;
		$max_width = isset($photoCache['PhotoCache']['max_width']) ? $photoCache['PhotoCache']['max_width'] : 0;
		$max_height_display = isset($photoCache['PhotoCache']['max_height']) ? $photoCache['PhotoCache']['max_height'] : 'null';
		$max_width_display = isset($photoCache['PhotoCache']['max_width']) ? $photoCache['PhotoCache']['max_width'] : 'null';

		if (!isset($this->CloudFiles)) {
			App::import('Component','CloudFiles');
			$this->CloudFiles = new CloudFilesComponent();
		}
		
		
		if (empty($photoCache['Photo']['cdn-filename-forcache']) || empty($photoCache['Photo']['cdn-filename'])) {
			// DREW TODO - need to delete the photo in this case
			$this->major_error('Tried to create a cache file for a photo with no image attached', $photoCache);
			if ( !empty($photoCache['PhotoCache']['max_height']) || !empty($photoCache['PhotoCache']['max_width']) ) {
				$photoCache['PhotoCache']['status'] = 'failed';
				$this->save($photoCache);
				return $this->get_dummy_error_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], $direct_output, false, $photoCache['PhotoCache']['crop']);
			} 
			
			return;
		}

		$container_url = ClassRegistry::init("SiteSetting")->getImageContainerUrl();
		if ($photoCache['Photo']['is_globally_shared'] == 1) {
			$container_url = ClassRegistry::init("SiteSetting")->get_site_default_container_url();
		}
		
		if (is_writable(TEMP_IMAGE_PATH)) {
			
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// get the path to the local cached version of the image
			//------------------------------------------------------------------------------------------------------------
			$use_smaller_master_cache = $photoCache['PhotoCache']['max_height'] <= SMALL_MASTER_CACHE_SIZE && $photoCache['PhotoCache']['max_width'] <= SMALL_MASTER_CACHE_SIZE;
			if ($use_smaller_master_cache) {
				$local_smaller_master_path = LOCAL_SMALLER_MASTER_CACHE.DS.$photoCache['Photo']['cdn-filename-smaller-forcache'];
				if (!file_exists($local_smaller_master_path)) {
					$cdn_filename_smaller_forcache = $container_url.$photoCache['Photo']['cdn-filename-smaller-forcache'];
					
					if (copy( // from cloud files to local (only happens if not used for a while)
						$cdn_filename_smaller_forcache, 
						$local_smaller_master_path
					) !== true) {
						$this->major_error('failed to copy smaller cdn forcache into local smaller master cache', compact('cdn_filename_smaller_forcache',  'local_smaller_master_path', 'new_cache_image_path', 'max_width', 'max_height'));
					}
				}
				
				$large_image_url = $local_smaller_master_path;
			} else {
				$local_larger_master_cache_path = LOCAL_MASTER_CACHE.DS.$photoCache['Photo']['cdn-filename-forcache'];
				if (!file_exists($local_larger_master_cache_path)) {
					$cdn_filename_forcache = $container_url.$photoCache['Photo']['cdn-filename-forcache'];
					
					if (copy( // from cloud files to local (only happens if not used for a while)
						$cdn_filename_forcache, 
						$local_larger_master_cache_path
					) !== true) {
						$this->major_error('failed to copy larger cdn forcache into local larger master cache', compact('cdn_filename_forcache',  'local_larger_master_cache_path', 'new_cache_image_path', 'max_width', 'max_height'));
					}
				} 
				
				$large_image_url = $local_larger_master_cache_path;
			}
			// end - get the path to the local cached version of the image
			//------------------------------------------------------------------------------------
			
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// grab the crop to put into cdn-filename
			// - resize the larger cached image into the actual size actual version
			//----------------------------------------------------------------------------------------------
			$unsharp_amount = 0;
			if (isset($photoCache['PhotoCache']['unsharp_amount'])) {
				$unsharp_amount = $photoCache['PhotoCache']['unsharp_amount'];
			}
			$crop_str = ($photoCache['PhotoCache']['crop'] == '1') ? 'crop' : 'nocrop';
			$unsharp_str = "unsh$unsharp_amount";
			$cache_image_name = $cache_prefix.$max_height_display.'x'.$max_width_display."_".$crop_str."_".$unsharp_str."_".$photoCache['Photo']['cdn-filename'];
			$new_cache_image_path = TEMP_IMAGE_PATH.DS.$cache_image_name;
			$this->ThemePrebuildCacheSize = Classregistry::init('ThemePrebuildCacheSize');
			$this->ThemePrebuildCacheSize->increment_used_in_theme($max_width, $max_height, $photoCache['PhotoCache']['crop'], $unsharp_amount);
			if ($this->convert($large_image_url, $new_cache_image_path, $max_width, $max_height, true, $unsharp_amount, $photoCache['PhotoCache']['crop']) == false) {
				$this->major_error('failed to create new cache file in finish_create_cache', compact('large_image_url', 'new_cache_image_path', 'max_width', 'max_height'));
				$photoCache['PhotoCache']['status'] = 'failed';
				$this->save($photoCache);
				if ( !empty($photoCache['PhotoCache']['max_height']) || !empty($photoCache['PhotoCache']['max_width']) ) {
					return $this->get_dummy_error_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], $direct_output, false, $photoCache['PhotoCache']['crop']);
				} 
				
				return;
			}
			// end - resize the larger cached image into the actual size actual version
			//----------------------------------------------------------------------------------------------
			
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// get info on the newly created file
			//----------------------------------------------------------------------------------------------
			$newcache_size = getimagesize($new_cache_image_path);
			list($newcache_width, $newcache_height, $newcache_type, $newcache_attr) = $newcache_size;
			$newcache_mime = $newcache_size['mime'];
			// end - get info on the newly created file
			//----------------------------------------------------------------------------------------------
			
			
			if ($direct_output) {
				//header('Content-Description: File Transfer');
				header("Content-type: $newcache_mime");
				//header('Content-Disposition: attachment; filename='.basename($new_cache_image_path));
				header('Content-Transfer-Encoding: binary');
				//header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				//header('Content-Length: ' . filesize($new_cache_image_path));
				ob_clean();
				flush();
				readfile($new_cache_image_path);
			}

			$photoCache['PhotoCache']['pixel_width'] = $newcache_width;
			$photoCache['PhotoCache']['pixel_height'] = $newcache_height;
			$photoCache['PhotoCache']['cdn-filename'] = $cache_image_name;
			$photoCache['PhotoCache']['tag_attributes'] = $newcache_attr;
			$photoCache['PhotoCache']['style_attributes'] = "width: {$newcache_width}px; height: {$newcache_height}px;";
			$photoCache['PhotoCache']['status'] = 'ready';
			unset($photoCache['PhotoCache']['created']);
			unset($photoCache['PhotoCache']['modified']);
			unset($photoCache['Photo']);

			
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// actually upload the newly created file to cloud files
			//------------------------------------------------------------------------------------------
			if (!$this->CloudFiles->put_object($cache_image_name, $new_cache_image_path, $newcache_mime)) {
				$this->major_error("failed to finish creating cache file", compact('photoCache', 'cache_image_name', 'new_cache_image_path', 'newcache_mime'), 'low');
				unset($photoCache['PhotoCache']['pixel_width']);
				unset($photoCache['PhotoCache']['pixel_height']);
				unset($photoCache['PhotoCache']['cdn-filename']);
				$photoCache['PhotoCache']['status'] = 'queued'; // just going to try again next time - hopefully it will work!
				
				
				// the old stuff
//				$this->major_error("failed to finish creating cache file", compact('photoCache', 'cache_image_name', 'new_cache_image_path', 'newcache_mime'));
//				$photoCache['PhotoCache']['status'] = 'failed';
//				unset($photoCache['PhotoCache']['pixel_width']);
//				unset($photoCache['PhotoCache']['pixel_height']);
//				unset($photoCache['PhotoCache']['cdn-filename']);
			}
			// end - actually upload the newly created file to cloud files
			//------------------------------------------------------------------------------------------
			$this->save($photoCache);
			
			unlink($new_cache_image_path);
		} else {
			$this->major_error("the temp image path is not writable for image cache");
			return $this->get_dummy_error_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], true, false, $photoCache['PhotoCache']['crop']);
		}
	}
	
	public function cache_size_exists($photo_id, $width, $height, $crop, $unsharp) {
		$conditions = array(
			'PhotoCache.photo_id' => $photo_id,
			'PhotoCache.max_height' => $height,
			'PhotoCache.max_width' => $width,
			'PhotoCache.crop' => ($crop === true) ? 1 : 0,
			'PhotoCache.unsharp_amount' => $unsharp
		);

		$photoCache = $this->find('first', array(
			'conditions' => $conditions,
			'contain' => false
		));
		
		return $photoCache;
	}
	
	private function get_cloud_file() {
		if (!isset($this->CloudFiles)) {
			App::import('Component','CloudFiles');
			$this->CloudFiles = new CloudFilesComponent();
		}
		
		return $this->CloudFiles;
	}
	
	public function convert($old_image_url, $new_image_path, $max_width, $max_height, $enlarge = true, $unsharp_amount = 0, $crop = false) {
		/*App::import('Component', 'ImageVersion');
		$email = new ImageVersionComponent();
		$email->startup($controller);
		
		
		$smallThumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false,  'quality' => 100,  'size' => array(156, 156)));*/
		
		
		$use_speed = USE_CACHE_SPEED;
		$max_thumb_size = SMALL_MASTER_CACHE_SIZE;
		$sizeString = '';
		$resize = '';
		$jpeg_define = '';
		$filter = '';
		$bothEmpty = empty($max_height) && empty($max_width);
		$onlyWidth = !empty($max_width) && empty($max_height);
		$onlyHeight = empty($max_width) && !empty($max_height);
		$bothSet = !empty($max_width) && !empty($max_height);
		if ($bothEmpty) {
			return false;
		} else if ($onlyWidth) {
			if ($use_speed && $max_width < $max_thumb_size) {
				$resize = '-thumbnail';
				$jpeg_define = "-define jpeg:size=".($max_width*2);
				$filter = '-filter box';
			} else {
				$resize = '-resize';
			}
			$resize .= ' '.$max_width;
		} else if ($onlyHeight) {
			if ($use_speed && $max_height < $max_thumb_size) {
				$resize = '-thumbnail';
				$jpeg_define = "-define jpeg:size=x".($max_height*2);
				$filter = '-filter box';
			} else {
				$resize = '-resize';
			}
			$resize .= ' x'.$max_height;
		} else if ($bothSet) {
			if ($use_speed && $max_height < $max_thumb_size && $max_width < $max_thumb_size) {
				$resize = '-thumbnail';
				$jpeg_define = "-define jpeg:size=".($max_width*2)."x".($max_height*2);
				$filter = '-filter box';
			} else {
				$resize = '-resize';
			}
			$resize .= ' '.$max_width.'x'.$max_height;
		}
		
		$enlarge_str = '\>';
		if ($enlarge == true) {
			$enlarge_str = '';
		}
		
		$unsharp = '';
		if (!empty($unsharp_amount)) {
			$unsharp = "-unsharp 0x$unsharp_amount";
		}
		
		$crop_str = '';
		if ($crop == true) {
			$crop_str = " -gravity center -extent {$max_width}x{$max_height}";
			$enlarge_str = '^';
		}
		
		$imageMagickCommand = "convert $unsharp $jpeg_define $filter $resize$enlarge_str $crop_str ".escapeshellarg($old_image_url).' '.escapeshellarg($new_image_path).' ';
		$info = array();
		$info['output'] = array();
		$info['return_var'] = 0;
		exec($imageMagickCommand, $info['output'], $info['return_var']);
                
		if (is_file($new_image_path) == false) {
			$this->major_error("new image file not created");
		}
		
		if ($info['return_var'] != 0) {
			$this->major_error('image magick command failed', $info);
			
			return false;
		}
		
		return true;
	}
}