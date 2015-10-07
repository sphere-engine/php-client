<?php
/**
 * ApiClient
 * 
 * PHP version 5
 *
 * @category Class
 * @package  SphereEngine 
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

namespace SphereEngine;

class ApiClient
{
	private $baseUrl;
	private $accessToken;
	private $userAgent;
	public static $PROTOCOL = "http";
	
	
    /**
     * Constructor
     * @param string $accessToken Access token to Sphere Engine service
     * @param string $version version of the API
     * @param string $endpoint link to the endpoint
     */
	function __construct($accessToken, $version, $endpoint)
	{
		$this->accessToken = $accessToken;
		$this->baseUrl = $this->buildBaseUrl($version, $endpoint);
		$this->userAgent = "SphereEngine";
	}
	
	private function buildBaseUrl($version, $endpoint)
	{
		return self::$PROTOCOL . '://' . $endpoint . '/' . $version;
	}
	
	/**
	 * Make the HTTP call (Sync)
	 * @param string $resourcePath path to method endpoint
	 * @param string $method       method to call
	 * @param array  $queryParams  parameters to be place in query URL
	 * @param array  $postData     parameters to be placed in POST body
	 * @param array  $headerParams parameters to be place in request header
	 * @param string $responseType expected response type of the endpoint
	 * @return mixed
	 */
	public function callApi($resourcePath, $method, $queryParams, $postData, $headerParams, $responseType=null)
	{
	    $headers = array();
	
	    // construct the http header
	    $headerParams = array(
	       'Content-Type' => 'application/json' 
        );
	    
	    foreach ($headerParams as $key => $val) {
	        $headers[] = "$key: $val";
	    }
	
	    /* TODO: ustalic czy w ogole nam potrzbne te opcje
	    // form data
	    if ($postData and in_array('Content-Type: application/x-www-form-urlencoded', $headers)) {
	        $postData = http_build_query($postData);
	    } else if ((is_object($postData) or is_array($postData)) and !in_array('Content-Type: multipart/form-data', $headers)) { // json model
	        $postData = json_encode($this->serializer->sanitizeForSerialization($postData));
	    }
	    */
	
	    // pamietac o "/" w resource path
	    $url = $this->baseUrl . $resourcePath;
	
	    $curl = curl_init();
	    // set timeout, if needed
	    /* TODO: dobrze umiejscowiæ ten timeout w kodzie
	    if ($this->config->getCurlTimeout() != 0) {
	        curl_setopt($curl, CURLOPT_TIMEOUT, $this->config->getCurlTimeout());
	    }
	    */
	    // return the result on success, rather than just TRUE
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	
	    $queryParams['access_token'] = $this->accessToken;
	    if (! empty($queryParams)) {
	        $url = ($url . '?' . http_build_query($queryParams));
	    }
	    
	    if ($method == 'POST') {
	        curl_setopt($curl, CURLOPT_POST, true);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	    /* TODO: Spytaæ Micha³a czy kiedykolwiek mo¿emy u¿ywaæ tych poleceñ
	    } else if ($method == self::$HEAD) {
	        curl_setopt($curl, CURLOPT_NOBODY, true);
	    } else if ($method == self::$OPTIONS) {
	        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "OPTIONS");
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	    } else if ($method == self::$PATCH) {
	        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	    */
	    } else if ($method == 'PUT') {
	        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	    } else if ($method == 'DELETE') {
	        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
	    } else if ($method != 'GET') {
	        //throw new \Exception('Method ' . $method . ' is not recognized.');
	    }
	    curl_setopt($curl, CURLOPT_URL, $url);
	
	    // Set user agent
	    curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
	
	    // quiet mode
	    curl_setopt($curl, CURLOPT_VERBOSE, 0);
	
	    // obtain the HTTP response headers
	    curl_setopt($curl, CURLOPT_HEADER, 1);
	
	    // Make the request
	    $response = curl_exec($curl);
	    $http_header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	    $http_header = substr($response, 0, $http_header_size);
	    $http_body = substr($response, $http_header_size);
	    $response_info = curl_getinfo($curl);
	    
	    // Handle the response
	    if ($response_info['http_code'] == 0) {
	        //throw new \Exception("API call to $url timed out: ".serialize($response_info), 0, null, null);
	    } else if ($response_info['http_code'] >= 200 && $response_info['http_code'] <= 299 ) {
	        // return raw body if response is a file
	        if ($responseType == '\SplFileObject') {
	            return array($http_body, $http_header);
	        }
	
	        $data = json_decode($http_body);
	        if (json_last_error() > 0) { // if response is a string
	            $data = $http_body;
	        }
	    } else {
	        //throw new \Exception(
	        //    "[".$response_info['http_code']."] Error connecting to the API ($url)",
	        //    $response_info['http_code'], $http_header, $http_body
	        //);
	    }
	    return array($data, $http_header);
	}
}