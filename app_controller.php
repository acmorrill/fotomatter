<?php
class AppController extends Controller {
    /**
     * components
     *
     * Array of components to load for every controller in the application
     *
     * @var $components array
     * @access public
     */
    var $components = array('Auth', 'Session');
	
	
	
    /**
     * beforeFilter
     *
     * Application hook which runs prior to each controller action
     *
     * @access public
     */
    function beforeFilter() {
		if (isset($this->params['url']['ajax_autoredirect'])) {
			$this->Session->write('Auth.redirect', $this->params['url']['ajax_autoredirect']);
		}
		
        //Override default fields used by Auth component
        $this->Auth->fields = array('username'=>'email_address','password'=>'password');
        //Set application wide actions which do not require authentication
        $this->Auth->allow('display', 'view');//IMPORTANT for CakePHP 1.2 final release change this to $this->Auth->allow(array('display'));
        //$this->Auth->allow(array('*'));//IMPORTANT for CakePHP 1.2 final release change this to $this->Auth->allow(array('display'));
        //Set the default redirect for users who logout
        $this->Auth->logoutRedirect = '/';
        //Set the default redirect for users who login
        $this->Auth->loginRedirect = '/admin/dashboards/index';
        //Extend auth component to include authorisation via isAuthorized action
        $this->Auth->authorize = 'controller';
        //Restrict access to only users with an active account
        $this->Auth->userScope = array('User.active = 1');
        //Pass auth component data over to view files
        $this->set('Auth',$this->Auth->user());
    }
    /**
     * beforeRender
     *
     * Application hook which runs after each action but, before the view file is
     * rendered
     *
     * @access public
     */
    function beforeRender(){
        //If we have an authorised user logged then pass over an array of controllers
        //to which they have index action permission
        if($this->Auth->user()){
            $controllerList = Configure::listObjects('controller');
            $permittedControllers = array();
            foreach($controllerList as $controllerItem){
                if($controllerItem <> 'App'){
                    if($this->__permitted($controllerItem,'index')){
                        $permittedControllers[] = $controllerItem;
                    }
                }
            }
        }
        $this->set(compact('permittedControllers'));
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
    function isAuthorized(){
		$value = $this->__permitted($this->name,$this->action);
        return $value;
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
    function __permitted($controllerName,$actionName){
        //Ensure checks are all made lower case
        $controllerName = low($controllerName);
        $actionName = low($actionName);
        //If permissions have not been cached to session...
        if(!$this->Session->check('Permissions')){
            //...then build permissions array and cache it
            $permissions = array();
            //everyone gets permission to logout
            $permissions[]='users:logout';
            //Import the User Model so we can build up the permission cache
            App::import('Model', 'User');
            $thisUser = new User;
            //Now bring in the current users full record along with groups
            $thisGroups = $thisUser->find(array('User.id'=>$this->Auth->user('id')));
            $thisGroups = $thisGroups['Group'];
            foreach($thisGroups as $thisGroup){
                $thisPermissions = $thisUser->Group->find(array('Group.id'=>$thisGroup['id']));
                $thisPermissions = $thisPermissions['Permission'];
                foreach($thisPermissions as $thisPermission){
                    $permissions[]=$thisPermission['name'];
                }
            }
            //write the permissions array to session
            $this->Session->write('Permissions',$permissions);
        }else{
            //...they have been cached already, so retrieve them
            $permissions = $this->Session->read('Permissions');
        }
        //Now iterate through permissions for a positive match
        foreach($permissions as $permission){
            if($permission == '*'){
                return true;//Super Admin Bypass Found
            }
            if($permission == $controllerName.':*'){
                return true;//Controller Wide Bypass Found
            }
            if($permission == $controllerName.':'.$actionName){
                return true;//Specific permission found
            }
        }
        return false;
    }
	
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
		return $view->render($element_path);
	}
	
	public function return_json($data) {
		echo json_encode($data);
		exit();
	}
}

