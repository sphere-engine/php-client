<?php
/**
 * ApiClient
 * 
 * PHP version 5
 *
 * @category Class
 * @package  SphereEngine\Api
 * @author   https://github.com/sphere-engine/sphereengine-api-php-client
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/sphere-engine/sphereengine-api-php-client
 */
/**
 *  Copyright 2015 Sphere Research Sp z o.o.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace SphereEngine\Api;

use SphereEngine\Api\Model\HttpApiResponse;

class ApiClient
{
	protected $baseUrl;
	protected $accessToken;
	protected $userAgent;
	public static $PROTOCOL = "http";
	
    /**
     * Constructor
     * @param string $accessToken Access token to Sphere Engine service
     * @param string $version version of the API
     * @param string $endpoint link to the endpoint
     */
	function __construct($accessToken, $endpoint)
	{
		$this->accessToken = $accessToken;
		$this->baseUrl = $this->buildBaseUrl($endpoint);
		$this->userAgent = "SphereEngine";
	}
	
	protected function buildBaseUrl($endpoint)
	{
		return self::$PROTOCOL . '://' . $endpoint;
	}
	
	/**
	 * Make the HTTP call (Sync)
	 * @param string $resourcePath path to method endpoint
	 * @param string $method       method to call
	 * @param array  $queryParams  parameters to be place in query URL
	 * @param array  $postData     parameters to be placed in POST body
	 * @param array  $headerParams parameters to be place in request header
	 * @param string $responseType expected response type of the endpoint
	 * @throws \SphereEngine\SphereEngineResponseException on a non 4xx response
	 * @throws \SphereEngine\SphereEngineConnectionException on a non 5xx response
	 * @return mixed
	 */
	public function callApi($resourcePath, $method, $urlParams, $queryParams, $postData, $headerParams, $responseType=null)
	{
		$httpResponse = $this->makeHttpCall($resourcePath, $method, $urlParams, $queryParams, $postData, $headerParams);
		$response = $this->processResponse($httpResponse, $responseType);
		return $response;
	}

	/**
	 * Process response data from HTTP callApi
	 * @param HttpApiResponse 	$response 	response from http api callApi
	 * @throws \SphereEngine\SphereEngineResponseException on a non 4xx response
	 * @throws \SphereEngine\SphereEngineConnectionException on a non 5xx response
	 * @return mixed
	 */
	protected function processResponse($response, $responseType)
	{
		// curl connection errors (invalid port, connection refused, timeout)
		if (in_array($response->curlErrno, [3, 7, 28])) {
			throw new SphereEngineConnectionException($response->curlError, 0);
		}
	    
		// sphere engine errors
	    if ($response->httpCode >= 400 && $response->httpCode <= 499) {
	        throw new SphereEngineResponseException(json_decode($response->httpBody, true)['message'], $response->httpCode);
	    } elseif ($response->httpCode >= 500 && $response->httpCode <= 599) {
	        throw new SphereEngineConnectionException('Connection error', $response->httpCode);
	    }

		// sphere engine success
		if ($response->httpCode >= 200 && $response->httpCode <= 299) {
			return ($responseType == 'file') ? $response->httpBody : json_decode($response->httpBody, true);
		}

		// other errors
		throw new SphereEngineConnectionException('Unknown error', $response->httpCode);
	}

	/**
	 * Make the HTTP call
	 * @param string $resourcePath path to method endpoint
	 * @param string $method       method to call
	 * @param array  $queryParams  parameters to be place in query URL
	 * @param array  $postData     parameters to be placed in POST body
	 * @param array  $headerParams parameters to be place in request header
	 * @param string $responseType expected response type of the endpoint
	 * @return mixed
	 */
	protected function makeHttpCall($resourcePath, $method, $urlParams, $queryParams, $postData, $headerParams)
	{
	    $headers = array();
	
	    // construct the http header
	    $headerParams = array(
	       'Content-Type' => 'application/x-www-form-urlencoded' 
        );
	    
	    foreach ($headerParams as $key => $val) {
	        $headers[] = "$key: $val";
	    }
	
	    // fill url params with proper values
	    if (is_array($urlParams)) {
    	    foreach($urlParams as $param => $value) {
    	        $resourcePath = str_replace("{" . $param . "}", $value, $resourcePath);
    	    }
	    }
	    
	    // create a complete url
	    $url = $this->baseUrl . $resourcePath;
	
	    $curl = curl_init();
	    
		// set timeout
		curl_setopt($curl, CURLOPT_TIMEOUT, 4);
	    
		/* TODO: make proper placement for timeout 
	    if ($this->config->getCurlTimeout() != 0) {
	        curl_setopt($curl, CURLOPT_TIMEOUT, $this->config->getCurlTimeout());
	    }
	    */
	    // return the result on success, rather than just TRUE
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	
	    $queryParams['access_token'] = $this->accessToken;
	    if (! empty($queryParams)) {
	        $url = ($url . '?' . http_build_query($queryParams, '', '&'));
	    }
		
	    if (is_array($postData)) {
	       $postData = http_build_query($postData, '', '&');
	    }
	    
	    if ($method == 'POST') {
	        curl_setopt($curl, CURLOPT_POST, true);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	    } else if ($method == 'PUT') {
	        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	    } else if ($method == 'DELETE') {
	        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	    } else if ($method != 'GET') {
	        throw new \Exception('Method ' . $method . ' is not recognized.');
	    }
	    curl_setopt($curl, CURLOPT_URL, $url);
	
	    // Set user agent
	    curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
	
	    // quiet mode
	    curl_setopt($curl, CURLOPT_VERBOSE, 0);
	
	    // obtain the HTTP response headers
	    curl_setopt($curl, CURLOPT_HEADER, 1);
	    
	    // disable https verification
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	
	    // Make the request
	    $response = curl_exec($curl);
	    $http_header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	    $http_header = substr($response, 0, $http_header_size);
	    $http_body = substr($response, $http_header_size);
	    $response_info = curl_getinfo($curl);

		return new HttpApiResponse($response_info['http_code'], $http_header, $http_body, curl_errno($curl), curl_error($curl));
	}
}
