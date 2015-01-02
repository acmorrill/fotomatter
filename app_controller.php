<?php

class AppController extends Controller {

	public $view = 'fotomatter';

	/**
	 * components
	 *
	 * Array of components to load for every controller in the application
	 *
	 * @var $components array
	 * @access public
	 */
	var $components = array(
		'Auth',
		'Session',
		'RequestHandler',
		'HashUtil',
		'ThemeRenderer',
		'LessCss',
		'PhpClosure',
		'MobileDetect',
		'Browscap',
		'Validation',
		'Postmark',
		'FotomatterEmail',
		'FotomatterBilling',
		'FeatureLimiter',
	);
//	public $helpers = array(); // see helpers in beforeFilter

	/**
	 * beforeFilter
	 *
	 * Application hook which runs prior to each controller action
	 *
	 * @access public
	 */
	function beforeFilter() {
		$this->AccountDomain = ClassRegistry::init('AccountDomain');
		$this->SiteSetting = ClassRegistry::init('SiteSetting');
		$site_domain = $this->SiteSetting->getVal('site_domain');
		$this->set('site_domain', $site_domain);
		
		
		//////////////////////////////////////////////////////
		// stuff todo just in the admin
		$in_admin = isset($this->params['admin']) && $this->params['admin'] == 1;
		if ($in_admin) {
			$this->AccountDomain->invalidate_and_clear_view_cache();
		}
		
		///////////////////////////////////////////////////////////////
		// clear apc cache if in debug mode
		if (Configure::read('debug') > 0 || isset($_GET['apc_clear_cache'])) {
			apc_clear_cache('user');
		}
		
		
		////////////////////////////////////////////////////////////////////////////////////////////////
		// if we are on the welcome site 
		//	- disallow everything except welcome controller and user login
		//	- require ssl
		$WELCOME_SITE_URL = WELCOME_SITE_URL;
		if (empty($WELCOME_SITE_URL)) {
			$WELCOME_SITE_URL = 'welcome.fotomatter.net';
		}
		$this->on_welcome_site = $_SERVER['HTTP_HOST'] === $WELCOME_SITE_URL;
		$this->not_on_welcome_site = !$this->on_welcome_site;
		$this->not_in_welcome_controller = $this->startsWith($_SERVER['REQUEST_URI'], '/admin/welcome') == false;
		$this->not_in_welcome_site_access_area = $this->not_in_welcome_controller && $this->startsWith($_SERVER['REQUEST_URI'], '/admin/users/login') == false;
		if ( $this->on_welcome_site && ( $this->not_in_welcome_site_access_area || empty($_SERVER['HTTPS']) ) ) {
			header('HTTP/1.0 404 Not Found');
			die();
		}
		
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// if a password has never been setup via the welcome 
		// and in admin
		// and we are not on welcome site
		// and we are not in welcome controller
		// and is not the dev site
		// then redirect to the welcome create_password
		if ($in_admin && $this->not_on_welcome_site && $this->not_in_welcome_controller && $this->SiteSetting->getVal('welcome_password_set', 0) == 0 && $this->SiteSetting->getVal('is_dev', 0) != 1) {
			// grab the hash key from the global db
			$account_id = $this->SiteSetting->getVal('account_id', false);
			if (!empty($account_id)) {
				$this->GlobalWelcomeHash = ClassRegistry::init('GlobalWelcomeHash');
				$global_welcome_hash = $this->GlobalWelcomeHash->find('first', array(
					'conditions' => array(
						'GlobalWelcomeHash.account_id' => $account_id,
					),
					'contain' => false,
				));
				if (!empty($global_welcome_hash['GlobalWelcomeHash']['hash'])) {
					header("Location: https://$site_domain.fotomatter.net/admin/welcome/create_password?wh=" . $global_welcome_hash['GlobalWelcomeHash']['hash']);
					exit();
				}
			}
			
			// error if we made it here because no redirect happened above
			$this->major_error('went directly to built website, but welcome password not set and could not redirect to set password', array(), 'high');
			header('HTTP/1.0 404 Not Found');
			die();
		}
		
		
		
		//////////////////////////////////////////////////////////////////////
		// redirect to ssl if need be
		$in_checkout = false;
		if ($this->startsWith($_SERVER['REQUEST_URI'], '/ecommerces') && !$this->startsWith($_SERVER['REQUEST_URI'], '/ecommerces/view_cart') && !$this->startsWith($_SERVER['REQUEST_URI'], '/ecommerces/add_to_cart')) {
			$in_checkout = true;
		}
		$redirect_to_ssl = $in_admin || $in_checkout;
		if (empty($_SERVER['HTTPS']) && Configure::read('debug') == 0 && $redirect_to_ssl) {
			$this->redirect("https://$site_domain.fotomatter.net{$_SERVER['REQUEST_URI']}");
			exit();
		}
		
		
		
		
		//////////////////////////////////////////////////////////////////////////
		// redirect to primary domain if:
		// 1) not already on primary
		// 2) primary is not expired
		// 3) if don't need to redirect to ssl
		$current_primary_domain = $this->AccountDomain->get_current_primary_domain();
		$http_host = $_SERVER["HTTP_HOST"];
		if ($this->not_on_welcome_site && Configure::read('debug') == 0 && !$redirect_to_ssl && $http_host != $current_primary_domain) {
			$this->redirect("http://$current_primary_domain");
			exit();
		}
		$this->set('current_primary_domain', $current_primary_domain);
		
		
		
		// stuff to do only on not cli
		if (php_sapi_name() !== 'cli-server') {
			$this->helpers = array(
				'Session',
				'Form',
				'Util',
				'ThemeMenu',
				'ThemeLogo',
				'Theme',
				'Photo',
				'Gallery',
				'Ecommerce',
				'Cart',
				'Account',
				'SiteSetting',
				'Number',
				'Cache',
				'Page'
			);
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// find out what features are on or off (apc cached)
		$this->current_on_off_features = $this->FotomatterBilling->get_current_on_off_features();
		$this->current_feature_prices = $this->FotomatterBilling->get_current_feature_pricing();
		$this->set('current_on_off_features', $this->current_on_off_features);
		$this->set('current_feature_prices', $this->current_feature_prices);
		$GLOBALS['current_on_off_features'] = $this->current_on_off_features;
		$GLOBALS['current_feature_prices'] = $this->current_feature_prices;
//		$this->log($this->current_on_off_features, 'current_on_off_features');
//		$this->log($this->current_feature_prices, 'current_feature_prices');



		// DREW TODO - for testing only!
		if (Configure::read('debug') > 0 && !$this->Session->check('Message.flash') && rand(1, 7) == 5 ) {
			$this->Session->setFlash(
				'If you do not see this on a page that page is not outputting any flash messages and there also is no flash message to display. For testing only.', 
				'admin/flashMessage/success'
			);
		}


		
		///////////////////////////////////////////////////////
		// setup mobile settings for mobile theming
		// and supported browser code
		$this->is_mobile = false;
		$this->is_tablet = $this->MobileDetect->isTablet();
		if (!empty($this->current_on_off_features['mobile_theme']) && $this->MobileDetect->isMobile()) {
			$this->is_mobile = true;
		}
		$this->browser_is_supported = true;
		if ($this->is_mobile === false && $this->is_tablet === false) {
			$this->browser_is_supported = $this->Browscap->is_browser_supported();
		}
		$this->showed_supported_browser_popup = false;
		if (!$this->Session->check('showed_supported_browser_popup')) {
			$this->Session->write('showed_supported_browser_popup', true);
		} else {
			$this->showed_supported_browser_popup = true;
		}
		$this->set('browser_is_supported', $this->browser_is_supported);
		$this->set('showed_supported_browser_popup', $this->showed_supported_browser_popup);
		
		
		
		$this->set('is_mobile', $this->is_mobile);
		$this->set('is_tablet', $this->is_tablet);



		// recompile less css if a get param is set or debug is set to 2
		if (Configure::read('debug') > 0 || isset($this->params['url']['lesscss']) || $this->Session->check('recompile_css') ) {
			if (isset($this->params['url']['lesscss'])) {
				$this->Session->write('recompile_css', true);
			}
			$this->LessCss->recompile_css();
		}
		
		// recompile php_closure js if a get param is set or debug is set to 2
		if (Configure::read('debug') > 0 || isset($this->params['url']['php_closure']) || $this->Session->check('php_closure')) {
			if (isset($this->params['url']['php_closure'])) {
				$this->Session->write('php_closure', true);
			}
			$this->PhpClosure->recompile_javascript();
		}


		// ajax redirect code
		if (isset($this->params['url']['ajax_autoredirect'])) {
			$this->Session->write('Auth.redirect', $this->params['url']['ajax_autoredirect']);
		}

		//Override default fields used by Auth component
		$this->Auth->fields = array('username' => 'email_address', 'password' => 'password');
		//Set application wide actions which do not require authentication
		$this->Auth->allow('display', 'view'); //IMPORTANT for CakePHP 1.2 final release change this to $this->Auth->allow(array('display'));
		//$this->Auth->allow(array('*'));//IMPORTANT for CakePHP 1.2 final release change this to $this->Auth->allow(array('display'));
		//Set the default redirect for users who logout
		$this->Auth->flashElement = 'admin/flashMessage/warning';
		$this->Auth->logoutRedirect = '/admin/users/login';
		//Set the default redirect for users who login
		$this->Auth->loginRedirect = '/admin/theme_centers/choose_theme';
		//Extend auth component to include authorisation via isAuthorized action
		$this->Auth->authorize = 'controller';
		//Restrict access to only users with an active account
		$this->Auth->userScope = array('User.active = 1');
		$this->Auth->ajaxLogin = 'admin/ajax_login';
		//Pass auth component data over to view files
		$user_data = $this->Auth->user();
		$this->set('Auth', $user_data);
		
		
		//////////////////////////////////////////////////////////////
		// Send logged in info to overlord
		if (!empty($user_data['User']['id'])) {
			if (!$this->Session->check('user_has_logged_in')) {
				$this->Session->write('user_has_logged_in', true);
				
				// send the login data to overlord
				$ip = $this->get_real_ip_addr();
				$this->FotomatterBilling->log_user_logged_in($ip);
			}
		}
		
		
		


		// locking hash code
		if (isset($this->params['form']['global_current_js_locking_hash']) && isset($this->params['form']['global_current_js_locking_hash_namespace'])) {
			if ($this->HashUtil->check_this_hash($this->params['form']['global_current_js_locking_hash'], $this->params['form']['global_current_js_locking_hash_namespace']) === false) {
				$this->major_error('had to relead a page because of a locking hash', null, 'low');
				if ($this->RequestHandler->isAjax()) {
					header('HTTP/1.0 412 Precondition Failed');
				} else {
					$this->redirect($_SERVER['HTTP_REFERER']);
				}
				exit();
			}
		}
		
		
		
		//////////////////////////////////////////////////////////
		// turn on the first time login popup if
		// really is a first time login
		//	-- $this->not_in_welcome_site_access_area
		//	-- welcome_first_login_popup is not 1
		//	-- not on welcome site
		//	-- not the ping location
		// also - log them in if they also have the correct hash for their website
		$this->done_welcome_first_login_popup = 1;
		if ($this->not_in_welcome_site_access_area && $this->not_on_welcome_site && $this->startsWith($_SERVER['REQUEST_URI'], '/site_pages/ping') === false) {
			$this->done_welcome_first_login_popup = $this->SiteSetting->getVal('welcome_first_login_popup', 0);
			if (empty($this->done_welcome_first_login_popup)) {
				$this->SiteSetting->setVal('welcome_first_login_popup', 1);
				
				$account_id = $this->SiteSetting->getVal('account_id', false);
				if (!empty($account_id)) {
					$this->GlobalWelcomeHash = ClassRegistry::init('GlobalWelcomeHash');
					$global_welcome_hash = $this->GlobalWelcomeHash->find('first', array(
						'conditions' => array(
							'GlobalWelcomeHash.account_id' => $account_id,
						),
						'contain' => false,
					));
					if (!empty($global_welcome_hash['GlobalWelcomeHash']['hash'])) {
						$this->Welcome = ClassRegistry::init('Welcome');
						$this->User = ClassRegistry::init('User');
						// so log them in - this should only happen once because above welcome_first_login_popup is set to 1
						$account_email = $this->SiteSetting->getVal('account_email', false);
						if (!empty($account_email)) {
							$user_id = $this->User->get_user_id_by_email($account_email);
							if (!empty($user_id)) {
								$this->FotomatterEmail->send_first_login_email($this);
								$this->Auth->login($user_id);
							}
						}
					}
				}
			}
		}
		$this->set('done_welcome_first_login_popup', $this->done_welcome_first_login_popup);
	}
	
	public function validatePaymentProfile() {
		$this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_firstname', __('You must provide your first name.', true));
		$this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_lastname', __('You must provide your last name.', true));
		$this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_address', __('You must provide your address.', true));
		$this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_city', __('You must provide your city.', true));

		$this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_zip', __('You must provide your zip code.', true));
		$this->Validation->validate('valid_cc_no_type', $this->data['AuthnetProfile']['payment_cardNumber'], 'billing_cardNumber', __('Your credit card was not entered or not in a valid format.', true));
		$this->data['AuthnetProfile']['str_date'] = $this->data['AuthnetProfile']['expiration']['month'] . '/31' . '/' . $this->data['AuthnetProfile']['expiration']['year'];
		$this->Validation->validate('date_is_future', $this->data['AuthnetProfile'], 'str_date', __('Your date provided was invalid or not in the future.', true)); //Ok in theory this should never be hit cause they are selects
		$this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'payment_cardCode', __('Your csv code was either blank or invalid.', true));
	}


