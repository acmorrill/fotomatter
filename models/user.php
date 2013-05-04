<?php

class User extends AppModel {
    var $displayField = 'email_address';
    var $name = 'User';
    var $validate = array(
        'email_address' => array('email'),
        'password' => array('alphaNumeric'),
        'active' => array('numeric')
    );
    var $hasAndBelongsToMany = array(
		'Group' => array('className' => 'Group',
			'joinTable' => 'groups_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'group_id',
			'unique' => true
		)
    );
	
	public function create_user($email_address, $password, $is_admin = false) {
		App::import('Core', 'Security');
		
		$data['User']['admin'] = ($is_admin === true) ? 1 : 0;
		$data['User']['email_address'] = $email_address;
		$data['User']['password'] = Security::hash($password, null, true);
		$data['User']['active'] = '1';
		
		$exists = $this->find('first', array(
			'conditions' => array('User.email_address' => $email_address)
		));
		if ($exists != array()) {
			$data['User']['id'] = $exists['User']['id'];
		}
		
		if ($this->save($data)) {
			return $this->id;
		} else {
			$this->major_error('failed to create a user', compact('email_address', 'password', 'is_admin'));
			return false;
		}
	}
}
