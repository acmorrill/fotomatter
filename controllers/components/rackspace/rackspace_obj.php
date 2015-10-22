<?php

/**
 *  Parent class for all rackspace objects. This is currently based off of the 1.0 api, which is currently deprecated.
 *  They don't really have documentation for any of the further apis, so I am going to use this for now. I used this library for
 *  reference: https://github.com/eyecreate/Rackspace-Cloud-PHP-Library
 *
 */
class RackspaceObj extends Object {

	/**
	 * @var access token to connect to the api
	 */
	protected $_access_token = false;

	/**
	 * @var storage token for cloud files
	 */
	protected $_storage_token = false;

	/**
	 * @var apiEndPoint (array) array of endponts(urls used to perform specific api functions)
	 */
	protected $_apiEndPoint;

	/**
	 * @var lastResponseStatus  contains last response 
	 */
	protected $lastResponseStatus = array();

	/**
	 * initial authentication to the api
	 */
	protected function _authenticate() {
		$this->ServerSetting = ClassRegistry::init("ServerSetting");
		$username = $this->ServerSetting->getVal("rackspace_api_username", false);

		$key = $this->ServerSetting->getVal("rackspace_api_key", false);
		if ($username == false || $key == false) {
			$this->ServerSetting->major_error("Rackspace credentials missing.");
			return false;
		}

		$authUrl = "https://auth.api.rackspacecloud.com/v1.0";
		$authHeaders = array(
			"X-Auth-User: {$username}",
			"X-Auth-Key: {$key}",
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $authUrl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $authHeaders);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_CAPATH, '/etc/ssl/certs');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		if (Configure::read('debug') > 0) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		}
		$response = curl_exec($ch);

		curl_close($ch);
		if (preg_match("/^HTTP\/1.1 401 Unauthorized/", $response)) {
			$this->ServerSetting->major_error("Rackspace credentials invalid");
			return false;
		}

		if (preg_match("/^HTTP\//", $response)) {
			preg_match("/X-Auth-Token: (.*)/", $response, $matches);
			$this->_access_token = $matches[1];

			preg_match("/X-Server-Management-Url: (.*)/", $response, $matches);
			if (!empty($matches[1])) {
				$this->_apiEndPoint['server'] = $matches[1];
			}

			preg_match("/X-Storage-Url: (.*)/", $response, $matches);
			$this->_apiEndPoint['storage'] = $matches[1];

			preg_match("/X-Storage-Token: (.*)/", $response, $matches);
			$this->_storage_token = $matches[1];

			preg_match("/X-CDN-Management-Url: (.*)/", $response, $matches);
			$this->_apiEndPoint['cdn'] = $matches[1];
		}

		return true;
	}

	protected function _is_authenticated() {
		return ($this->_access_token);
	}

	/**
	 * This does not really have any use.. but to provide a public method for unit tests.
	 */
	public function test_authenticate() {
		if ($this->_is_authenticated() === false) {
			return $this->_authenticate();
		}
	}

	/**
	 * curl function used only for HEAD operations
	 */
	protected function _getHeaders($apiEndpointType, $url, $method) {
		// Authenticate if necessary
		if (!$this->_is_authenticated()) {
			if (!$this->_authenticate()) {
				return false;
			}
		}
		$jsonUrl = trim($this->_apiEndPoint[$apiEndpointType]) . $url;

		$httpHeaders = array(
			"X-Auth-Token: " . trim($this->_access_token)
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $jsonUrl);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_HEADERFUNCTION, array(&$this, 'parseHeader'));
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_CAPATH, '/etc/ssl/certs');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		if (Configure::read('debug') > 0) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		}

		$response_headers = curl_exec($ch);
		$returnArgs = array();
		foreach (explode("\n", $response_headers) as $header) {
			if (preg_match("/X-(.*?)\:[\s]*(.*?)$/i", $header, $matches)) {
				if ($matches[2] == 'True')
					$matches[2] = true;
				if ($matches[2] == 'False')
					$matches[2] = false;

				$returnArgs[$matches[1]] = $matches[2];
			}
		}

		curl_close($ch);
		return $returnArgs;
	}

	/**
	 * Send api calls to
	 */
	protected function _makeApiCall($apiEndpointType, $url, $postData = NULL, $method = NULL, $extra_http_headers = array(), $raw_output = false) {
		// Authenticate if necessary
		if (!$this->_is_authenticated()) {
			if (!$this->_authenticate()) {
				return false;
			}
		}
		$jsonUrl = trim($this->_apiEndPoint[$apiEndpointType]) . $url;
		if (($apiEndpointType == 'storage' || $apiEndpointType == 'cdn') && $method != 'PUT') {
			$jsonUrl .= "?format=json";
		}
		$httpHeaders = array(
			"X-Auth-Token: " . trim($this->_access_token)
		);
		if (empty($extra_http_headers) === false) {
			$httpHeaders = array_merge($httpHeaders, $extra_http_headers);
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $jsonUrl);
		if ($postData && $method == 'PUT') {
			curl_setopt_array($ch, $postData);
		}
		if ($postData && $method == 'POST') {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			$httpHeaders[] = "Content-Type: application/json";
		}
		if ($method) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
			if ($method == 'PUT') {
				curl_setopt($ch, CURLOPT_PUT, true);
			}
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HEADERFUNCTION, array(&$this, 'parseHeader'));
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$jsonResponse = curl_exec($ch);
		curl_close($ch);

		if ($raw_output) {
			return $jsonResponse;
		}

		// var_dump($jsonResponse);
		$js = json_decode($jsonResponse, TRUE);
		return $js;
	}

	private function parseHeader($ch, $header) {
		preg_match("/^HTTP\/1\.[01] (\d{3}) (.*)/", $header, $matches);
		if (isset($matches[1])) {
			$this->lastResponseStatus = $matches[1];
		}

		return strlen($header);
	}

}

?>
