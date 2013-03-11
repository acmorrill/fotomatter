<?php
class PhotosController extends AppController {
	public $name = 'Photos';
	public $uses = array(/*'OldPhoto', */'Photo', 'SiteSetting', 'PhotoFormat', 'SiteSetting', 'PhotoGalleriesPhoto', 'PhotoGallery', 'PhotosTag', 'Tag', 'PhotoAvailSizesPhotoPrintType', 'PhotoSellablePrint');
	public $helpers = array('Menu', 'Photo', 'Gallery');
	public $components = array('Upload', "ImageVersion", "Gd", "ImageWizards", "CloudFiles");
	public $paginate = array(
		'limit' => 20,        
		'order' => array(            
			'Photo.id' => 'asc'
		)    
	);

	public function  beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'admin/photos';
		
		$this->Auth->allow('view_photo');
	}
	
	public function view_photo($photo_id = null) {
		$conditions = array();
		if (isset($photo_id)) {
			$conditions = array(
				'Photo.id' => $photo_id
			);
		}
		
		// find the photo
		$curr_photo = $this->Photo->find('first', array(
			'conditions' => $conditions,
			'contain' => array(
				'PhotoFormat'
			)
		));
		
		// what gallery are we currently in
		$gallery_id = isset($this->params['named']['gid']) ? $this->params['named']['gid'] : '' ;
		$conditions = array();
		if (isset($gallery_id)) {
			$conditions['PhotoGallery.id'] = $gallery_id;
		}
		$curr_gallery = $this->PhotoGallery->find('first', array(
			'conditions' => $conditions,
			'contain'=>false
		));
		
		
		$this->set(compact('curr_photo', 'curr_gallery'));
		$this->renderEmpty();
	}

	public function admin_index() {
		$data = $this->paginate('Photo');    
		$imageContainerUrl = $this->SiteSetting->getImageContainerUrl();
		$this->set(compact('data', 'imageContainerUrl'));
	}
	
	public function admin_mass_upload() {
		//$this->layout = 'ajax';
	}
	
	public function admin_process_mass_photos() {
		$returnArr['code'] = -1;
		$returnArr['message'] = 'this is not changed';
		if (isset($this->data['Tag'])) {
			$this->loadModel('Tag');
			$tag_result = $this->Tag->process_new_save($this->data['Tag']);
		}
		
		if ($this->data['GalleryPhoto']) {
			$galleries_to_save = array();
			foreach ($this->data['GalleryPhoto'] as $id => $to_use) {
				if ($to_use) {
					$galleries_to_save[] = $id;
				}
			}
		}
	
		if (isset($this->params['form']['files'])) {
			$upload_data['name'] = $this->params['form']['files']['name'][0];
			$upload_data['tmp_name'] = $this->params['form']['files']['tmp_name'][0];
			$upload_data['type'] = $this->params['form']['files']['type'][0];
			$upload_data['size'] = $this->params['form']['files']['size'][0];
			
			if ($this->params['form']['files']['error'][0]) {
				$this->Photo->major_error('Photo failed to upload, probably due to apache limits', 'high', $this->params['form']);
				$returnArr['code'] = -1;
				$returnArr['message'] = 'admin_process_mass_photos';
				$this->return_json($returnArr);
			}
			
			$photo_for_db['Photo']['cdn-filename'] = $upload_data;
			$photo_for_db['Photo']['display_title'] = $this->params['form']['files']['name'][0];
			if (empty($tag_result) === false) {
				$photo_for_db['Tag'] = $tag_result;
			}
			
			$this->Photo->create();
			if ($this->Photo->save($photo_for_db) === false) {
				$this->Photo->major_error('Photo failed to save in admin_process_mass_photos');
				$returnArr['code'] = -1;
				$returnArr['message'] = 'admin_process_mass_photos';
				$this->return_json($returnArr);
			}
			if (isset($galleries_to_save)) {
				foreach ($galleries_to_save as $gallery) {
					$gallery_photo['PhotoGalleriesPhoto']['photo_id'] = $this->Photo->id;
					$gallery_photo['PhotoGalleriesPhoto']['photo_gallery_id'] = $gallery;
					$this->PhotoGalleriesPhoto->create();
					if ($this->PhotoGalleriesPhoto->save($gallery_photo) === false) {
						$this->PhotoGalleriesPhoto->major_error('PhotoGalleriesphoto failed to save on photo upload.');
						$returnArr['code'] = -1;
						$returnArr['message'] = 'PhotoGalleriesphoto failed to save on photo upload.';
						$this->return_json($returnArr);
					}
				}	
			}
			$returnArr['new_photo_id'] = $this->Photo->id;
			$returnArr['code'] = 1;
			$cache_file_height = isset($this->params['form']['height']) ? $this->params['form']['height'] : null ;
			$cache_file_width = isset($this->params['form']['width']) ? $this->params['form']['width'] : null ;
			if (isset($cache_file_width) && isset($cache_file_height)) {
				$returnArr['new_photo_path'] = $this->Photo->get_photo_path($this->Photo->id, $cache_file_height, $cache_file_width);
			}
		} else {
			$this->Photo->major_error('file params not set in admin_process_mass_photos');
			$returnArr['code'] = -1;
			$returnArr['message'] = 'file params not set in admin_process_mass_photos';
		}
		$this->return_json($returnArr);
	}
	
	public function admin_edit($id) {
		$this->HashUtil->set_new_hash('ecommerce');
		
		if ($id != null) { // edit or save mode?
		   $this->set('mode', 'edit');
		} else {
		   $this->set('mode', 'add');
		}


		if (empty($this->data)) {
			$photo_formats = $this->Photo->PhotoFormat->find('list');
			$this->set('photoFormats', $photo_formats);
			
			$this->data = $this->Photo->find('first', array(
				'conditions' => array(
					'Photo.id' => $id
				),
				'contain' => array(
					'PhotoFormat'
				)
			));

			
			
			//$this->log($this->data, 'photo_edit');
			if ($id == null) { // adding (default data for when your adding)
				$this->data['Photo']['enabled'] = 1;
				$this->data['Photo']['photo_format_id'] = 1;
			}
		} else {
			// set the upload destination folder
//			$largeDestination = str_replace(DS, '/', realpath('../../app/webroot/photos/large/') . "/");
//			$extraLargeDestination = str_replace(DS, '/', realpath('../../app/webroot/photos/extraLarge/') . "/");
//			$thumbDestination = str_replace(DS, '/', realpath('../../app/webroot/photos/thumbs/') . "/");
		   
			// set or unset the id (depending on if its an edit or add)
			$this->data['Photo']['id'] = $id;


			if (isset($this->data['Photo']['tag_ids'])) {
				//$this->data['Photo']['tag_ids']
				
				// add the tags
				if (!$this->PhotosTag->deleteAll(array(
					'PhotosTag.photo_id' => $id
				), false, false)) {
					$this->PhotosTag->major_error('Failed to remove tags from photo');
				}
				
				foreach ($this->data['Photo']['tag_ids'] as $tag_id) {
					$new_photo_tag = array();
					$new_photo_tag['tag_id'] = $tag_id;
					$new_photo_tag['photo_id'] = $id;
					$this->PhotosTag->create();
					if (!$this->PhotosTag->save($new_photo_tag)) {
						$this->PhotosTag->major_error('Failed to add tag to photo', compact('tag_id'));
					}
				}
			}
			
			
			///////////////////////////////////////////////////////
			// save the sellable print data
			foreach ($this->data['PhotoSellablePrint'] as $curr_photo_sellable_data) {
				if ($curr_photo_sellable_data['override_for_photo'] === '1') {
					if (isset($curr_photo_sellable_data['available'])) {
						$curr_photo_sellable_data['available'] = '1';
					} else {
						$curr_photo_sellable_data['available'] = '0';
					}
					
					// if the value is the same as the default then don't save it so it will use a changed default
					foreach ($curr_photo_sellable_data['defaults'] as $default_name => $default_value) {
						if ($curr_photo_sellable_data[$default_name] == $default_value) {
							unset($curr_photo_sellable_data[$default_name]);
						}
					}
					
					$this->PhotoSellablePrint->create();
					if (!$this->PhotoSellablePrint->save(array('PhotoSellablePrint' => $curr_photo_sellable_data))) {
						$this->PhotoSellablePrint->major_error('failed to save photo sellable print on photo save', compact('curr_photo_sellable_data'));
					}
				} else {
					$this->PhotoSellablePrint->deleteAll(array(
						'PhotoSellablePrint.photo_avail_sizes_photo_print_type_id' => $curr_photo_sellable_data['photo_avail_sizes_photo_print_type_id'],
						'PhotoSellablePrint.photo_id' => $this->data['Photo']['id'],
					));
				}
			}
			
			
			$this->Photo->create();
			if ($this->Photo->save($this->data)) {
				//$this->Photo->replicatePriceCal($this->Photo->id); // this is to calculate the price in the old way for the old website (so when you add you don't have to run priceCal.php)
				$this->Session->setFlash('Photo saved');
				if ($id == null) { // adding
					 $this->redirect('/admin/photos/');
				}
				
				
				$photo_formats = $this->Photo->PhotoFormat->find('list');
				$this->set('photoFormats', $photo_formats);
				
				// have to reset data because it could have change in a photo beforeSave
				$this->data = $this->Photo->find('first', array(
					'conditions' => array(
						'Photo.id' => $this->Photo->id
					)
				));
			} else {
				$this->Photo->major_error('Failed to save changes to a photo in admin/photos/edit', $this->data);
				$this->Session->setFlash('Error saving photo');
			}
		}
		
		
		
		/////////////////////////////////////////////////
		// Get available print types and sizes 
		// and any overridden values for the photo
		$photo_sellable_prints = $this->Photo->get_sellable_print_sizes($this->data);
		$this->set(compact('photo_sellable_prints'));
		
		
		$tags = $this->Tag->find('all', array(
			'order' => array(
				'Tag.name'
			),
			'contain' => false
		));
		
		$photo_tags = $this->PhotosTag->find('all', array(
			'conditions' => array(
				'PhotosTag.photo_id' => $this->data['Photo']['id']
			),
			'contain' => false
		));
		$photo_tag_ids = Set::extract('/PhotosTag/tag_id', $photo_tags);
		
		$this->set(compact('tags', 'photo_tag_ids'));
	}

	
		   /*if (!empty($thumbFile['tmp_name'])) {
				$thumbFile['name'] = $this->data['Photo']['title'];
				$thumbResult = $this->Upload->upload($thumbFile, $thumbDestination, null, array('type' => 'resize','quality' => 100, 'size' => '600', 'output' => 'jpg'));
				if (!$thumbResult) {
					 $this->data['Photo']['thumbImage'] = $this->Upload->result;
					 $thumbSize = getimagesize ($thumbDestination.$this->data['Photo']['title']);
					 $this->data['Photo']['thumbWidth'] = $thumbSize[0];
					 $this->data['Photo']['thumbHeight'] = $thumbSize[1];
				} else {
					 $errors = $this->Upload->errors;
					 // piece together errors
					 if(is_array($errors)){ $errors = implode("<br />",$errors); }
					 $this->Session->setFlash($errors);
				}
		   }*/
	
		   /*$largeFile = $this->data['Photo']['largeImage'];
		   if (!empty($largeFile['tmp_name'])) {
				$largeFile['name'] = $this->data['Photo']['title'];
				$largeResult = $this->Upload->upload($largeFile, $largeDestination, null, array('type' => 'resize','quality' => 100, 'size' => '1000', 'output' => 'jpg'));
				if (!$largeResult) {
					 $this->data['Photo']['largeImage'] = $this->Upload->result;
					 $largeSize = getimagesize ($largeDestination.$this->data['Photo']['title']);
					 $this->data['Photo']['webWidth'] = $largeSize[0];
					 $this->data['Photo']['webHeight'] = $largeSize[1];

					 $path = $largeDestination.$this->Upload->result;

					 // automagically create the thumbnails
					 if ($this->data['Photo']['format'] != "panoramic") {
						  //$thumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false, 'quality' => 100, 'size' => array(195, 195)));
						  $smallThumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false,  'quality' => 100,  'size' => array(156, 156)));
					 } else {
						  //$thumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false,  'quality' => 100,  'size' => array(434, 434)));
						  $smallThumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false,  'quality' => 100,  'size' => array(156, 156)));
					 }

					 // add border to regular thumbnail
					 //$this->Gd->addBorder($thumbPath, 2, array(255, 255, 255));
					 //$this->Gd->addBorder($thumbPath, 1, array(205, 205, 205));

					 //$thumbNailMoveToo = str_replace(DS, '/', realpath('../../app/webroot/photos/thumbs/')."/");
					 $smallThumbNailMoveToo = str_replace(DS, '/', realpath('../../app/webroot/photos/smallThumbs/')."/");


	//                         if (isset($this->Upload->result) && !copy($thumbPath, $thumbNailMoveToo.$this->Upload->result)) {
	//                              $this->Session->setFlash("Failed to copy thumbnail to its directory.");
	//                         }
					 if (isset($this->Upload->result) && !copy($smallThumbPath, $smallThumbNailMoveToo.$this->data['Photo']['title'])) {
						  $this->Session->setFlash("Failed to copy small thumbnail to its directory.");
					 }
				} else {
					 $errors = $this->Upload->errors;
					 // piece together errors
					 if(is_array($errors)){ $errors = implode("<br />",$errors); }
					 $this->Session->setFlash($errors);
				}
		   }

		   $extraLargeFile = $this->data['Photo']['extraLargeImage'];
		   if (!empty($extraLargeFile['tmp_name'])) {
				$extraLargeFile['name'] = $this->data['Photo']['title'];
				$extraLargeResult = $this->Upload->upload($extraLargeFile, $extraLargeDestination, null, array('type' => 'resize','quality' => 100, 'size' => '1500', 'output' => 'jpg'));
				if (!$extraLargeResult) {
					 $this->data['Photo']['largeImage'] = $this->Upload->result;
					 $extraLargeSize = getimagesize ($extraLargeDestination.$this->data['Photo']['title']);
					 $this->data['Photo']['largeWebWidth'] = $extraLargeSize[0];
					 $this->data['Photo']['largeWebHeight'] = $extraLargeSize[1];
				} else {
					 $errors = $this->Upload->errors;
					 // piece together errors
					 if(is_array($errors)){ $errors = implode("<br />",$errors); }
					 $this->Session->setFlash($errors);
				}
		   }*/



