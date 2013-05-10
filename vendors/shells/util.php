<?php

class UtilShell extends Shell {

	public $uses = array('User', 'Group', 'Permission', 'Photo', 'SiteSetting', 'PhotoGallery', 'PhotoGalleriesPhoto', 'PhotoCache', 'SitePage', 'SitePageElement', 'SitePagesSitePageElement', 'SiteOneLevelMenu', 'SiteTwoLevelMenu', 'SiteTwoLevelMenuContainer', 'SiteTwoLevelMenuContainerItem', 'PhotoAvailSize');

	///////////////////////////////////////////////////////////////
	/// shell start
	function _welcome() {
		Configure::write('debug', 1);

		$this->out();
		$this->out('Welcome to CakePHP v' . Configure::version() . ' Console');
		$this->hr();
		$this->out('App : ' . $this->params['app']);
		$this->out('Path: ' . $this->params['working']);
		$this->hr();
	}

	function main() {
		$this->help();
	}

	function help() {
		$kind = '';
		if (count($this->args)) {
			$kind = $this->args[0];
		}

		switch ($kind) {
			default:
				$this->out("
	add_default_data
	

");
		}
	}

	function fix_all_permissions() {
		$this->check_shell_running_as_root();

		$this->fix_mode_and_default_user_permissions();
		$this->fix_local_user_permissions();
		$this->fix_shared_user_permissions();
	}

	function fix_mode_and_default_user_permissions() {
		// set all file and folder permissions
		$root = ROOT;
		exec("{$this->get_root_prefix()} find $root -type d -exec chmod 775 {} \;", $output_1, $return_arr_1);
		exec("{$this->get_root_prefix()} find $root -type f -exec chmod 664 {} \;", $output_2, $return_arr_2);

		$default_user = Configure::read('file_folder_default_user');

		$this->set_owner("$default_user:$default_user", $root, true);
	}

	function fix_local_user_permissions() {
		$this->check_shell_running_as_root();
		$this->fix_mode_and_default_user_permissions();

		$root = ROOT;


		$permissions = array(
			'current_theme_webroot' => array('p' => ':www-data'),
			'default_theme_webroot' => array('p' => ':www-data'),
			'parent_theme_webroot' => array('p' => ':www-data'),
			'themes' => array('p' => ':www-data'),
			'image_tmp' => array('r' => ':www-data'),
			'local_master_cache' => array('r' => ':www-data'),
			'local_smaller_master_cache' => array('r' => ':www-data'),
			'site_background' => array('r' => ':www-data'),
			'site_logo' => array('r' => ':www-data'),
			'tmp' => array('r' => ':www-data'),
			'.' => array('p' => ':www-data'),
		);


		$this->recurse_set_user_permissions(ROOT, $permissions);
	}

	function fix_shared_user_permissions() {
		$this->check_shell_running_as_root();

		$permissions = array(
			'app' => array(
				'themes' => array(
					'adam' => array('webroot' => array('css' => array('r' => ':www-data'))),
					'amazing' => array('webroot' => array('css' => array('r' => ':www-data'))),
					'andrewmorrill' => array(
						'subthemes' => array(
							'difandrew' => array('webroot' => array('css' => array('r' => ':www-data'))),
						),
						'webroot' => array('css' => array('r' => ':www-data'))
					),
					'default' => array('webroot' => array('css' => array('r' => ':www-data'))),
					'simple_lightgrey_textured' => array('webroot' => array('css' => array('r' => ':www-data'))),
					'white_angular' => array('webroot' => array('css' => array('r' => ':www-data'))),
					'white_slider' => array('webroot' => array('css' => array('r' => ':www-data'))),
				),
				'webroot' => array(
					'css' => array('r' => ':www-data'),
					'img' => array(
						'photo_default' => array(
							'caches' => array('r' => ':www-data'),
						),
					),
				),
				'tmp' => array(
					'r' => ':www-data'
				),
			),
		);


		$this->recurse_set_user_permissions(ROOT, $permissions);
	}

	private function recurse_set_user_permissions($path, $array) {
		if (is_string($array)) {
			$this->set_owner($array, $path);
			return;
		}

		if (isset($array['p'])) {
			$this->set_owner($array['p'], $path);
		}

		if (isset($array['r'])) {
			$this->set_owner($array['r'], $path, true);
		}


		foreach ($array as $index => $value) {
			if ($index == 'r' || $index == 'p') {
				continue;
			}
			$this->recurse_set_user_permissions($path . '/' . $index, $value);
		}
	}

	private function set_owner($user, $path, $recurse = false) {
		$params = '--silent --no-dereference';
		if ($recurse == true) {
			$params .= " -R";
		}

		if (file_exists($path)) {
			exec("{$this->get_root_prefix()} chown $params $user $path", $output, $return_arr);
			$this->out("setting permissions: chown $params $user $path");
		} else {
//			$this->out('============================');
//			$this->out('file did not exist');
//			$this->out($path);
//			$this->out('============================');
		}
	}

	private function get_root_prefix() {
		if ($this->RUNNING_AS_ROOT == false) {
			return "sudo ";
		}
		return "";
	}

	private function check_shell_running_as_root() {
		exec('whoami', $current_user);
		if ($current_user[0] != 'root') {
			//can we chown and chmod without password
			exec('sudo -n chown --help', $output, $status);
			if ($status != 0) {
				$this->out('RUN THIS SHELL FUNCTION AS ROOT ONLY! OR MAKE SURE USER CAN CHOWN AND CHMODdd');
				exit();
			}

			exec('sudo -n chmod --help', $output, $status);
			if ($status != 0) {
				$this->out('RUN THIS SHELL FUNCTION AS ROOT ONLY! OR MAKE SURE USER CAN CHOWN AND CHMOD');
				exit();
			}

			$this->RUNNING_AS_ROOT = false;
			return;
		}
		$this->RUNNING_AS_ROOT = true;
		return;
	}

	function andrew_defaults() {
		$this->SiteSetting->setVal('image-container-url', 'http://c9134086.r86.cf2.rackcdn.com/');
		$this->SiteSetting->setVal('image-container-secure_url', 'https://c9134086.ssl.cf2.rackcdn.com/');
		$this->SiteSetting->setVal('image-container-name', 'andrew-dev-container');

		$this->defaults();
	}

	function defaults() {
		$this->MajorError = CLassRegistry::init('MajorError');
		$this->MajorError->deleteAll(array("1=1"), true, true);


		$this->out('-------- Deleting Photos ------------');
		$this->Photo->deleteAll(array("1=1"), true, true);
		$this->out('-------- Photos Deleted ------------');
		$this->out('-------- Deleting Photo Galleries ------------');
		$this->PhotoGallery->deleteAll(array("1=1"), true, true);
		$this->out('-------- Photo Galleries Deleted ------------');


		App::import("Component", "CloudFiles");
		$this->files = new CloudFilesComponent();

		$all_objects = $this->files->list_objects();

		$this->out('-------- Deleting Cloud Files Objects ------------');
		foreach ($all_objects as $all_object) {
			$this->files->delete_object($all_object['name']);
			//print_r($all_object);
		}
		$this->out('-------- Cloud Files Objects Deleted ------------');

		$this->args[0] = 20;
		$this->give_me_images();

		$this->out('-------- Adding Pages ------------');
		$this->add_pages();
		$this->out('-------- Pages Added ------------');

		$this->out('-------- Adding Menu Items ------------');
		$this->add_menu_items();
		$this->out('-------- Menu Items Added ------------');

		$this->out('-------- Adding Tags ------------');
		$this->add_tags();
		$this->out('-------- Tags Added ------------');


		$this->out('-------- Adding Avail Sizes ------------');
		$this->avail_photo_size_defaults();
		$this->out('-------- Avail Sizes Added ------------');
		/* $photo_data = array();

		  ////////////////////////////////////////////
		  // add some default photos
		  $lastPhoto = $this->Photo->find('first', array(
		  'order' => 'Photo.id DESC'
		  ));
		  if ($lastPhoto) {
		  $x = $lastPhoto['Photo']['id'];
		  } else {
		  $lastPhoto['Photo']['id'] = 0;
		  $x = 0;
		  }
		  for (; $x < $lastPhoto['Photo']['id'] + 300; $x++) {
		  $photo_data[$x]['display_title'] = 'Title '.$x;
		  $photo_data[$x]['display_subtitle'] = 'Subtitle '.$x;
		  $photo_data[$x]['description'] = 'description '.$x;
		  $photo_data[$x]['alt_text'] = $photo_data[$x]['display_subtitle'];
		  $photo_data[$x]['enabled'] = 1;
		  $photo_data[$x]['photo_format_id'] = rand(1, 5);
		  }
		  $this->Photo->saveAll($photo_data);



		  // add some default galleries and add random photos to them
		  $lastGallery = $this->PhotoGallery->find('first', array(
		  'order' => 'PhotoGallery.id DESC'
		  ));
		  if ($lastGallery) {
		  $x = $lastGallery['PhotoGallery']['id'];
		  } else {
		  $x = 0;
		  $lastGallery['PhotoGallery']['id'] = 0;
		  }
		  for (; $x < $lastGallery['PhotoGallery']['id'] + 50; $x++) {
		  $gallery_data['PhotoGallery']['display_name'] = 'Name '.$x;
		  $gallery_data['PhotoGallery']['description'] = 'description '.$x;
		  $this->PhotoGallery->create();
		  $this->PhotoGallery->save($gallery_data);

		  $limit = rand(0, 10);
		  if ($limit > 0) {
		  $randomPhotoIds = $this->Photo->find('list', array(
		  'fields' => 'id',
		  'order' => 'RAND()',
		  'limit' => $limit
		  ));
		  } else {
		  $randomPhotoIds = array();
		  }

		  foreach ($randomPhotoIds as $randomPhotoId) {
		  $photo_gallery_photo['PhotoGalleriesPhoto'] = array(
		  'photo_id' => $randomPhotoId,
		  'photo_gallery_id' => $this->PhotoGallery->id
		  );

		  $this->PhotoGalleriesPhoto->create();
		  $this->PhotoGalleriesPhoto->save($photo_gallery_photo);
		  }
		  } */
	}

	public function avail_photo_size_defaults() {
		$this->PhotoAvailSize->restore_avail_photo_size_defaults();
	}

	public function add_tags() {
		$this->Tag = ClassRegistry::init('Tag');
		$this->Tag->deleteAll(array("1=1"), true, true);

		$tags_to_add = array(
			'Portrait',
			'Landscape',
			'Wedding',
			'Awesome',
			'Wide Angle',
			'Telephoto',
			'Kids',
			'Head Shots',
			'Blue',
			'Sunset',
			'Green',
			'Night',
			'Moon',
			'Large Format',
			'Moab',
			'California',
			'Purple',
			'New York',
			'Misty',
			'Subdued',
			'Monster',
		);

		foreach ($tags_to_add as $tag) {
			$new_tag = array();
			$new_tag['Tag']['name'] = $tag;

			$this->Tag->create();
			$this->Tag->save($new_tag);
		}
	}

	public function add_menu_items() {
		// delete whole menu
		$this->SiteOneLevelMenu->deleteAll('1=1', true, true);


		$belongsTo = $this->SiteOneLevelMenu->belongsTo;

		$pos_models = array();
		foreach ($belongsTo as $model_name => $item) {
			$pos_models[] = $model_name;
		}


		///////////////////////////////////////////
		// put in the system menu items
		$new_menu_item = array();
		$new_menu_item['SiteOneLevelMenu']['external_id'] = 0;
		$new_menu_item['SiteOneLevelMenu']['external_model'] = 'SitePage';
		$new_menu_item['SiteOneLevelMenu']['ref_name'] = 'home';
		$new_menu_item['SiteOneLevelMenu']['is_system'] = '1';
		$this->SiteOneLevelMenu->create();
		$this->SiteOneLevelMenu->save($new_menu_item);

		$new_menu_item = array();
		$new_menu_item['SiteOneLevelMenu']['external_id'] = 0;
		$new_menu_item['SiteOneLevelMenu']['external_model'] = 'SitePage';
		$new_menu_item['SiteOneLevelMenu']['ref_name'] = 'image_galleries';
		$new_menu_item['SiteOneLevelMenu']['is_system'] = '1';
		$this->SiteOneLevelMenu->create();
		$this->SiteOneLevelMenu->save($new_menu_item);


		$total_menu_items = 10;
		for ($x = 0; $x < $total_menu_items; $x++) {
			$random_model = $pos_models[rand(0, count($pos_models) - 1)];

			$random_model_row = $this->$random_model->find('first', array(
				'contain' => false,
				'limit' => 1,
				'order' => 'rand()'
					));

			//debug($random_model_row[$random_model]['id']);

			$new_menu_item = array();
			$new_menu_item['SiteOneLevelMenu']['external_id'] = $random_model_row[$random_model]['id'];
			$new_menu_item['SiteOneLevelMenu']['external_model'] = $random_model;
			$this->SiteOneLevelMenu->create();
			$this->SiteOneLevelMenu->save($new_menu_item);
		}


		$this->SiteTwoLevelMenu->deleteAll('1=1', true, true);
		$this->SiteTwoLevelMenuContainer->deleteAll('1=1', true, true);
		$this->SiteTwoLevelMenuContainerItem->deleteAll('1=1', true, true);

		$belongsTo = $this->SiteTwoLevelMenu->belongsTo;

		$pos_models = array();
		foreach ($belongsTo as $model_name => $item) {
			$pos_models[] = $model_name;
		}

		$pos_cont_item_models = array();
		foreach ($this->SiteTwoLevelMenuContainerItem->belongsTo as $model_name => $item) {
			$pos_cont_item_models[] = $model_name;
		}

		$total_menu_items = 15;
		$total_containers = 1;
		for ($x = 0; $x < $total_menu_items; $x++) {
			$random_model = $pos_models[rand(0, count($pos_models) - 1)];

			if ($random_model == 'SiteTwoLevelMenuContainer') {
				// need to also create some random containers and container items
				$new_container = array();
				$new_container['SiteTwoLevelMenuContainer']['display_name'] = 'container ' . $total_containers;
				$total_containers++;
				$this->SiteTwoLevelMenuContainer->create();
				$this->SiteTwoLevelMenuContainer->save($new_container);

				$new_container_id = $this->SiteTwoLevelMenuContainer->id;

				// create some random menu items for the two level menu container
				$total_sub_menu_items = rand(2, 10);
				for ($r = 0; $r < $total_sub_menu_items; $r++) {
					$random_sub_model = $pos_cont_item_models[rand(0, count($pos_cont_item_models) - 1)];

					$random_sub_model_row = $this->$random_sub_model->find('first', array(
						'contain' => false,
						'limit' => 1,
						'order' => 'rand()'
							));


					$new_sub_menu_item = array();
					$new_sub_menu_item['SiteTwoLevelMenuContainerItem']['ref_name'] = 'custom';
					$new_sub_menu_item['SiteTwoLevelMenuContainerItem']['site_two_level_menu_container_id'] = $new_container_id;
					$new_sub_menu_item['SiteTwoLevelMenuContainerItem']['external_id'] = $random_sub_model_row[$random_sub_model]['id'];
					$new_sub_menu_item['SiteTwoLevelMenuContainerItem']['external_model'] = $random_sub_model;
					$this->SiteTwoLevelMenuContainerItem->create();
					$this->SiteTwoLevelMenuContainerItem->save($new_sub_menu_item);
				}

				$random_model_row = $this->SiteTwoLevelMenuContainer->find('first', array(
					'contain' => false,
					'conditions' => array(
						'SiteTwoLevelMenuContainer.id' => $new_container_id
					)
						));
			} else {
				$random_model_row = $this->$random_model->find('first', array(
					'contain' => false,
					'limit' => 1,
					'order' => 'rand()'
						));
			}




			$new_menu_item = array();
			$new_menu_item['SiteTwoLevelMenu']['external_id'] = $random_model_row[$random_model]['id'];
			$new_menu_item['SiteTwoLevelMenu']['external_model'] = $random_model;
			$this->SiteTwoLevelMenu->create();
			$this->SiteTwoLevelMenu->save($new_menu_item);
		}
	}

	public function add_pages() {
		@$this->SiteOneLevelMenu->deleteAll('1=1', true, true);
		@$this->SiteTwoLevelMenu->deleteAll('1=1', true, true);
		@$this->SiteTwoLevelMenuContainer->deleteAll('1=1', true, true);
		@$this->SiteTwoLevelMenuContainerItem->deleteAll('1=1', true, true);
		@$this->SitePage->deleteAll('1=1', true, true);
		@$this->SitePagesSitePageElement->deleteAll('1=1', true, true);


		for ($r = 0; $r < 50; $r++) {
			$data = array();
			$data['SitePage'] = array();
			$data['SitePage']['title'] = "Page " . str_pad(($r + 1), 3, "0", STR_PAD_LEFT);
			$this->SitePage->create();
			$this->SitePage->save($data);


			// add page elements to the just created page
			$num_elements_to_add = rand(0, 10);
			for ($i = 0; $i < $num_elements_to_add; $i++) {
				// find a random element
				$random_element = $this->SitePageElement->find('first', array(
					'order' => 'RAND()'
						));


				$data = array();
				$data['SitePagesSitePageElement']['site_page_id'] = $this->SitePage->id;
				$data['SitePagesSitePageElement']['site_page_element_id'] = $random_element['SitePageElement']['id'];
				$data['SitePagesSitePageElement']['config'] = array(
					'test1' => true,
					'test2' => false
				);
				$this->SitePagesSitePageElement->create();
				$this->SitePagesSitePageElement->save($data);
			}
		}
	}

	public function list_cloudfiles() {
		App::import("Component", "CloudFiles");
		$this->files = new CloudFilesComponent();

		debug($this->files->list_objects());
	}

	public function clear_container() {
		App::import("Component", "CloudFiles");
		$this->files = new CloudFilesComponent();

		if (isset($this->args[0]) === false) {
			$this->error('no container name');
			exit(1);
		}

		$all_images = $this->files->list_objects($this->args[0]);
		foreach ($all_images as $image) {
			$this->files->delete_object($image['name'], $this->args[0]);
		}
		return true;
	}

	public function clear_image_cache() {
		$this->PhotoCache->deleteAll('1=1', true, true);
	}

	public function cdn_detail() {
		$container_name = false;
		if (isset($this->args[0])) {
			$container_name = $this->args[0];
		}

		App::import("Component", "CloudFiles");
		$this->files = new CloudFilesComponent();
		debug($this->files->cdn_detail_container($container_name));
	}

	public function give_me_images() {
		if (isset($this->args[0]) && is_numeric($this->args[0]) === false) {
			$this->hr();
			$this->out('It looks you are trying to pass a image limit, but the value is not numeric');
			$this->out("example \n cake util give_me_images 12");
			$this->hr();
			exit(1);
		}

		$this->Photo->query("truncate table photos;");
		$this->PhotoCache->query("truncate table photo_caches");
		$this->PhotoGallery->query("truncate table photo_galleries");
		$this->PhotoGallery->query("truncate table photo_galleries_photos");
		$this->PhotoGallery->query("truncate table xhprof_profiles");
		exec("rm -rf " . LOCAL_MASTER_CACHE . "/*; rm -rf " . LOCAL_SMALLER_MASTER_CACHE . "/*");

		//clear current image
		App::import("Component", "CloudFiles");
		$this->files = new CloudFilesComponent();
		$all_objects = $this->files->list_objects();
		foreach ($all_objects as $object) {
			$this->files->delete_object($object['name']);
		}

		//Download any new images form the gallery
		App::import("Component", "CloudFiles");
		$this->files = new CloudFilesComponent();
		$tmp_images = TEMP_IMAGE_VAULT;

		$local_images = scandir($tmp_images);
		$tmp = array();
		//insert images into db
		$limit = false;
		if (isset($this->args[0])) {
			$limit = $this->args[0];
		}

		foreach ($local_images as $image) {
			if ($image == '.' || $image == '..') {
				continue;
			}
			$tmp[$image] = $image;
		}
		$local_images = $tmp;

		$master_test_images = $this->files->list_objects('master-test');
		$actual_count = 0;
		foreach ($master_test_images as $image) {
			if ($actual_count >= $limit && $limit != false)
				break;
			$actual_count++;

			if (empty($local_images[$image['name']])) {
				unset($output);
				exec("cd $tmp_images; wget http://c13957077.r77.cf2.rackcdn.com/" . $image['name'] . " > /dev/null 2>&1", $output);
			}
		}

		//I probably saved new images so rescan to be sure
		$local_images = scandir($tmp_images);
		$actual_count = 0;
		foreach ($local_images as $count => $image) {
			if ($image == '.' || $image == '..') {
				continue;
			}
			if ($actual_count >= $limit && $limit != false)
				break;
			$actual_count++;

			$photo_for_db['Photo']['cdn-filename']['tmp_name'] = $tmp_images . DS . $image;
			$photo_for_db['Photo']['cdn-filename']['name'] = $image;
			list($width, $height, $type, $attr) = getimagesize($tmp_images . DS . $image);
			$photo_for_db['Photo']['cdn-filename']['type'] = $type;
			$photo_for_db['Photo']['cdn-filename']['size'] = filesize($photo_for_db['Photo']['cdn-filename']['tmp_name']);


			$photo_for_db['Photo']['display_title'] = 'Title' . $image;
			$photo_for_db['Photo']['display_subtitle'] = 'subtitle' . $image;
			$photo_for_db['Photo']['alt_text'] = 'alt text ' . $image;
			$this->Photo->create();
			$this->Photo->save($photo_for_db);
			$this->out(($actual_count) . ". Image " . $image . " has been saved to the database.");
		}

		$this->out("Done Inserting Images");
		$this->hr();
		$this->out("Creating Galleries");

		//create random galleries and assign photos to them
		$lastGallery = $this->PhotoGallery->find('first', array(
			'order' => 'PhotoGallery.id DESC'
				));
		if ($lastGallery) {
			$r = $lastGallery['PhotoGallery']['id'];
		} else {
			$x = 0;
			$lastGallery['PhotoGallery']['id'] = 0;
		}
		for (; $x < $lastGallery['PhotoGallery']['id'] + 50; $x++) {
			$gallery_data['PhotoGallery']['display_name'] = 'Name ' . $x;
			$gallery_data['PhotoGallery']['description'] = 'description ' . $x;
			$this->PhotoGallery->create();
			$this->PhotoGallery->save($gallery_data);

			$limit = rand(0, 10);
			if ($limit > 0) {
				$randomPhotoIds = $this->Photo->find('list', array(
					'fields' => 'id',
					'order' => 'RAND()',
					'limit' => $limit
						));
			} else {
				$randomPhotoIds = array();
			}

			foreach ($randomPhotoIds as $randomPhotoId) {
				$photo_gallery_photo['PhotoGalleriesPhoto'] = array(
					'photo_id' => $randomPhotoId,
					'photo_gallery_id' => $this->PhotoGallery->id
				);

				$this->PhotoGalleriesPhoto->create();
				$this->PhotoGalleriesPhoto->save($photo_gallery_photo);
			}
		}
		$this->out("Done Creating Galleries");
	}

	public function upload_folder() {
		if (count($this->args) != 2) {
			$this->error("cake util upload_folder <complete-system-path> <rackspace container");
			exit(1);
		}
		App::import("Component", "CloudFiles");
		$this->files = new CloudFilesComponent();

		if (is_readable($this->args[0]) === false) {
			$this->error("You non person you... the folder is not readable");
			exit(1);
		}

		$all_images = scandir($this->args[0]);
		foreach ($all_images as $image) {
			if ($image == '.' || $image == '..') {
				continue;
			}

			list($width, $height, $type, $attr) = getimagesize($this->args[0] . "/" . $image);
			$result = $this->files->put_object($image, $this->args[0] . "/" . $image, $type, $this->args[1]);
			if ($result === 'false') {
				$this->error('I returned false');
				exit(1);
			}
		}
	}

	public function check() {
		App::import("Component", "CloudFiles");
		$this->files = new CloudFilesComponent();
		debug($this->files->detail_object($this->args[0], 'master-test'));
	}

	public function delete_stuff() {
		$to_delete = array(
			'thunderbird-icon.png'
		);
		App::import("Component", "CloudFiles");
		$this->files = new CloudFilesComponent();
		$all_objects = $this->files->list_objects('master-test');

		foreach ($to_delete as $delete) {
			debug($this->files->delete_object($delete, 'master-test'));
		}
	}

}