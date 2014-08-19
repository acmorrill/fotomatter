<?php
class TagsController extends AppController {
    
	public $layout = 'admin/accounts';
	
	public function admin_test() {
		die('came into test 5');
	}
	
	public function admin_manage_tags() {
		$curr_page = 'photos';
		
		$this->set(compact('curr_page'));
	}
   
	
	public function admin_index() {
		$tag_query = "
			SELECT Tag.id, Tag.weight, Tag.name, Tag.created, (SELECT count(*) FROM photos_tags WHERE tag_id = Tag.id) as photos_count
			FROM tags AS Tag
		";
        $tags = $this->Tag->query($tag_query);
		
		// convert tag ids to int so json will be int and sort correct in angular
		foreach ($tags as &$tag) {
			$tag['Tag']['id'] = (int) $tag['Tag']['id'];
			$tag['Tag']['photos_count'] = (int) $tag[0]['photos_count'];
		}
                                        
		$this->return_json($tags);
    }

//    public function view($id) {
//        $tag = $this->Tag->findById($id);
//		$this->return_json($tag);
//    }

    public function admin_add() {
		$new_tag = array();
		$new_tag['Tag']['name'] = $_GET['name'];
		
		$exists = $this->Tag->findByName($_GET['name']);
		if (!empty($exists)) {
			$message = array(
                'text' => __('Duplicate tag name.', true),
                'type' => 'error'
            );
		} else if ($this->Tag->save($new_tag)) {
			$new_tag = $this->Tag->findById($this->Tag->id);
			$new_tag['Tag']['id'] = (int) $new_tag['Tag']['id'];
			$new_tag['Tag']['photos_count'] = 0;
            $message = array(
                'text' => __('Saved', true),
                'type' => 'success',
				'new_tag' => $new_tag
            );
        } else {
			$this->Tag->major_error('Tag failed to save in tag add.', compact('new_tag'));
            $message = array(
                'text' => __('Error', true),
                'type' => 'error'
            );
        }
		$this->return_json($message);
    }

    public function admin_edit($id) {
		$edit_tag = array();
		$edit_tag['Tag']['id'] = $id;
		$edit_tag['Tag']['name'] = $_GET['name'];
		
		$exists = $this->Tag->findByName($_GET['name']);
		
		if (!empty($exists)) {
            $message = array(
                'text' => __('Duplicate tag name.', true),
                'type' => 'error'
            );
		} else if ($this->Tag->save($edit_tag)) {
            $message = array(
                'text' => __('Saved', true),
                'type' => 'success'
            );
        } else {
			$this->Tag->major_error('Tag failed to save in tag edit.', compact('edit_tag'));
            $message = array(
                'text' => __('Tag failed to save.', true),
                'type' => 'error'
            );
        }
		$this->return_json($message);
    }

    public function admin_delete($id) {
        if ($this->Tag->delete($id)) {
            $message = array(
                'text' => __('Deleted', true),
                'type' => 'success'
            );
        } else {
			$this->Tag->major_error('Tag failed to delete in tag delete.', compact('id'));
            $message = array(
                'text' => __('Failed to delete tag.', true),
                'type' => 'error'
            );
        }
		$this->return_json($message);
    }
	
}