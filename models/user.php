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

	public function create_user($email_address, $password, $is_admin = false, $facebook = null) {
		App::import('Core', 'Security');

		$data['User']['admin'] = ($is_admin === true) ? 1 : 0;
		$data['User']['email_address'] = $email_address;
		$data['User']['active'] = '1';

		if (empty($facebook)) {
			$data['User']['password'] = Security::hash($password, null, true);
			$exists = $this->find('first', array(
				'conditions' => array('User.email_address' => $email_address),
				'contain' => false
			));
			if ($exists != array()) {
				$data['User']['id'] = $exists['User']['id'];
			}
		} else {
			$data['User']['password'] = 'a';
			$data['User']['facebook'] = $facebook;
			$exists = $this->find('first', array(
				'conditions' => array('User.facebook' => $facebook),
				'contain' => false
			));
			if ($exists != array()) {
				$data['User']['id'] = $exists['User']['id'];
			}
			$emailUser = $this->find('first', array(
				'conditions' => array('User.email_address' => $email_address),
				'contain' => false
			));
			if ($emailUser != array() && $exists['User']['id'] !== $emailUser['User']['id']) {
				// email already taken. whoops.
				unset($data['User']['email_address']);
			}
		}

		if ($this->save($data)) {
			return $this->id;
		} else {
			$this->major_error('failed to create a user', compact('email_address', 'password', 'is_admin', 'facebook'));
			return false;
		}
	}

	public function fb_login_url($permissions = ['email']) {
		require_once(ROOT.'/app/vendors/facebook-php-sdk-v4-5.0.0/src/Facebook/autoload.php');
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$facebook_site = $this->SiteSetting->getVal('facebook', false);
		if (!$facebook_site) {
			return false;
		}
		$site_domain = $this->SiteSetting->getVal('site_domain');
		$fb = new Facebook\Facebook([
			'app_id' => '360914430736815',
			'app_secret' => 'de3419a89b4423f82f690e5909876928',
			'default_graph_version' => 'v2.5',
		]);
		$helper = $fb->getRedirectLoginHelper();
		$loginUrl = $helper->getLoginUrl("http://$site_domain.fotomatter.net/users/fb_callback", $permissions);
		return $loginUrl;
	}

	public function get_user_id_by_email($email_address) {
		$user = $this->find('first', array(
			'conditions' => array(
				'User.email_address' => $email_address,
				'User.admin' => true,
				'User.active' => true,
			),
			'contains' => false,
		));

		if (isset($user['User']['id'])) {
			return $user['User']['id'];
		} else {
			return false;
		}
	}

}
