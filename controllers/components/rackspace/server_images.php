<?php
require_once(ROOT . DS . 'app' . DS . 'controllers' . DS . 'components' . DS . 'rackspace' . DS . 'rackspace_obj.php');
class ServerImagesComponent extends RackspaceObj {
    
    public function list_images($detail=false) {
        $url = "/images";
        if ($detail !== false) {
            $url .= "/detail";
        }
        $response = $this->_makeApiCall('server', $url, NULL, NULL);
        return $response;
    }
    
    public function delete_image($imageId) {
        $url = "/images/$imageId";
        $response = $this->_makeApiCall('server', $url, NULL, "delete");
        if ($this->lastResponseStatus == '204') {
            return true;
        }
        return false;
    }
    
    public function image_detail($imageId) {
        $url = "/images/$imageId";
        $response = $this->_makeApiCall('server', $url);
        if (in_array($this->lastResponseStatus, array(200, 203))) {
            return $response;
        }
        return false;
    }
    
    public function create_image($name, $serverid) {
        $url = "/images";
        $data = array(
            'image'=>array(
                'serverId'=>$serverid,
                "name"=>$name
            )
        );
        
	$jsonData = json_encode($data);
        $response = $this->_makeApiCall('server', $url, $jsonData, 'POST');
        if (isset($response['image'])) {
            return $response['image'];
        }
    
    }
}
?>