	/**
	 * beforeRender
	 *
	 * Application hook which runs after each action but, before the view file is
	 * rendered
	 *
	 * @access public
	 */
//    function beforeRender(){
//        //If we have an authorised user logged then pass over an array of controllers
//        //to which they have index action permission
//        if($this->Auth->user()){
//            $controllerList = Configure::listObjects('controller');
//            $permittedControllers = array();
//            foreach($controllerList as $controllerItem){
//                if($controllerItem <> 'App'){
//                    if($this->__permitted($controllerItem,'index')){
//                        $permittedControllers[] = $controllerItem;
//                    }
//                }
//            }
//        }
//        $this->set(compact('permittedControllers'));
//    }
	// deprecated - use ThemeRenderer->render()
//	public function renderEmpty() {
//		$this->render('/elements/empty_theme_page');
//	}


	function setup_front_end_view_cache(&$controller) {
		if (Configure::read('debug') == 0) {
			$controller->cacheAction = FRONTEND_VIEW_CACHING_STRTOTIME_TTL;
		}
	}

	function redirect($url) {
		parent::redirect($url);
		exit();
	}

	function get_json_from_input() {
		App::import('Sanitize');
		return Sanitize::clean(json_decode(file_get_contents("php://input"), true));
	}

	/**
	 * isAuthorized
	 *
	 * Called by Auth component for establishing whether the current authenticated
	 * user has authorization to access the current controller:action
	 *
	 * @return true if authorised/false if not authorized
	 * @access public
	 */
	function isAuthorized() {
		$is_admin_user = ($this->Auth->user('admin') === '1') ? true : false;

		if ($is_admin_user === true || (isset($this->front_end_auth) && in_array($this->action, $this->front_end_auth))) {
			return true;
		}

		return false;

		// DREW TODO - if we want to use more granular auth then turn the below back on and setup the db
//		$value = $this->__permitted($this->name,$this->action);
//        return $value;
	}

