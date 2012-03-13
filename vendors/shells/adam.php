<?php
class AdamShell extends Shell {
    
    public function my_settings() {
        $this->User = ClassRegistry::init("User");
        $user['User']['email_address'] = 'adamdude828@gmail.com';
        $user['User']['password'] = '1990geocar';
        $user['User']['active'] = true;
        $this->User->save();
        $this->User->create($user);
    }
    
    public function test_api() {
        App::import("Component", "CloudFiles");
        $this->files = new CloudFilesComponent();
     
        debug($this->files->list_objects());
       
       // debug($this->files->detail_object('andrew-dev-container'));
        //debug($this->files->cdn_list_containers());
        
        
       // $this->files->copy_object("MichelleCellPhone", "copytest3.jpg", "test.jpg");
        
        //$image = $this->files->get_object('MichelleCellPhone', 'Image12212010113102.jpg');
        //var_dump($this->files->put_object('EmeraldFlow.jpg', '/home/acmorrill/Downloads/A Tangerine Blue small (1).tif', 'image/jpeg'));
        
       // file_put_contents('Image12212010113102.jpg', $image);
       // debug($this->files->list_objects('MichelleCellPhone'));
        //debug($this->files->delete_container('adam_test_1'));
     //  debug($this->files->list_cdn_containers());
//       debug($this->files->list_containers());
      
     //debug($this->files->detail_object('MichelleCellPhone', 'test.jpg'));
        debug($this->files->list_objects());
    }
}