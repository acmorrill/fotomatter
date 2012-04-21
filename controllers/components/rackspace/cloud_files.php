<?php
require_once(ROOT . DS . 'app' . DS . 'controllers' . DS . 'components' . DS . 'rackspace' . DS . 'rackspace_obj.php');
class CloudFilesComponent extends RackspaceObj {
    
    /**
     * list_containers
     * List all containers corresponding to the account. 
     */
    public function list_containers() {
	$url = "/";
	$response = $this->_makeApiCall('storage',$url,NULL,NULL);
	return $response;
    }

    /**
     * create_container
     * Create a new container. Normally this will be called when a site builds.
     * @var <string> container_name Name of the new container to be created
     * @return <bool> True on false.. false on error
     * //Status codes:
     * 201 container created successfully
     * 202 container already existed (still returns true for the purpose of this function)
     * 
     */
    public function create_container($container_name) {
	//container sizes cannot be more than 256 bytes...lets just make sure its less than 100 chars
	if (strlen($container_name) > 100) {
	    return false;
	}
	
	//they cannot have /
	$container_name = str_replace("/", "", $container_name);
	$url = "/$container_name";
	$response = $this->_makeApiCall('storage', $url, NULL, 'PUT');
	if (in_array($this->lastResponseStatus, array('201', '202'))) {
	    return true;
	}
	return false;
    }
    
    
    /**
     *delete_container
     *Delete a container.. probably should never be used.
     *@var <string> Mcontainer_name container to be deleted
     *@return <bool> true on success.. false on error
     *return codes:
     *204: success
     *404: container not found
     *409: container not empty
    */
    public function delete_container($container_name) {
		$url = "/$container_name";
		$response = $this->_makeApiCall('storage', $url, NULL, 'DELETE');
		//204 means success
		//404 means not found
		//409 means container not empty
                
		if (in_array($this->lastResponseStatus, array('204'))) {
			return true;
		}
		return false;
    }
    
    //get image-container-name setting from the db
    private function _getContainerName() {
		$this->SiteSetting = ClassRegistry::init("SiteSetting");
		$image_container = $this->SiteSetting->getVal('image-container-name');
		if ($image_container === false) {
			$this->SiteSetting->major_error('The cloud files component was called without a container name.');
			return false;
		}
		return $image_container;
    }
    
    /**
     * List all objects for the sites container
     * List all objects from this local sites container.. or another one if specified
     * container name @var <string> List objects from this specific container
     * @return <bool> true on success.. false on error
     */
    public function list_objects($container=false) {
		if($container === false) {
			$container = $this->_getContainerName();
			if ($container === false) return false;
		} 
		$url = "/$container";
		
		$response = $this->_makeApiCall('storage',$url,NULL,NULL);
		return $response;
    }
    
    /**
     * Gets details for a specific object
     * @param <string> The name of the object that we want to get details about
     * @param <string> Specifiy if we are looking in a different container than the default one for the site.  
     */
    public function detail_object($object_name, $container=false) {
                if ($container === false) {
                    $container = $this->_getContainerName();
                    if ($container === false) return false;
                }
		$url = "/".$container."/".$object_name;
		return $this->_getHeaders('storage', $url, 'HEAD');
    }
    
    public function get_object($object_name, $container=false) {
               if ($container === false) {
                    $container = $this->_getContainerName();
                    if ($container === false) return false;
                }
      
                $url = "/".$container."/".$object_name;
		$response = $this->_makeApiCall('storage', $url, NULL, 'GET', array(), true);
		if (in_array($this->lastResponseStatus, array('200'))) {
			return $response;
		}
		return false;
    }
    
    public function put_object($object_name, $file_path, $mime_type, $container=false) {
		if ($container === false) {
                    $container = $this->_getContainerName();
                    if ($container === false) return false;
                }
	
		$url = "/".$container."/".$object_name;
		//the postdata option in this case in extra curl optons needed for the tranfer
		$file_size = filesize($file_path);
		$options = array(
			CURLOPT_INFILE=>fopen($file_path, 'r'),
			CURLOPT_INFILESIZE=>$file_size,
			CURLOPT_CONNECTTIMEOUT=>200
		);
		$http_headers = array(
			'ETag: '.md5_file($file_path),
			"Content-Length: {$file_size}",
			"Content-Type: {$mime_type}",
			"X-Object-Meta-created-date: ".date("y-m-d H:i:s")
		);
		
		$this->_makeApiCall('storage', $url, $options, 'PUT', $http_headers, true);
		if (in_array($this->lastResponseStatus, array('201'))) {
			return true;
		}
		return false;
		//response codes
		//201 successful write
		//412 length required
		//422 checksum error
    }

    //if the container souce if different then the destination then specify it with container_source
    //DEPRECATED
    /*public function copy_object($object_name, $source, $container_source=false, $container_destination=false) {
        if ($container_destination === false) {
            $container_destination = $this->_getContainerName();
            if ($container_destination === false) return false;
        }
		$destination_url = "/".$container_destination."/".$object_name;

		if ($container_source === false) {
			$source_file = $this->_getContainerName() . "/" . $source;
		} else {
			$source_file = $container_source . "/" . $source;
		}

		$options = array(
			CURLOPT_CONNECTTIMEOUT=>200
		);

		$http_headers = array(
			"X-Copy-From: $source_file"
		);

		$this->_makeApiCall('storage', $destination_url, $options, 'PUT', $http_headers, true);
		if (in_array($this->lastResponseStatus, array('201'))) {
			return true;
		}
		return false;
	} */

		public function delete_object($object_name, $container=false) {
			if ($container === false) {
						$container = $this->_getContainerName();
						if ($container === false) return false;
					}
		$url = "/".$container."/".$object_name;
		$this->_makeApiCall('storage', $url, NULL, 'DELETE');

		if (in_array($this->lastResponseStatus, array('204'))) {
			return true;
		}
		return false;
    }
    
    public function cdn_list_containers() {
	$response =  $this->_makeApiCall('cdn', "/", NULL, NULL);
    	return $response;
    }
    
    public function cdn_detail_container($container=false) {
	if($container === false) {
	    $container = $this->_getContainerName();
	}
	$url = "/$container";
	
	return $this->_getHeaders('cdn', $url, 'HEAD');
    }
    
    /* Make a container public, probably will not be used as part of the application, but on lunarnexus when a account builds
     */ 
	public function cdn_enable_container($container) {
        $url = "/$container";

	$this->_makeApiCall('cdn', $url, NULL, 'PUT');
	if (in_array($this->lastResponseStatus, array('201'))) {
	    return true;
	}
	return false;
    } 
}
?>
