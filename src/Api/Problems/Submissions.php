<?php
/**
 * Submissions
 * 
 * PHP version 5
 *
 * @category Class
 * @package  SphereEngine\Api\Problems 
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

namespace SphereEngine\Api\Problems;

use \SphereEngine\ApiClient;

class Submissions
{
    /**
     * API Client
     * @var \SphereEngine\ApiClient instance of the ApiClient
     */
    private $apiClient;
    
	function __construct($apiClient)
	{
	    $this->apiClient = $apiClient;
	}
	
	/**
	 * create
	 *
	 * Create a new submission
	 *
	 * @param string $problemCode Problem code (required)
	 * @param string $source source code (required)
	 * @param int $compiler Compiler ID (required)
	 * @param int $user User ID, default: account owner user (optional)
	 * @return string
	 */
	public function create($problemCode, $source, $compiler, $user=null)
	{
	    $postParams = [
	        'problemCode' => $problemCode,
	        'compilerId' => $compiler,
	        'source' => $source
	    ];
	    if (isset($user)) $postParams['userId'] = $user;
	    return $this->apiClient->callApi('/submissions', 'POST', null, null, $postParams, null);
	}
	
	/**
	 * get
	 *
	 * Fetch submission details
	 *
	 * @param string $id Submission ID (required)
	 * @return string
	 */
	public function get($id)
	{
	    $urlParams = [
	      'id' => $id  
	    ];
	    return $this->apiClient->callApi('/submissions/{id}', 'GET', $urlParams, null, null, null);
	}
}