	/**
	 * __permitted
	 *
	 * Helper function returns true if the currently authenticated user has permission
	 * to access the controller:action specified by $controllerName:$actionName
	 * @return
	 * @param $controllerName Object
	 * @param $actionName Object
	 */
//    function __permitted($controllerName,$actionName){
//        //Ensure checks are all made lower case
//        $controllerName = low($controllerName);
//        $actionName = low($actionName);
//        //If permissions have not been cached to session...
//        if(!$this->Session->check('Permissions')){
//            //...then build permissions array and cache it
//            $permissions = array();
//            //everyone gets permission to logout
//            $permissions[]='users:logout';
//            //Import the User Model so we can build up the permission cache
//            App::import('Model', 'User');
//            $thisUser = new User;
//            //Now bring in the current users full record along with groups
//            $thisGroups = $thisUser->find(array('User.id'=>$this->Auth->user('id')));
//            $thisGroups = $thisGroups['Group'];
//            foreach($thisGroups as $thisGroup){
//                $thisPermissions = $thisUser->Group->find(array('Group.id'=>$thisGroup['id']));
//                $thisPermissions = $thisPermissions['Permission'];
//                foreach($thisPermissions as $thisPermission){
//                    $permissions[]=$thisPermission['name'];
//                }
//            }
//            //write the permissions array to session
//            $this->Session->write('Permissions',$permissions);
//        }else{
//            //...they have been cached already, so retrieve them
//            $permissions = $this->Session->read('Permissions');
//        }
//        //Now iterate through permissions for a positive match
//        foreach($permissions as $permission){
//            if($permission == '*'){
//                return true;//Super Admin Bypass Found
//            }
//            if($permission == $controllerName.':*'){
//                return true;//Controller Wide Bypass Found
//            }
//            if($permission == $controllerName.':'.$actionName){
//                return true;//Specific permission found
//            }
//        }
//        return false;
//    }

