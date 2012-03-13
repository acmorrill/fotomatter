<?php
require_once(ROOT . DS . 'app' . DS . 'controllers' . DS . 'components' . DS . 'rackspace' . DS . 'rackspace_obj.php');
class ServerToolsComponent extends RackspaceObj {
    
    public function list_servers($detail=false) {
        if ($this->_is_authenticated() === false) {
            $this->_authenticate();
        }
        
        $url = '/servers';
        if ($detail) {
            $url .= '/detail';
        }
        
        $result = $this->_makeApiCall('server', $url, NULL, NULL);
        return $result;
    }
    
}
?>