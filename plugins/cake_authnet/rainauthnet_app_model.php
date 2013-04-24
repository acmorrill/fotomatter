<?php
class RainauthnetAppModel extends AppModel {
    
    public $ActsAs = array(
        'Containable'
    );
    
    /**
     * include config file for plugin
     */
    
    public function __construct() {
        require_once(ROOT.'/app/plugins/rainauthnet/vendors/Authorize.Net-XML/AuthnetXML.class.php');
        
        if (is_file(ROOT . '/app/plugins/rainauthnet/config/local.php')) {
            require_once(ROOT . '/app/plugins/rainauthnet/config/local.php');
        }
        parent::__construct();
    }
    
    /**
     * create and instance of the AuthnetXML.class
     */
    public function get_authnet_instance() {
        $auth_net_args = Configure::read('rainauthnet');
        if ($auth_net_args['is_dev']) {
            return new AuthnetXML($auth_net_args['id'], $auth_net_args['key'], AUTHNETXML::USE_DEVELOPMENT_SERVER);
        } else {
            return new AuthnetXML($auth_net_args['id'], $auth_net_args['key'], AUTHNETXML::USE_PRODUCTION_SERVER);
        }
    }
    
    public function authnet_error($description, $extra_data = null, $severity = 'normal') {
            $stackTrace = debug_backtrace(false);

            $majorError = ClassRegistry::init("AuthnetError");

            $location = '';
            if (isset($stackTrace[1]['class'])) {
                    $location .= " --- Class: ".$stackTrace[1]['class']." --- ";
            }
            if (isset($stackTrace[1]['function'])) {
                    $location .= " --- Function: ".$stackTrace[1]['function']." --- ";
            }
            $data['AuthnetError']['location'] = $location;
            $data['AuthnetError']['line_num'] = isset($stackTrace[1]['line']) ? $stackTrace[1]['line']: 1;
            $data['AuthnetError']['description'] = $description;
            if ($extra_data != null) {
                    $data['AuthnetError']['extra_data'] = print_r($extra_data, true);
            }
            $data['AuthnetError']['severity'] = $severity;
            $majorError->create();
            $majorError->save($data);        
        }
    
}