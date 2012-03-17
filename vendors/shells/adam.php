<?php
class AdamShell extends Shell {
    
    public function my_settings() {
        $this->User = ClassRegistry::init("User");
        $user['User']['email_address'] = 'adamdude828@gmail.com';
        $user['User']['password'] = '1990geocar';
        $user['User']['active'] = true;
        $this->User->save();
        $this->User->create($user);
        
        App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
        
        $this->SiteSetting = ClassRegistry::init("SiteSetting");
        $this->SiteSetting->setVal('image-container-name', 'adam-dev-container');
        
        $cdn_info = $this->files->cdn_detail_container('adam-dev-container');
        $this->SiteSetting->setVal("image-container-url", trim($cdn_info['CDN-URI']) . "/");
        $this->SiteSetting->setVal("image-container-secure_url", trim($cdn_info['CDN-SSL-URI']) . "/");
        
        $all_images = $this->files->list_objects();
        foreach ($all_images as $image) {
            $this->files->delete_object($image['name']);
        }
        
        //add some photos
        $photo_data = array();
		
        ////////////////////////////////////////////
        // add some default photos
        $this->Photo = ClassRegistry::init("Photo");
        $lastPhoto = $this->Photo->find('first', array(
                'order' => 'Photo.id DESC'
        ));
        if ($lastPhoto) {
                $x = $lastPhoto['Photo']['id'];
        } else {
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
    }
    
    public function test_api() {
        App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
        
        debug($this->files->cdn_detail_container('adam-dev-container'));
     
    }
}