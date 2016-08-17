<?php

class PhotosController extends AppController {

    public $name = 'Photos';
    public $uses = array(/* 'OldPhoto', */'Photo', 'SiteSetting', 'PhotoFormat', 'SiteSetting', 'PhotoGalleriesPhoto', 'PhotoGallery', 'PhotosTag', 'Tag', 'PhotoAvailSizesPhotoPrintType', 'PhotoSellablePrint', 'Theme');
    public $helpers = array('Menu', 'Photo', 'Gallery');
    public $components = array('Upload', "ImageVersion", "Gd", "CloudFiles");
    public $paginate = array(
        'limit' => 50,
        'order' => array(
            'Photo.id' => 'asc'
        )
    );

    public function beforeFilter() {
        parent::beforeFilter();

        $this->layout = 'admin/photos';

        $this->Auth->allow('view_photo');
    }

    public function admin_delete_all_photos() {
        if ($this->Photo->delete_all_photos()) {
            $this->Session->setFlash(__('All photos deleted successfully', true), 'admin/flashMessage/success');
        } else {
            $this->Session->setFlash(__('Failed to delete all photos', true), 'admin/flashMessage/error');
            $this->Photo->major_error('Failed to delete all photos');
        }

        $this->redirect('/admin/photos/');
    }

    public function admin_delete_photo($photo_id = null) {
        if ($photo_id == null) {
            $this->redirect('/admin/photos/');
        }


        if ($this->Photo->delete($photo_id)) {
            $this->Session->setFlash(__('Photo deleted successfully', true), 'admin/flashMessage/success');
        } else {
            $this->Session->setFlash(__('Failed to delete photo', true), 'admin/flashMessage/error');
            $this->Photo->major_error('Failed to delete photo', compact($photo_id));
        }


        $this->redirect('/admin/photos/');
    }

    public function view_photo($photo_id = null) {
        $total_photos = $this->Photo->count_total_photos(true);
//		if ($total_photos <= 100) { // only do photo view caching on sites with less than 100 photos // DREW TODO - maybe make this based on the free limit
        $this->setup_front_end_view_cache($this);
//		}


        $conditions = array();
        if (isset($photo_id)) {
            $conditions = array(
                'Photo.id' => $photo_id
            );
        }


        $max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
        if (!empty($max_photo_id)) {
            $conditions['Photo.id <='] = $max_photo_id;
        }


        // find the photo
        $curr_photo = $this->Photo->find('first', array(
            'conditions' => $conditions,
            'contain' => array(
                'PhotoFormat'
            )
        ));
        $photo_id = $curr_photo['Photo']['id'];

        if (empty($curr_photo) || empty($curr_photo['Photo']['enabled'])) {
            $this->redirect('/');
        }

        // what gallery are we currently in
        $gallery_id = isset($this->params['named']['gid']) ? $this->params['named']['gid'] : '';
        $conditions = array();
        if (isset($gallery_id)) {
            $conditions['PhotoGallery.id'] = $gallery_id;
        }
        $curr_gallery = $this->PhotoGallery->find('first', array(
            'conditions' => $conditions,
            'contain' => false
        ));

        if (empty($curr_photo['Photo']['use_date_taken'])) {
            unset($curr_photo['Photo']['date_taken']);
        }

//		if (empty($curr_photo['Photo']['display_title'])) {
//			$curr_photo['Photo']['display_title'] = "Untitled";
//		}

        $this->set(compact('curr_photo', 'curr_gallery', 'photo_sellable_prints', 'photo_id', 'dynamic_photo_size'));
        $this->ThemeRenderer->render($this);
    }

    public function admin_index() {
        $max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
        $total_photos = $this->Photo->count_total_photos();
        $max_used_space_megabytes = $this->Photo->get_max_photo_space();
        $total_used_space_megabytes = $this->Photo->get_total_photo_used_space(true);


        $data = $this->paginate('Photo');
        $imageContainerUrl = $this->SiteSetting->getImageContainerUrl();
        $this->set(compact('data', 'imageContainerUrl', 'max_photo_id', 'total_photos', 'total_used_space_megabytes', 'max_used_space_megabytes'));
    }

