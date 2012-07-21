<?php
class PhotosController extends AppController {
	public $name = 'Photos';
	public $uses = array(/*'OldPhoto', */'Photo', 'SiteSetting', 'PhotoFormat', 'SiteSetting');
	public $helpers = array('Menu', 'Photo');
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
		if (isset($this->params['form']['files'])) {
			$upload_data['name'] = $this->params['form']['files']['name'][0];
			$upload_data['tmp_name'] = $this->params['form']['files']['tmp_name'][0];
			$upload_data['type'] = $this->params['form']['files']['type'][0];
			$upload_data['size'] = $this->params['form']['files']['size'][0];
			
			$photo_for_db['Photo']['cdn-filename'] = $upload_data;
			$photo_for_db['Photo']['display_title'] = $this->params['form']['files']['name'][0];
			$this->Photo->create();
			if ($this->Photo->save($photo_for_db) === false) {
				$this->Photo->major_error('Photo failed to save on upload');
			}
			
			$photo_from_db = $this->Photo->find('first', array(
				'conditions'=>array(
					'Photo.id'=>$this->Photo->id
				)
			));
			$json['name'] = $photo_from_db['Photo']['display_title'];
			$json['size'] = $upload_data['size'];
			

			
			
			$this->return_json(true);
		}
	}
	
	public function admin_edit($id) {
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
