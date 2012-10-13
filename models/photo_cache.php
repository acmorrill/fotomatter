<?php
/***
 * testing notes
 * 1) make sure there are no queed photo caches that are old
 * 2) make sure there are no processing photo caches that are old
 * 3) see if there are any failed photo caches
 * 4) test that if you upload a small original a larger cache file will still work (upres)
 * 5) photo caches must have a max_width and max_height set if either are set (or both null cus is queued)
 */
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
	
	
	public function get_dummy_error_image_path($height, $width, $direct_output = false) {
		$image_name = 'photo_404.jpg';
		$dummy_image_path = ROOT.DS.APP_DIR.DS.'webroot/img/photo_default/'.$image_name;
		$dummy_image_url_path = '/img/photo_default/'.$image_name;
		$cache_path = ROOT.DS.APP_DIR.DS.'webroot/img/photo_default/caches';
		$url_cache_path = '/img/photo_default/caches';
		
		return $this->get_dummy_image_path($height, $width, $image_name, $dummy_image_path, $dummy_image_url_path, $cache_path, $url_cache_path, $direct_output);
	}
	
	public function get_dummy_processing_image_path($height, $width, $direct_output = false) {
		$image_name = 'photo_processing.jpg';
		$dummy_image_path = ROOT.DS.APP_DIR.DS.'webroot/img/photo_default/'.$image_name;
		$dummy_image_url_path = '/img/photo_default/'.$image_name;
		$cache_path = ROOT.DS.APP_DIR.DS.'webroot/img/photo_default/caches';
		$url_cache_path = '/img/photo_default/caches';
		
		return $this->get_dummy_image_path($height, $width, $image_name, $dummy_image_path, $dummy_image_url_path, $cache_path, $url_cache_path, $direct_output);
	}
	
	private function get_dummy_image_path($height, $width, $image_name, $dummy_image_path, $dummy_image_url_path, $cache_path, $url_cache_path, $direct_output) { 
		$bothEmpty = empty($height) && empty($width);
		$onlyWidth = !empty($width) && empty($height);
		$onlyHeight = empty($width) && !empty($height);
		$bothSet = !empty($width) && !empty($height);
		
		// return the full photo path
		if ($bothEmpty) {
			if ($direct_output == false) {
				return $dummy_image_url_path;
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
			
			if ($this->convert($dummy_image_path, $image_path, $width, $height) == false) {
				$this->major_error('failed to create dummy image cache in get_dummy_image_path', array($dummy_image_path, $image_path, $width, $height));
			}
		}
		
		if ($direct_output == false) {
			return $url_image_path;
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
	
	public function get_full_path($id) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		$photo_cache = $this->find('first', array(
			'conditions' => array('PhotoCache.id' => $id),
			'contain' => false,
			'fields' => array('PhotoCache.cdn-filename')
		));
               
		return $this->SiteSetting->getImageContainerUrl().$photo_cache['PhotoCache']['cdn-filename'];
	}
	
	public function prepare_new_cachesize($photo_id, $height, $width, $raw_id = false) {
		$data['PhotoCache']['photo_id'] = $photo_id;
		$data['PhotoCache']['max_height'] = $height;
		$data['PhotoCache']['max_width'] = $width;
		$data['PhotoCache']['status'] = 'queued';
		
		$this->create();
		if ($this->save($data) == false) {
			$this->major_error('failed to prepare new cache size', $data);
			return false;
		} else {
			if ($raw_id == true) {
				return $this->id;
			} else {
				return '/photo_caches/create_cache/'.$this->id.'/';
			}
		}
	}
	
	public function get_existing_cache_create_url($photo_cache_id) {
		return '/photo_caches/create_cache/'.$photo_cache_id.'/';
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

		if ($direct_output && $photoCache['PhotoCache']['status'] == 'ready') {
			$cache_full_path = $this->get_full_path($photoCache['PhotoCache']['id']);
			
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
			return;
		}
		
		$photo_cache_id = $photoCache['PhotoCache']['id'];
		$initLocked = $this->query("SELECT GET_LOCK('finish_create_cache_".$photo_cache_id."', 8)");
		if ($initLocked['0']['0']["GET_LOCK('finish_create_cache_".$photo_cache_id."', 8)"] == 0 || $initLocked['0']['0']["GET_LOCK('finish_create_cache_".$photo_cache_id."', 8)"] == null) {
			if ( !empty($photoCache['PhotoCache']['max_height']) || !empty($photoCache['PhotoCache']['max_width']) ) {
				return $this->get_dummy_processing_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], $direct_output);
			} else {
				return;
			}
		}


		if ($photoCache['PhotoCache']['status'] != 'queued') {
			$this->query("SELECT RELEASE_LOCK('finish_create_cache_".$photo_cache_id."')");
			if ( !empty($photoCache['PhotoCache']['max_height']) || !empty($photoCache['PhotoCache']['max_width']) ) {
				return $this->get_dummy_processing_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], $direct_output);
			} else {
				return;
			}
		}

		$cache_prefix = 'cache_';
		$photoCache['PhotoCache']['status'] = 'processing';
		$this->save($photoCache);
		$releaseLock = $this->query("SELECT RELEASE_LOCK('finish_create_cache_".$photo_cache_id."')");
		
		
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
			$this->major_error('Tried to create a cache file for a photo with no image attached', $photoCache);
			if ( !empty($photoCache['PhotoCache']['max_height']) || !empty($photoCache['PhotoCache']['max_width']) ) {
				$photoCache['PhotoCache']['status'] = 'failed';
				$this->save($photoCache);
				return $this->get_dummy_error_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], $direct_output);
			} 
			
			return;
		}

		
		if (is_writable(TEMP_IMAGE_PATH)) {
			if ($photoCache['PhotoCache']['max_height'] <= SMALL_MASTER_CACHE_SIZE && $photoCache['PhotoCache']['max_width'] <= SMALL_MASTER_CACHE_SIZE) {
				if (!file_exists(LOCAL_SMALLER_MASTER_CACHE.DS.$photoCache['Photo']['cdn-filename-smaller-forcache'])) {
					copy( // from cloud files to local (only happens if not used for a while)
						ClassRegistry::init("SiteSetting")->getImageContainerUrl().$photoCache['Photo']['cdn-filename-smaller-forcache'], 
						LOCAL_SMALLER_MASTER_CACHE.DS.$photoCache['Photo']['cdn-filename-smaller-forcache']
					);
				} 
				
				$large_image_url = LOCAL_SMALLER_MASTER_CACHE.DS.$photoCache['Photo']['cdn-filename-smaller-forcache'];
			} else {
				if (!file_exists(LOCAL_MASTER_CACHE.DS.$photoCache['Photo']['cdn-filename-forcache'])) {
					copy( // from cloud files to local (only happens if not used for a while)
						ClassRegistry::init("SiteSetting")->getImageContainerUrl().$photoCache['Photo']['cdn-filename-forcache'], 
						LOCAL_MASTER_CACHE.DS.$photoCache['Photo']['cdn-filename-forcache']
					);
				} 
				
				$large_image_url = LOCAL_MASTER_CACHE.DS.$photoCache['Photo']['cdn-filename-forcache'];
			}
			
			
			
			$cache_image_name = $cache_prefix.$max_height_display.'x'.$max_width_display.'_'.$photoCache['Photo']['cdn-filename'];
			$new_cache_image_path = TEMP_IMAGE_PATH.DS.$cache_image_name;
                        
			
			if ($this->convert($large_image_url, $new_cache_image_path, $max_width, $max_height) == false) {
				$this->major_error('failed to create new cache file in finish_create_cache', array($large_image_url, $new_cache_image_path, $max_width, $max_height));
				$photoCache['PhotoCache']['status'] = 'failed';
				$this->save($photoCache);
				if ( !empty($photoCache['PhotoCache']['max_height']) || !empty($photoCache['PhotoCache']['max_width']) ) {
					return $this->get_dummy_error_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], $direct_output);
				} 
				
				return;
			}

			$newcache_size = getimagesize($new_cache_image_path);
			list($newcache_width, $newcache_height, $newcache_type, $newcache_attr) = $newcache_size;
			$newcache_mime = $newcache_size['mime'];
			
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
			$photoCache['PhotoCache']['status'] = 'ready';
			unset($photoCache['PhotoCache']['created']);
			unset($photoCache['PhotoCache']['modified']);
			unset($photoCache['Photo']);

			if (!$this->CloudFiles->put_object($cache_image_name, $new_cache_image_path, $newcache_mime)) {
				$this->major_error("failed to finish creating cache file", compact('photoCache', 'cache_image_name', 'new_cache_image_path', 'newcache_mime'));
				$photoCache['PhotoCache']['status'] = 'failed';
				unset($photoCache['PhotoCache']['pixel_width']);
				unset($photoCache['PhotoCache']['pixel_height']);
				unset($photoCache['PhotoCache']['cdn-filename']);
			}
			$this->save($photoCache);
			
			unlink($new_cache_image_path);
		} else {
			$this->major_error("the temp image path is not writable for image cache");
			return $this->get_dummy_error_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], true);
		}
	}
	
	private function get_cloud_file() {
		if (!isset($this->CloudFiles)) {
			App::import('Component','CloudFiles');
			$this->CloudFiles = new CloudFilesComponent();
		}
		
		return $this->CloudFiles;
	}
	
	public function convert($old_image_url, $new_image_path, $max_width, $max_height, $enlarge = true) {
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
		
		$imageMagickCommand = "convert $jpeg_define $filter $resize$enlarge_str ".escapeshellarg($old_image_url).' '.escapeshellarg($new_image_path).' ';
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