//		   $this->data['Photo']['galleries'] = implode(',', $this->data['Photo']['galleries']);
//		   $this->data['Photo']['availSizes'] = implode(',', $this->data['Photo']['availSizes']);
//		   if ($id == null) { // adding
//				$this->data['Photo']['position'] = -1;
//		   }	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/****************************************************
	 * OLD FUNCTIONS FOR THE OLD SYSTEM
	 ***************************************************/
	
	
	
	public function admin_list_oldphotos($category = 'all') {

	  if ($category == 'all') {
		   $allOldPhotos = $this->OldPhoto->find('all', array(
				'order' => 'OldPhoto.position'
		   ));
	  } else {
		   $allOldPhotos = $this->OldPhoto->find('all', array(
				'order' => 'OldPhoto.position',
			   'conditions' => array('OldPhoto.galleries LIKE' => '%largeFormatColor%')
		   ));
	  }

	  $this->set('allOldPhotos', $allOldPhotos);
	}


	/////////////////////////////////////////////
	// this is also the ADD
	public function admin_edit_oldphoto($id = null) {
	  if ($id != null) { // edit or save mode?
		   $this->set('mode', 'edit');
	  } else {
		   $this->set('mode', 'add');
	  }


	  if (empty($this->data)) {
		   $this->data = $this->OldPhoto->findById($id);
		   if ($id == null) { // adding (default data for when your adding)
				$this->data['OldPhoto']['used'] = 1;
				$this->data['OldPhoto']['tier'] = 1;
				$this->data['OldPhoto']['pricePerFoot'] = 15;
				$this->data['OldPhoto']['format'] = 'landscape';
		   }
	  } else {
		   // set the upload destination folder
		   $largeDestination = str_replace(DS, '/', realpath('../../app/webroot/photos/large/') . "/");
		   $extraLargeDestination = str_replace(DS, '/', realpath('../../app/webroot/photos/extraLarge/') . "/");
		   $thumbDestination = str_replace(DS, '/', realpath('../../app/webroot/photos/thumbs/') . "/");


		   $thumbFile = $this->data['OldPhoto']['thumbImage'];
		   if (!empty($thumbFile['tmp_name'])) {
				$thumbFile['name'] = $this->data['OldPhoto']['title'];
				$thumbResult = $this->Upload->upload($thumbFile, $thumbDestination, null, array('type' => 'resize','quality' => 100, 'size' => '600', 'output' => 'jpg'));
				if (!$thumbResult) {
					 $this->data['OldPhoto']['thumbImage'] = $this->Upload->result;
					 $thumbSize = getimagesize ($thumbDestination.$this->data['OldPhoto']['title']);
					 $this->data['OldPhoto']['thumbWidth'] = $thumbSize[0];
					 $this->data['OldPhoto']['thumbHeight'] = $thumbSize[1];
				} else {
					 $errors = $this->Upload->errors;
					 // piece together errors
					 if(is_array($errors)){ $errors = implode("<br />",$errors); }
					 $this->Session->setFlash($errors);
				}
		   }


		   $largeFile = $this->data['OldPhoto']['largeImage'];
		   if (!empty($largeFile['tmp_name'])) {
				$largeFile['name'] = $this->data['OldPhoto']['title'];
				$largeResult = $this->Upload->upload($largeFile, $largeDestination, null, array('type' => 'resize','quality' => 100, 'size' => '1000', 'output' => 'jpg'));
				if (!$largeResult) {
					 $this->data['OldPhoto']['largeImage'] = $this->Upload->result;
					 $largeSize = getimagesize ($largeDestination.$this->data['OldPhoto']['title']);
					 $this->data['OldPhoto']['webWidth'] = $largeSize[0];
					 $this->data['OldPhoto']['webHeight'] = $largeSize[1];

					 $path = $largeDestination.$this->Upload->result;

					 // automagically create the thumbnails
					 if ($this->data['OldPhoto']['format'] != "panoramic") {
						  //$thumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false, 'quality' => 100, 'size' => array(195, 195)));
						  $smallThumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false,  'quality' => 100,  'size' => array(156, 156)));
					 } else {
						  //$thumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false,  'quality' => 100,  'size' => array(434, 434)));
						  $smallThumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false,  'quality' => 100,  'size' => array(156, 156)));
					 }

					 // add border to regular thumbnail
					 //$this->Gd->addBorder($thumbPath, 2, array(255, 255, 255));
					 //$this->Gd->addBorder($thumbPath, 1, array(205, 205, 205));

					 //$thumbNailMoveToo = str_replace(DS, '/', realpath('../../app/webroot/photos/thumbs/')."/");
					 $smallThumbNailMoveToo = str_replace(DS, '/', realpath('../../app/webroot/photos/smallThumbs/')."/");


	//                         if (isset($this->Upload->result) && !copy($thumbPath, $thumbNailMoveToo.$this->Upload->result)) {
	//                              $this->Session->setFlash("Failed to copy thumbnail to its directory.");
	//                         }
					 if (isset($this->Upload->result) && !copy($smallThumbPath, $smallThumbNailMoveToo.$this->data['OldPhoto']['title'])) {
						  $this->Session->setFlash("Failed to copy small thumbnail to its directory.");
					 }
				} else {
					 $errors = $this->Upload->errors;
					 // piece together errors
					 if(is_array($errors)){ $errors = implode("<br />",$errors); }
					 $this->Session->setFlash($errors);
				}
		   }

		   $extraLargeFile = $this->data['OldPhoto']['extraLargeImage'];
		   if (!empty($extraLargeFile['tmp_name'])) {
				$extraLargeFile['name'] = $this->data['OldPhoto']['title'];
				$extraLargeResult = $this->Upload->upload($extraLargeFile, $extraLargeDestination, null, array('type' => 'resize','quality' => 100, 'size' => '1500', 'output' => 'jpg'));
				if (!$extraLargeResult) {
					 $this->data['OldPhoto']['largeImage'] = $this->Upload->result;
					 $extraLargeSize = getimagesize ($extraLargeDestination.$this->data['OldPhoto']['title']);
					 $this->data['OldPhoto']['largeWebWidth'] = $extraLargeSize[0];
					 $this->data['OldPhoto']['largeWebHeight'] = $extraLargeSize[1];
				} else {
					 $errors = $this->Upload->errors;
					 // piece together errors
					 if(is_array($errors)){ $errors = implode("<br />",$errors); }
					 $this->Session->setFlash($errors);
				}
		   }



		   $this->data['OldPhoto']['galleries'] = implode(',', $this->data['OldPhoto']['galleries']);
		   $this->data['OldPhoto']['availSizes'] = implode(',', $this->data['OldPhoto']['availSizes']);
		   if ($id == null) { // adding
				$this->data['OldPhoto']['position'] = -1;
		   }
		   $this->data['OldPhoto']['id'] = $id;

		   if ($this->OldPhoto->save($this->data)) {
				$this->OldPhoto->replicatePriceCal($this->OldPhoto->id); // this is to calculate the price in the old way for the old website (so when you add you don't have to run priceCal.php)
				$this->Session->setFlash('Photo saved');
				if ($id == null) { // adding
					 $this->redirect('/photos/list_oldphotos/');
				}
		   } else {
				$this->Session->setFlash('Error saving photo');
				unlink($destination.$this->Upload->result);
		   }
	  }
	}


	public function admin_calc_photoprice($id) {
	  $this->set('data', $this->OldPhoto->findById($id));

	  $this->set('prices', $this->OldPhoto->recalcPricesForImage($id));
	}

	public function admin_update_order() {

	  $count = 0;
	  foreach ($this->params['form']['imageOrder'] as $imgId) {
		   $data['OldPhoto']['position'] = $count;
		   $this->OldPhoto->id = $imgId;
		   $this->OldPhoto->save($data);
		   $count++;
	  }


	  echo json_encode($this->params['form']);
	  exit();
	}

	public function admin_image_wizards_quote() {
	  $this->set('quote', $this->ImageWizards->getQuote());
	}

}
