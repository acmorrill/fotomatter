<?php
class TagsController extends AppController {
    
	public $components = array('RequestHandler');
	public $layout = 'admin/tags';
	
	public function admin_manage_tags() {
		$curr_page = 'photos';
		
		$this->set(compact('curr_page'));
	}
   
	
	public function index() {
        $tags = $this->Tag->find('all');
                                        
		$this->return_json($tags);
    }

    public function view($id) {
        $tag = $this->Tag->findById($id);
		$this->return_json($tag);
    }

    public function add() {
        //$this->Tag->id = $id;
        if ($this->Tag->save($this->request->data)) {
            $message = array(
                'text' => __('Saved', true),
                'type' => 'success'
            );
        } else {
            $message = array(
                'text' => __('Error', true),
                'type' => 'error'
            );
        }
		$this->return_json($message);
    }

    public function edit($id) {
        $this->Tag->id = $id;
        if ($this->Tag->save($this->request->data)) {
            $message = array(
                'text' => __('Saved', true),
                'type' => 'success'
            );
        } else {
            $message = array(
                'text' => __('Error', true),
                'type' => 'error'
            );
        }
		$this->return_json($message);
    }

    public function delete($id) {
        if ($this->Tag->delete($id)) {
            $message = array(
                'text' => __('Deleted', true),
                'type' => 'success'
            );
        } else {
            $message = array(
                'text' => __('Error', true),
                'type' => 'error'
            );
        }
		$this->return_json($message);
    }
	
}