	public function element($element_path, $extra_vals = array()) {
		$this->layout = false;
		$this->autoRender = false;

		/* Set up new view that won't enter the ClassRegistry */
		$view = new View($this, false);
		foreach ($extra_vals as $key => $extra_val) {
			$view->set($key, $extra_val);
		}
		$view->Session = $this->Session;

		$view->viewPath = 'elements';

		/* Grab output into variable without the view actually outputting! */
		$return_html = $view->render($element_path);
		return $return_html;
	}

	public function return_json($data) {
		echo json_encode($data);
		exit();
	}

	public function major_error($description, $extra_data = null, $severity = 'normal') {
		$this->MajorError = ClassRegistry::init("MajorError");
		$this->MajorError->major_error($description, $extra_data, $severity);
	}

	public function is_logged_in_frontend() {
		$user = $this->Auth->user();

		if (!empty($user) && isset($user['User']['admin']) && $user['User']['admin'] != 1) {
			return true;
		} else {
			return false;
		}
	}

	/*********************************************************
	* HELPER FUNCTIONS
	* 
	*/
	public function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	public function endsWith($haystack, $needle) {
		$length = strlen($needle);
		$start  = $length * -1; //negative
		return (substr($haystack, $start) === $needle);
	}
	
	public function get_real_ip_addr() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	
	public function after_change_theme_todo() {
		$this->Theme = ClassRegistry::init('Theme');
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		// stuff to do after theme changed
		$new_theme_config = $this->ThemeRenderer->_process_theme_config_with_user_settings(true);
		$this->Theme->get_theme_background_config_values($new_theme_config, true);

		// important!  --   this makes sure that paths to helpers etc are correct after the symlinks change in change theme above
		apc_add('clear_cake_core_apc_cache_on_next_request', true);
	}
}