    public function admin_mass_upload() {
        $max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
        $total_photos = $this->Photo->count_total_photos();
        $max_used_space_megabytes = $this->Photo->get_max_photo_space();
        $total_used_space_megabytes = $this->Photo->get_total_photo_used_space(true);


        $this->layout = 'admin/mass_upload';
        $curr_page = 'mass_upload';

        $total_photos = $this->Photo->count_total_photos();
        $max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
        $photos_left_to_add = LIMIT_MAX_FREE_PHOTOS - $total_photos;
        $this->set(compact('photos_left_to_add', 'curr_page', 'max_photo_id', 'total_photos', 'total_used_space_megabytes', 'max_used_space_megabytes'));
        if (!empty($max_photo_id) && $photos_left_to_add <= 0) {
            $this->FeatureLimiter->limit_view_go($this, 'unlimited_photos');
        }


        $max_used_space_megabytes = $this->Photo->get_max_photo_space();
        $total_used_space_megabytes = $this->Photo->get_total_photo_used_space();
        if ($total_used_space_megabytes >= $max_used_space_megabytes) {
            $this->FeatureLimiter->limit_view_go($this, 'unlimited_storage');
        }
    }

    public function admin_process_mass_photos($return_new_image_data = false) {
        $photo_id = null;
        if (!empty($this->params['form']['photo_id'])) {
            $photo_id = $this->params['form']['photo_id'];
        }


        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // fail if trying to add more than the max num photos if not paying for unlimited_photos
        // don't limit the photo upload if we are just editing an existing photo
        // need to just fail here because it they should have gotten an upsell in mass_upload
        if (empty($photo_id) && empty($this->current_on_off_features['unlimited_photos']) && $this->Photo->count_total_photos() >= LIMIT_MAX_FREE_PHOTOS) {
            $this->FeatureLimiter->limit_function_403();
        }


        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // fail if trying to upload more than the max total amount of data
        // just need to 403 because it should have gotten an upsell in mass_upload
        $max_used_space_megabytes = $this->Photo->get_max_photo_space();
        $total_used_space_megabytes = $this->Photo->get_total_photo_used_space();
        if ($total_used_space_megabytes >= $max_used_space_megabytes) {
            $this->FeatureLimiter->limit_function_403();
        }



        $upload_data = array();
        if (isset($this->params['form']['files'])) {
            $upload_data['name'] = $this->params['form']['files']['name'][0];
            $upload_data['tmp_name'] = $this->params['form']['files']['tmp_name'][0];
            $upload_data['type'] = $this->params['form']['files']['type'][0];
            $upload_data['size'] = $this->params['form']['files']['size'][0];
            $upload_data['pathinfo'] = pathinfo($upload_data['name']);


            if ($this->params['form']['files']['error'][0]) {
                $upload_data['error'] = $this->params['form']['files']['error'][0];
                $this->Photo->major_error('Photo failed to upload, probably due to apache limits', $this->params['form'], 'high');
                $this->return_mass_upload_json($upload_data);
            }

            $photo_for_db['Photo']['id'] = $photo_id;
            $photo_for_db['Photo']['cdn-filename'] = $upload_data;
//			$photo_for_db['Photo']['display_title'] = $upload_data['pathinfo']['filename']; // don't save this by default any more
            if (empty($this->data['tag_ids']) === false) {
                $photo_for_db['Tag'] = $this->data['tag_ids'];
            }

            $this->Photo->create();
            $save_photo_result = $this->Photo->before_save_code($photo_for_db); // some meaningless changes
            if (is_string($save_photo_result)) {
                $this->Photo->major_error('Photo failed to save in admin_process_mass_photos 1');
                $upload_data['error'] = 'Photo failed to save.';
                if (!empty($save_photo_result)) {
                    $upload_data['error'] = $save_photo_result;
                }
                $this->return_mass_upload_json($upload_data);
            } else {
                $photo_for_db = $save_photo_result;
            }
            if (!$this->Photo->save($photo_for_db)) {
                $this->Photo->major_error('Photo failed to save in admin_process_mass_photos 2');
                $upload_data['error'] = 'Photo failed to save.';
                $this->return_mass_upload_json($upload_data);
            }
            $this->last_photo_id = $this->Photo->id;


            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
            // after this point need to delete photo if anything fails


            if (!empty($this->data['gallery_ids'])) {
                foreach ($this->data['gallery_ids'] as $gallery_id) {
                    $gallery_photo['PhotoGalleriesPhoto']['photo_id'] = $this->Photo->id;
                    $gallery_photo['PhotoGalleriesPhoto']['photo_gallery_id'] = $gallery_id;
                    $this->PhotoGalleriesPhoto->create();
                    if ($this->PhotoGalleriesPhoto->save($gallery_photo) === false) {
                        $this->photo_after_save_fail_recover(); // DREW TODO - test this
                        $this->PhotoGalleriesPhoto->major_error('PhotoGalleriesphoto failed to save on photo upload.');
                        $upload_data['error'] = 'Failed to add photo to selected galleries.';
                        $this->return_mass_upload_json($upload_data);
                    }
                }
            }


            $upload_data['new_photo_id'] = $this->Photo->id;
            $upload_data['code'] = 1;
            $cache_file_height = isset($this->params['form']['height']) ? $this->params['form']['height'] : null;
            $cache_file_width = isset($this->params['form']['width']) ? $this->params['form']['width'] : null;
            if (isset($cache_file_width) && isset($cache_file_height)) {
                $new_photo_path_data = $this->Photo->get_photo_path($this->Photo->id, $cache_file_height, $cache_file_width, 0, true);
                $upload_data['new_photo_path'] = $new_photo_path_data['url'];
                $upload_data['new_width'] = $new_photo_path_data['width'];
                $upload_data['new_height'] = $new_photo_path_data['height'];
                $upload_data['thumbnailUrl'] = $upload_data['new_photo_path'];
            }
        } else {
            $this->Photo->major_error('file params not set in admin_process_mass_photos');
            $upload_data['error'] = "File upload error.";
        }


        $this->return_mass_upload_json($upload_data);
    }

