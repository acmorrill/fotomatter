<?php

class UsersController extends AppController {

	public $name = 'Users';
	public $uses = array('User');
	public $layout = 'admin/login';

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('fb_callback', 'request_admin_password_change', 'change_admin_password'));
		require_once(ROOT.'/app/vendors/facebook-php-sdk-v4-5.0.0/src/Facebook/autoload.php');
	}

	public function fb_callback() {
		$fb = new Facebook\Facebook([
			'app_id' => FACEBOOK_APP_ID,
			'app_secret' => FACEBOOK_APP_SECRET,
			'default_graph_version' => FACEBOOK_GRAPH_VERSION,
		]);
		$helper = $fb->getRedirectLoginHelper();
		try {
			$accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			$this->User->major_error('Graph returned an error', array($e->getMessage()));
			$this->Session->setFlash(__('An error occured with the Facebook login.', true), 'admin/flashMessage/warning', array(), 'auth');
			$this->redirect('/admin/users/login');
			return;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			$this->User->major_error('Facebook SDK returned an error', array($e->getMessage()));
			$this->Session->setFlash(__('An error occured with the Facebook login.', true), 'admin/flashMessage/warning', array(), 'auth');
			$this->redirect('/admin/users/login');
			return;
		}
		if (!isset($accessToken)) {
			if ($helper->getError()) {
				$this->User->major_error('Facebook returned an error', array($helper->getError(),$helper->getErrorCode(),$helper->getErrorReason(),$helper->getErrorDescription()));
			} else {
				$this->User->major_error('Facebook returned an error', array('accessToken not set for unkown reason'));
			}
			$this->Session->setFlash(__('An error occured with the Facebook login.', true), 'admin/flashMessage/warning', array(), 'auth');
			$this->redirect('/admin/users/login');
			return;
		}
		$oAuth2Client = $fb->getOAuth2Client();

		// Get the access token metadata from /debug_token
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);
		$tokenMetadata->validateAppId('360914430736815');

		$user = $this->User->find('first', array(
			'conditions' => array(
				'User.facebook' => $tokenMetadata->getField('user_id'),
				'User.admin' => true,
				'User.active' => true,
			),
			'contains' => false,
		));
		if (isset($user['User']['id'])) {
			$this->Auth->login($user['User']['id']);
			$this->redirect('/admin/theme_centers/choose_theme');
		} else {
			$this->Session->setFlash(__('No user is connected to that Facebook account.', true), 'admin/flashMessage/warning', array(), 'auth');
			$this->redirect('/admin/users/login');
		}
	}

	function admin_login() {
		$email = '';
		$facebook_url = $this->User->fb_login_url();

		if (isset($_GET['email'])) {
			$email = $_GET['email'];
		}
		
		if (!empty($this->data['User']['email_address'])) {
			$email = $this->data['User']['email_address'];
		}

		$this->set(compact('email', 'facebook_url'));
	}

	function request_admin_password_change() {
		// we are sending the forgot password email
		if (isset($this->data['User']['forgot_password_email'])) {
			$forgot_password_email = $this->data['User']['forgot_password_email'];
			
			
			// check to make sure the email is a valid email for a user
			$change_password_user = $this->User->find('first', array(
				'conditions' => array(
					'User.email_address' => $forgot_password_email,
					'User.admin' => true,
				),
				'contain' => false,
			));
			
			if (empty($change_password_user)) {
				$this->Session->setFlash(__('Email does not belong to a valid user', true), 'admin/flashMessage/warning', array(), 'auth');
			} else {
				$this->Session->setFlash(__('Check your email to change your password', true), 'admin/flashMessage/success', array(), 'auth');
				$this->FotomatterEmail->send_forgot_password_email($this, $change_password_user);
			}
		}
		$this->redirect('/admin');
	}
	
	function change_admin_password($user_id, $passed_modified_hash) {
		$change_password_user = $this->User->find('first', array(
			'conditions' => array(
				'User.id' => $user_id,
			),
			'contain' => false,
		));
		
		$can_change_password = false;
		if (!empty($change_password_user)) {
			$modified_hash = openssl_digest($change_password_user['User']['modified'].FORGOT_PASSWORD_SALT, 'sha512');
			
			if ($modified_hash === $passed_modified_hash) {
				$can_change_password = true;
			}
		}
		
		
		$this->set(compact('can_change_password', 'user_id', 'passed_modified_hash'));
		if ($can_change_password === true && isset($this->data['User']['new_password']) && isset($this->data['User']['new_password_repeat'])) {
			try {
				$this->Validation->validate('account_valid_password', $this->data['User'], 'new_password', 'Please enter a valid password.');
				$this->Validation->validate('password_match', $this->data['User']['new_password'], $this->data['User']['new_password_repeat'], 'The passwords must match.');
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage(), 'admin/flashMessage/error', array(), 'auth');
				return;
			}
			
			// actually change the password
			$new_password_hash = Security::hash($this->data['User']['new_password'], null, true);
			$change_password_user['User']['password'] = $new_password_hash;
			unset($change_password_user['User']['modified']);
			if (!$this->User->save($change_password_user)) {
				$this->Session->setFlash(__("Failed to change password.", true), 'admin/flashMessage/error', array(), 'auth');
				$this->User->major_error('Failed to change admin user password.', compact('change_password_user'));
			} else {
				$this->Session->setFlash(__("Password changed.", true), 'admin/flashMessage/success', array(), 'auth');
				$this->redirect('/admin');
			}
		}
		
		if ($can_change_password === false) {
			$this->redirect('/admin');
		}
	}
	
	function admin_change_password() {
		try {
			$this->Validation->validate('account_valid_password', $this->params['form'], 'password', __('You must have a password that is longer than eight characters.', true));
			$this->Validation->validate('password_match', $this->params['form']['password'], $this->params['form']['password_confirm'], __('Your passwords do not match.', true));
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage(), 'admin/flashMessage/error');
			$this->redirect('/admin/accounts/account_details');
			exit();
		}
		$current_user = $this->Auth->user();
		$this->User->create_user($current_user['User']['email_address'], $this->params['form']['password'], true);
		$this->Session->setFlash(__('Your password has been changed successfully.', true), 'admin/flashMessage/success');
		$this->redirect('/admin/accounts/account_details');
		exit();
	}

	function admin_logout() {
		$this->Session->delete('user_has_logged_in');
		$this->Session->delete('Permissions');
		$this->redirect($this->Auth->logout());
	}

	function admin_index() {
		$this->layout = 'admin/users';
	}

}
