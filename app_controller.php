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
		'MobileDetect',
		'Validation',
		'Postmark',
		'FotomatterEmail',
		'FotomatterBilling',
		'FeatureLimiter',
	);
	
	public $helpers = array(
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
		'Cache'
	);
	
	
	/**
	* beforeFilter
	*
	* Application hook which runs prior to each controller action
	*
	* @access public
	*/
	function beforeFilter() {
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

		
		//////////////////////////////////////////////////////
		// stuff todo just in the admin
		if (isset($this->params['admin']) && $this->params['admin'] == 1) {
			clearCache();
		} 
		
		// DREW TODO - for testing only!
		if (Configure::read('debug') > 0 && !$this->Session->check('Message.flash')) {
			$this->Session->setFlash('If you do not see this on a page that page is not outputting any flash messages and there also is no flash message to display. For testing only.', 'admin/flashMessage/success');
		}
		
		
		
		///////////////////////////////////////////////////////
		// setup mobile settings for mobile theming
		$this->is_mobile = false;
		if (!empty($this->current_on_off_features['mobile_theme']) && $this->MobileDetect->isMobile()) {
			$this->is_mobile = true;
			//$this->autoRender = false;
		}
//		$this->is_mobile = true; // DREW TODO - remove this
		$this->set('is_mobile', $this->is_mobile);
		
		
		
		// recompile less css if a get param is set or debug is set to 2
		if (Configure::read('debug') == '2' || isset($this->params['url']['lesscss']) || $this->Session->check('recompile_css')) {
			if (isset($this->params['url']['lesscss'])) {
				$this->Session->write('recompile_css', true);
			}
			$this->LessCss->recompile_css();
		}
		
		
		// locking hash code
		if (isset($this->params['url']['ajax_autoredirect'])) {
			$this->Session->write('Auth.redirect', $this->params['url']['ajax_autoredirect']);
		}
		
		//Override default fields used by Auth component
		$this->Auth->fields = array('username' => 'email_address', 'password' => 'password');
		//Set application wide actions which do not require authentication
		$this->Auth->allow('display', 'view');//IMPORTANT for CakePHP 1.2 final release change this to $this->Auth->allow(array('display'));
		//$this->Auth->allow(array('*'));//IMPORTANT for CakePHP 1.2 final release change this to $this->Auth->allow(array('display'));
		//Set the default redirect for users who logout
		$this->Auth->logoutRedirect = '/admin';
		//Set the default redirect for users who login
		$this->Auth->loginRedirect = '/admin/dashboards/index';
		//Extend auth component to include authorisation via isAuthorized action
		$this->Auth->authorize = 'controller';
		//Restrict access to only users with an active account
		$this->Auth->userScope = array('User.active = 1');
		//Pass auth component data over to view files
		$this->set('Auth', $this->Auth->user());
		
		
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
	}
	
	public function validatePaymentProfile() {
		$this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_firstname', __('You must provide your first name.', true));
		$this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_lastname', __('You must provide your last name.', true));
		$this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_address', __('You must provide your address.', true));
		$this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_city', __('You must provide your city.', true));

		$this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'billing_zip', __('You must provide your zip code.', true));
		$this->Validation->validate('valid_cc_no_type', $this->data['AuthnetProfile']['payment_cardNumber'], 'billing_cardNumber', __('Your credit card was not entered or not in a valid format.', true));
		$this->data['AuthnetProfile']['str_date'] =  $this->data['AuthnetProfile']['expiration']['month'] . '/31' . '/' . $this->data['AuthnetProfile']['expiration']['year'];
		$this->Validation->validate('date_is_future', $this->data['AuthnetProfile'], 'str_date', __('Your date provided was invalid or not in the future.', true)); //Ok in theory this should never be hit cause they are selects
		$this->Validation->validate('not_empty', $this->data['AuthnetProfile'], 'payment_cardCode', __('Your csv code was either blank or invalid.', true));
	}
	
	public function send_overlord_api_request($api, $params=array()) {
		$request['Request']['data'] = $params;
        
		$url_to_use = $this->server_url . '/' .$api;
		$request['Request']['Server_params']['url'] = $url_to_use;
		$time_stamp = (string) time();
		$request['Request']['Server_params']['time_stamp'] = $time_stamp;

		$this->SiteSetting = ClassRegistry::init("SiteSetting");
		$site_key = $this->SiteSetting->getVal('site_domain');
		$request['Request']['key'] = $site_key;
		$request['Access']['signature'] = hash_hmac('sha256', json_encode($request['Request']), OVERLORD_API_KEY);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL , $url_to_use);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',
			'API_SIGNATURE: '.$request['Access']['signature']
		));
		$response = curl_exec($ch);
		curl_close($ch);
				
   //     $this->log($request['Request'], 'client_billing');
		return $response;
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
		foreach($extra_vals as $key => $extra_val) {
			$view->set($key, $extra_val);
		}
		
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
	
}

