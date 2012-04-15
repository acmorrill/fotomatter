<?php
class Photo extends AppModel {
	public $name = 'Photo';
	public $belongsTo = array('PhotoFormat');
	public $displayField = 'display_title';
	public $hasMany = array(        
		'PhotoCache' => array(            
			'dependent'=> true        
		),
		'PhotoGalleriesPhoto' => array(
			'order' => array(
				"PhotoGalleriesPhoto.photo_gallery_id" => 'asc'
			),
			'dependent' => true
		)
	);

	public function beforeDelete() {
		parent::beforeDelete();
		
		
		$photo = $this->find("first", array(
			"conditions" => array("Photo.id" => $this->id),
			'contain' => false
		));
		
		if (isset($photo['Photo']['cdn-filename'])) {
			$this->CloudFiles = $this->get_cloud_file();
			
			
			if (!$this->CloudFiles->delete_object($photo['Photo']['cdn-filename'])) {
				$this->major_error("failed to delete object cdn-filename in photo before delete", $photo['Photo']['cdn-filename']);
			}
		}
		
		if (isset($photo['Photo']['cdn-filename-forcache'])) {
			$this->CloudFiles = $this->get_cloud_file();
			
			if (!$this->CloudFiles->delete_object($photo['Photo']['cdn-filename-forcache'])) {
				$this->major_error("failed to delete object cdn-filename-forcache in photo before delete", $photo['Photo']['cdn-filename-forcache']);
			}
		}
		
		return true;
	}
	
	
	public function beforeSave($options = array()) {
		parent::beforeSave($options);
		
		$cacheTempLocation = '';
		$fiveMegabytes = 5242880;
		
		////////////////////////////////////////////////////////////////////////////////////////////
		// if a file was uploaded then upload it to cloud files and then delete any previous file
		if (!empty($this->data['Photo']['cdn-filename']['tmp_name'])) {
			// fail if the file is greater than 5 megs
			if (isset($this->data['Photo']['cdn-filename']['size']) && $this->data['Photo']['cdn-filename']['size'] > $fiveMegabytes) {
				return false;
			}
			
			// all the photo cache is now invalidated - so delete them if there were any
			if (isset($this->data['Photo']['id'])) {
				$this->PhotoCache->deleteAll(array(
					'PhotoCache.photo_id' => $this->data['Photo']['id']
				), true, true);
			}
			
			
			list($width, $height, $type, $attr) = getimagesize($this->data['Photo']['cdn-filename']['tmp_name']);
			
			
			$this->CloudFiles = $this->get_cloud_file();
			
			$file_name = $this->get_valid_filename($this->data['Photo']['cdn-filename']['name']);
			$tmp_location = $this->data['Photo']['cdn-filename']['tmp_name'];
			$mime_type = $this->data['Photo']['cdn-filename']['type'];

			
			if ($this->CloudFiles->put_object($file_name, $tmp_location, $mime_type)) {
				// file successfully uploaded - so now automatically set the photo format
				$this->data['Photo']['photo_format_id'] = $this->PhotoFormat->get_photo_format_id($height, $width);
				
				
				$this->data['Photo']['cdn-filename'] = $file_name;
				$this->data['Photo']['pixel_width'] = $width;
				$this->data['Photo']['pixel_height'] = $height;
				$this->data['Photo']['tag_attributes'] = $attr;
				
				// now remove the old cloud file if there was one
				if (isset($this->data['Photo']['id'])) {
					$oldPhoto = $this->find('first', array(
						'conditions' => array('Photo.id' => $this->data['Photo']['id']),
						'contain' => false
					));
					
					if ($oldPhoto && !empty($oldPhoto['Photo']['cdn-filename'])) {
						if (!$this->CloudFiles->delete_object($oldPhoto['Photo']['cdn-filename'])) {
							$this->major_error("failed to delete a cloud object in Photo beforeSave", $oldPhoto['Photo']);
						}
					}
				}
				
				//////////////////////////////////////////////////////////////////////////////////////////
				// now create a smaller version of the file (or bigger 1500x1500) for use in creating the cache files later
				if (is_writable(TEMP_IMAGE_PATH)) {
					$master_cache_prefix = 'mastercache_';
					$max_width = 1500; // TODO - read from a global setting
					$max_height = 1500;
					
					$new_image_url = ClassRegistry::init("SiteSetting")->getImageContainerUrl().$file_name;
					$cache_image_name = $master_cache_prefix.$file_name;
					
					$this->data['Photo']['cdn-filename-forcache'] = $cache_image_name;
					
					
					
					// TODO - check to make sure the photo url is pingable here
					// the command line image magick way
					$image_file_name = $this->random_num();
					$new_image_temp_path = TEMP_IMAGE_PATH.DS.$image_file_name;
					if ($this->PhotoCache->convert($new_image_url, $new_image_temp_path, $max_width, $max_height) == false) {
						$this->major_error('failed to create mastercache file in photo beforeSave', array($new_image_url, $new_image_temp_path, $max_width, $max_height));
					}
					/*$imageMagickCommand = 'convert '.escapeshellarg($new_image_url).' -resize '.$max_width.'x'.$max_height.' '.escapeshellarg($new_image_temp_path).' ';
					shell_exec($imageMagickCommand);*/
					
					
					if (!file_exists($new_image_temp_path)) {
						copy($new_image_url, $new_image_temp_path);
					}
					
					$master_cache_size = getimagesize($new_image_temp_path);
					list($mastercache_width, $mastercache_height, $mastercache_type, $mastercache_attr) = $master_cache_size;
					$mastercache_mime = $master_cache_size['mime'];
					$this->data['Photo']['forcache_pixel_width'] = $mastercache_width;
					$this->data['Photo']['forcache_pixel_height'] = $mastercache_height;
					
					
					if (!$this->CloudFiles->put_object($cache_image_name, $new_image_temp_path, $mastercache_mime)) {
						$this->major_error("failed to put master cache image in photo beforeSave", $cache_image_name);
						unset($this->data['Photo']['cdn-filename-forcache']);
						unset($this->data['Photo']['forcache_pixel_width']);
						unset($this->data['Photo']['forcache_pixel_height']);
					}
					
					unlink($new_image_temp_path);
					
					/* DEPRECATED
                                         * $handle = fopen($new_image_url, 'rb');
					$img = new Imagick();
					$img->readImageFile($handle);
					$geo = $img->getImageGeometry();
					if ( $geo['width'] > $max_width || $geo['height'] > $max_height ) {
						$img->resizeImage($max_width, $max_height, imagick::FILTER_LANCZOS, 0.9, true); // TODO - use a less intesive algorithm
						$geo = $img->getImageGeometry();
						$this->data['Photo']['forcache_pixel_width'] = $geo['width'];
						$this->data['Photo']['forcache_pixel_height'] = $geo['height'];
						
						$this->log($img->getImageLength(), 'finish_create_cache');
						$this->log('blah', 'finish_create_cache');
						
						if (!$this->CloudFiles->put_object_resource($cache_image_name, $handle, $img->getImageSize(), $mime_type)) {
							$this->major_error("failed to put master cache image in photo beforeSave", $cache_image_name);
							unset($this->data['Photo']['cdn-filename-forcache']);
							unset($this->data['Photo']['forcache_pixel_width']);
							unset($this->data['Photo']['forcache_pixel_height']);
						}
					} else {
						$this->data['Photo']['forcache_pixel_width'] = $geo['width'];
						$this->data['Photo']['forcache_pixel_height'] = $geo['height'];
						if (!$this->CloudFiles->copy_object($cache_image_name, $file_name)) {
							$this->major_error("failed to put master cache image in photo beforeSave as copy", $cache_image_name);
							unset($this->data['Photo']['cdn-filename-forcache']);
							unset($this->data['Photo']['forcache_pixel_width']);
							unset($this->data['Photo']['forcache_pixel_height']);
						}
					}
					$img->clear();*/
				} else {
					$this->major_error("the temp image path is not writable for photo before save");
				}
			} else {
				$this->major_error("failed to put an object to cloud files on photo save", array($this->data['Photo']['cdn-filename'], $file_name, $tmp_location, $mime_type) );
				unset($this->data['Photo']['cdn-filename']);
			}
		} else {
			unset($this->data['Photo']['cdn-filename']);
		}
		
		return true;
	}
	
	
	public function get_full_path($id) {
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		
		$photo = $this->find('first', array(
			'conditions' => array('Photo.id' => $id),
			'contain' => false,
			'fields' => array('Photo.cdn-filename')
		));
		
		return $this->SiteSetting->getImageContainerUrl().$photo['Photo']['cdn-filename'];
	}
	
	public function get_valid_filename($name) {
		$prefix = "fullsize";
		
		// remove spaces
		$name = str_replace(" ", "", $name);
		
		// remove underscores
		$name = str_replace("_", "", $name);
		
		
		// find a name that doesn't already exist
		$count = 1; 
		do {
			$name_to_try = $prefix."_".$this->number_pad($count, 4)."_".$name;
			$name_exists = $this->find('first', array(
				'conditions' => array('Photo.cdn-filename' => $name_to_try)
			));
			
			$count++;
		} while ($name_exists != array());
		$name = $name_to_try;
		
		return $name;
	}
	
	private function get_cloud_file() {
		if (!isset($this->CloudFiles)) {
			App::import('Component','CloudFiles');
			$this->CloudFiles = new CloudFilesComponent();
		}
		
		return $this->CloudFiles;
	}
	
	

}