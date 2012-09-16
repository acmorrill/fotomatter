<?php
class ThemeLogo extends AppModel {
	public $name = 'ThemeLogo';
	public $useTable = false;
	
	
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
		
		if ($info['return_var'] != 0) {
			$this->major_error('image magick command failed', $info);
			
			return false;
		}
		
		return true;
	}
}