    public function photo_after_save_fail_recover() { // DREW TODO - test this
        if (!empty($this->last_photo_id)) {
            $this->Photo->delete($this->last_photo_id);
        }
    }

    private function return_mass_upload_json($upload_data) {
        $files[] = $upload_data;

        echo json_encode(compact('files'));
        exit();
    }

    public function admin_edit($id, $starting_tab = 0) {
        $this->layout = 'admin/photo_details';
        $max_photo_id = $this->Photo->get_last_photo_id_based_on_limit();
        if (!empty($max_photo_id) && $id > $max_photo_id) {
            $this->FeatureLimiter->limit_function_403();
        }


        $this->HashUtil->set_new_hash('ecommerce');

        if ($id != null) { // edit or save mode?
            $this->set('mode', 'edit');
        } else {
            $this->set('mode', 'add');
        }


        $photo_formats = $this->Photo->PhotoFormat->find('list');
        $this->set('photoFormats', $photo_formats);
        if (empty($this->data)) {

            $this->data = $this->Photo->find('first', array(
                'conditions' => array(
                    'Photo.id' => $id
                ),
                'contain' => array(
                    'PhotoFormat'
                )
            ));



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


            if ($this->data['Photo']['display_title'] == 'Photo Title') {
                $this->data['Photo']['display_title'] = '';
            }
            if ($this->data['Photo']['display_subtitle'] == 'Photo Subtitle') {
                $this->data['Photo']['display_subtitle'] = '';
            }

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
            if (!empty($this->current_on_off_features['basic_shopping_cart'])) {
                if (isset($this->data['PhotoSellablePrint'])) {
                    foreach ($this->data['PhotoSellablePrint'] as $curr_photo_sellable_data) {
                        if ($curr_photo_sellable_data['override_for_photo'] === '1') {
                            if (isset($curr_photo_sellable_data['available'])) {
                                $curr_photo_sellable_data['available'] = '1';
                            } else {
                                $curr_photo_sellable_data['available'] = '0';
                            }

                            //////////////////////////////////////////////////////////////////////////////////////////////////////////
                            // if the value is the same as the default then save as null it so it will use a changed default
                            // this currently doesn't effect 'availability' right now (as its not passed in the default array)
                            foreach ($curr_photo_sellable_data['defaults'] as $default_name => $default_value) {
                                if ($curr_photo_sellable_data[$default_name] == $default_value) {
                                    $curr_photo_sellable_data[$default_name] = null;
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
                }
            }


            $this->Photo->create();
            $before_save_code = $this->Photo->before_save_code($this->data);
            if (is_string($before_save_code) || !$this->Photo->save($before_save_code)) {
                $curr_data = $this->data;
                $this->Photo->major_error('Failed to save changes to a photo in admin/photos/edit', compact('curr_data', 'before_save_code'));
                $this->Session->setFlash(__('Error saving photo', true), 'admin/flashMessage/error');
            } else {
                $this->Session->setFlash(__('Photo saved', true), 'admin/flashMessage/success');
                if ($id == null) { // adding
                    $this->redirect('/admin/photos/');
                }
            }

            // have to reset data because it could have change in a photo beforeSave
            $this->data = $this->Photo->find('first', array(
                'conditions' => array(
                    'Photo.id' => $this->data['Photo']['id']
                )
            ));
        }



        /////////////////////////////////////////////////
        // Get available print types and sizes 
        // and any overridden values for the photo
        $photo_sellable_prints = array();
        if (!empty($this->current_on_off_features['basic_shopping_cart'])) {
            $photo_sellable_prints = $this->Photo->get_sellable_print_sizes($this->data);
        }
        $this->set(compact('photo_sellable_prints'));


        $photo_tags = $this->PhotosTag->find('all', array(
            'conditions' => array(
                'PhotosTag.photo_id' => $this->data['Photo']['id']
            ),
            'contain' => false
        ));
        $photo_tag_ids = Set::extract('/PhotosTag/tag_id', $photo_tags);

        $this->set(compact('photo_tag_ids', 'starting_tab'));
    }

    public function admin_set_override_photo_pricing($photo_id) {
        $photo_data = array();
        $photo_data['Photo']['id'] = $photo_id;
        $photo_data['Photo']['override_pricing'] = 1;
        $this->Photo->create();
        $this->Photo->save($photo_data);

        // DREW TODO - this goes to the first tab because going to the second tab breaks the tags js plugin for now - it would be nice to fix this and go the second tab (already coded)
        $this->redirect("/admin/photos/edit/$photo_id/0");
    }
    
    public function admin_unset_override_photo_pricing($photo_id) {
        $photo_data = array();
        $photo_data['Photo']['id'] = $photo_id;
        $photo_data['Photo']['override_pricing'] = 0;
        
        // alse remove all photo sellable prints for photo
        $this->PhotoSellablePrint->delete_all_by_photo_id($photo_id);
        
        
        $this->Photo->create();
        $this->Photo->save($photo_data);

        // DREW TODO - this goes to the first tab because going to the second tab breaks the tags js plugin for now - it would be nice to fix this and go the second tab (already coded)
        $this->redirect("/admin/photos/edit/$photo_id/0");
    }

    /* if (!empty($thumbFile['tmp_name'])) {
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
      $this->Session->setFlash($errors, 'admin/flashMessage/error');
      }
      } */

    /* $largeFile = $this->data['Photo']['largeImage'];
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
      //                              $this->Session->setFlash("Failed to copy thumbnail to its directory.", 'admin/flashMessage/error');
      //                         }
      if (isset($this->Upload->result) && !copy($smallThumbPath, $smallThumbNailMoveToo.$this->data['Photo']['title'])) {
      $this->Session->setFlash("Failed to copy small thumbnail to its directory.", 'admin/flashMessage/error');
      }
      } else {
      $errors = $this->Upload->errors;
      // piece together errors
      if(is_array($errors)){ $errors = implode("<br />",$errors); }
      $this->Session->setFlash($errors, 'admin/flashMessage/error');
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
      $this->Session->setFlash($errors, 'admin/flashMessage/error');
      }
      } */

//		   $this->data['Photo']['galleries'] = implode(',', $this->data['Photo']['galleries']);
//		   $this->data['Photo']['availSizes'] = implode(',', $this->data['Photo']['availSizes']);
//		   if ($id == null) { // adding
//				$this->data['Photo']['position'] = -1;
//		   }	






















































    /*     * **************************************************
     * OLD FUNCTIONS FOR THE OLD SYSTEM
     * ************************************************* */



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
                $thumbResult = $this->Upload->upload($thumbFile, $thumbDestination, null, array('type' => 'resize', 'quality' => 100, 'size' => '600', 'output' => 'jpg'));
                if (!$thumbResult) {
                    $this->data['OldPhoto']['thumbImage'] = $this->Upload->result;
                    $thumbSize = getimagesize($thumbDestination . $this->data['OldPhoto']['title']);
                    $this->data['OldPhoto']['thumbWidth'] = $thumbSize[0];
                    $this->data['OldPhoto']['thumbHeight'] = $thumbSize[1];
                } else {
                    $errors = $this->Upload->errors;
                    // piece together errors
                    if (is_array($errors)) {
                        $errors = implode("<br />", $errors);
                    }
                    $this->Session->setFlash($errors, 'admin/flashMessage/error');
                }
            }


            $largeFile = $this->data['OldPhoto']['largeImage'];
            if (!empty($largeFile['tmp_name'])) {
                $largeFile['name'] = $this->data['OldPhoto']['title'];
                $largeResult = $this->Upload->upload($largeFile, $largeDestination, null, array('type' => 'resize', 'quality' => 100, 'size' => '1000', 'output' => 'jpg'));
                if (!$largeResult) {
                    $this->data['OldPhoto']['largeImage'] = $this->Upload->result;
                    $largeSize = getimagesize($largeDestination . $this->data['OldPhoto']['title']);
                    $this->data['OldPhoto']['webWidth'] = $largeSize[0];
                    $this->data['OldPhoto']['webHeight'] = $largeSize[1];

                    $path = $largeDestination . $this->Upload->result;

                    // automagically create the thumbnails
                    if ($this->data['OldPhoto']['format'] != "panoramic") {
                        //$thumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false, 'quality' => 100, 'size' => array(195, 195)));
                        $smallThumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false, 'quality' => 100, 'size' => array(156, 156)));
                    } else {
                        //$thumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false,  'quality' => 100,  'size' => array(434, 434)));
                        $smallThumbPath = $this->ImageVersion->version(array('image' => $path, 'absolute_path' => true, 'sharpen' => false, 'quality' => 100, 'size' => array(156, 156)));
                    }

                    // add border to regular thumbnail
                    //$this->Gd->addBorder($thumbPath, 2, array(255, 255, 255));
                    //$this->Gd->addBorder($thumbPath, 1, array(205, 205, 205));
                    //$thumbNailMoveToo = str_replace(DS, '/', realpath('../../app/webroot/photos/thumbs/')."/");
                    $smallThumbNailMoveToo = str_replace(DS, '/', realpath('../../app/webroot/photos/smallThumbs/') . "/");


                    //                         if (isset($this->Upload->result) && !copy($thumbPath, $thumbNailMoveToo.$this->Upload->result)) {
                    //                              $this->Session->setFlash("Failed to copy thumbnail to its directory.", 'admin/flashMessage/error');
                    //                         }
                    if (isset($this->Upload->result) && !copy($smallThumbPath, $smallThumbNailMoveToo . $this->data['OldPhoto']['title'])) {
                        $this->Session->setFlash(__("Failed to copy small thumbnail to its directory.", true), 'admin/flashMessage/error');
                    }
                } else {
                    $errors = $this->Upload->errors;
                    // piece together errors
                    if (is_array($errors)) {
                        $errors = implode("<br />", $errors);
                    }
                    $this->Session->setFlash($errors, 'admin/flashMessage/error');
                }
            }

            $extraLargeFile = $this->data['OldPhoto']['extraLargeImage'];
            if (!empty($extraLargeFile['tmp_name'])) {
                $extraLargeFile['name'] = $this->data['OldPhoto']['title'];
                $extraLargeResult = $this->Upload->upload($extraLargeFile, $extraLargeDestination, null, array('type' => 'resize', 'quality' => 100, 'size' => '1500', 'output' => 'jpg'));
                if (!$extraLargeResult) {
                    $this->data['OldPhoto']['largeImage'] = $this->Upload->result;
                    $extraLargeSize = getimagesize($extraLargeDestination . $this->data['OldPhoto']['title']);
                    $this->data['OldPhoto']['largeWebWidth'] = $extraLargeSize[0];
                    $this->data['OldPhoto']['largeWebHeight'] = $extraLargeSize[1];
                } else {
                    $errors = $this->Upload->errors;
                    // piece together errors
                    if (is_array($errors)) {
                        $errors = implode("<br />", $errors);
                    }
                    $this->Session->setFlash($errors, 'admin/flashMessage/error');
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
                $this->Session->setFlash(__('Photo saved', true), 'admin/flashMessage/success');
                if ($id == null) { // adding
                    $this->redirect('/photos/list_oldphotos/');
                }
            } else {
                $this->Session->setFlash(__('Error saving photo', true), 'admin/flashMessage/error');
                unlink($destination . $this->Upload->result);
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
