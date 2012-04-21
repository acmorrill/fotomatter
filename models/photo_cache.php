<?php
/***
 * testing notes
 * Make sure ignore user about works
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
			/*$imageMagickCommand = 'convert '.escapeshellarg($dummy_image_path).' -resize '.$folder.' '.escapeshellarg($image_path).' ';
			shell_exec($imageMagickCommand);*/
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
	
	public function prepare_new_cachesize($photo_id, $height, $width) {
		$data['PhotoCache']['photo_id'] = $photo_id;
		$data['PhotoCache']['max_height'] = $height;
		$data['PhotoCache']['max_width'] = $width;
		$data['PhotoCache']['status'] = 'queued';
		
		$this->create();
		if ($this->save($data) == false) {
			$this->major_error('failed to prepare new cache size', $data);
			return false;
		} else {
			return '/photo_caches/create_cache/'.$this->id.'/';
		}
	}
	
	public function finish_create_cache($photocache_id, $direct_output=true) {
		// TODO - maybe make the following code atomic
		
		$photoCache = $this->find('first', array(
			'conditions' => array(
				'PhotoCache.id' => $photocache_id
			),
			'contain' => array(
				'Photo'
			)
		));
		
		if (!$photoCache) {
			exit();
		}
		
		/*if ($photoCache['PhotoCache']['status'] == 'ready') {
			$cache_full_path = $this->get_full_path($photoCache['PhotoCache']['id']);
			
			header('Content-Description: File Transfer');
			header("Content-type: $newcache_mime");
			header('Content-Disposition: attachment; filename='.basename($new_cache_image_path));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($cache_full_path));
			ob_clean();
			flush();
			readfile($new_cache_image_path);
			exit();
		}*/
		
		if ($photoCache['PhotoCache']['status'] != 'queued') {
			if ( !empty($photoCache['PhotoCache']['max_height']) || !empty($photoCache['PhotoCache']['max_width']) ) {
				return $this->get_dummy_processing_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], $direct_output);
			} else {
				exit();
			}
		}
		
		$cache_prefix = 'cache_';
		$photoCache['PhotoCache']['status'] = 'processing';
		$this->save($photoCache);
		
		
		
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
			
			exit();
		}
		
		if (is_writable(TEMP_IMAGE_PATH)) {
			$large_image_url = ClassRegistry::init("SiteSetting")->getImageContainerUrl().$photoCache['Photo']['cdn-filename-forcache'];
			$cache_image_name = $cache_prefix.$max_height_display.'x'.$max_width_display.'_'.$photoCache['Photo']['cdn-filename'];
			$new_cache_image_path = TEMP_IMAGE_PATH.DS.$cache_image_name;

			
			if ($this->convert($large_image_url, $new_cache_image_path, $max_width, $max_height) == false) {
				$this->major_error('failed to create new cache file in finish_create_cache', array($large_image_url, $new_cache_image_path, $max_width, $max_height));
				$photoCache['PhotoCache']['status'] = 'failed';
				$this->save($photoCache);
				if ( !empty($photoCache['PhotoCache']['max_height']) || !empty($photoCache['PhotoCache']['max_width']) ) {
					return $this->get_dummy_error_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], $direct_output);
				} 
				
				exit();
			}
			/*$imageMagickCommand = 'convert '.escapeshellarg($large_image_url).' -resize '.$max_width.'x'.$max_height.' '.escapeshellarg($new_cache_image_path).' ';
			shell_exec($imageMagickCommand);*/

			$newcache_size = getimagesize($new_cache_image_path);
			list($newcache_width, $newcache_height, $newcache_type, $newcache_attr) = $newcache_size;
			$newcache_mime = $newcache_size['mime'];

			$photoCache['PhotoCache']['pixel_width'] = $newcache_width;
			$photoCache['PhotoCache']['pixel_height'] = $newcache_height;
			$photoCache['PhotoCache']['cdn-filename'] = $cache_image_name;
			$photoCache['PhotoCache']['tag_attributes'] = $newcache_attr;
			$photoCache['PhotoCache']['status'] = 'ready';
			unset($photoCache['PhotoCache']['created']);
			unset($photoCache['PhotoCache']['modified']);
			unset($photoCache['Photo']);

			if ($direct_output) {
				header('Content-Description: File Transfer');
				header("Content-type: $newcache_mime");
				header('Content-Disposition: attachment; filename='.basename($new_cache_image_path));
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($new_cache_image_path));
				ob_clean();
				flush();
				readfile($new_cache_image_path);
			}
			if (!$this->CloudFiles->put_object($cache_image_name, $new_cache_image_path, $newcache_mime)) {
				$this->major_error("failed to finish creating cache file", $photoCache);
				unset($photoCache['PhotoCache']['pixel_width']);
				unset($photoCache['PhotoCache']['pixel_height']);
				unset($photoCache['PhotoCache']['cdn-filename']);
				unset($photoCache['PhotoCache']['status']);
			}
			$this->save($photoCache);
			unlink($new_cache_image_path);
			exit();
			
		} else {
			$this->major_error("the temp image path is not writable for image cache");
			return $this->get_dummy_error_image_path($photoCache['PhotoCache']['max_height'], $photoCache['PhotoCache']['max_width'], true);
		}
		
		
		// the old way
		/*$handle = fopen($large_image_url, 'rb');
		
		
		$img = new Imagick();
		$img->readImageFile($handle);
		$this->log(" ");
		$this->log($img->getFormat(), 'finish_create_cache');
		$this->log($img->getImageLength(), 'finish_create_cache');
		$this->log($max_width, 'finish_create_cache');
		$this->log($max_height, 'finish_create_cache');
		$img->resizeImage($max_width, $max_height, imagick::FILTER_LANCZOS, 0.9, true); // TODO - make sure this is a good algorithm
		$this->log($cache_image_name, 'finish_create_cache');
		$this->log($img->getImageLength(), 'finish_create_cache');
		$this->log($img->getFormat(), 'finish_create_cache');
		$geo = $img->getImageGeometry();
		$photoCache['PhotoCache']['pixel_width'] = $geo['width'];
		$photoCache['PhotoCache']['pixel_height'] = $geo['height'];
		$photoCache['PhotoCache']['cdn-filename'] = $cache_image_name;
		$photoCache['PhotoCache']['status'] = 'ready';
		unset($photoCache['PhotoCache']['created']);
		unset($photoCache['PhotoCache']['modified']);
		unset($photoCache['Photo']);
		
		if (!$this->CloudFiles->put_object_resource($cache_image_name, $handle, $img->getImageLength(), $img->getFormat())) {
			$this->major_error("failed to finish creating cache file", $photoCache);
			unset($photoCache['PhotoCache']['pixel_width']);
			unset($photoCache['PhotoCache']['pixel_height']);
			unset($photoCache['PhotoCache']['cdn-filename']);
			unset($photoCache['PhotoCache']['status']);
		}
		$this->save($photoCache);
		$this->log($cache_image_name, 'finish_create_cache');
		$this->log($img->getImageLength(), 'finish_create_cache');
		$this->log($img->getFormat(), 'finish_create_cache');
		
		$image_blob = $img->getImageBlob();
		$outputtype = $img->getFormat();*/
	}
	
	private function get_cloud_file() {
		if (!isset($this->CloudFiles)) {
			App::import('Component','CloudFiles');
			$this->CloudFiles = new CloudFilesComponent();
		}
		
		return $this->CloudFiles;
	}
	
	public function convert($old_image_url, $new_image_path, $max_width, $max_height) {
		$imageMagickCommand = 'convert '.escapeshellarg($old_image_url).' -resize '.$max_width.'x'.$max_height.' '.escapeshellarg($new_image_path).' ';
		$info = array();
		$info['output'] = array();
		$info['return_var'] = 0;
		exec($imageMagickCommand, $info['output'], $info['return_var']);
		
		if ($info['return_var'] != 0) {
			$this->major_error('image magick command failed', $info);
			
			return false;
		}
		
		return true;
	}
}