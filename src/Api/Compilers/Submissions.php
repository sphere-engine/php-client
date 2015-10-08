<?php
/**
 * Submissions
 * 
 * PHP version 5
 *
 * @category Class
 * @package  SphereEngine\Api\Compilers 
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

namespace SphereEngine\Api\Compilers;

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
	 * @param string $source source code, default: empty (optional)
	 * @param int $compiler Compiler ID, default: 1 (C++) (optional)
	 * @param string $input data that will be given to the program on stdin, default: empty (optional)
	 * @return string
	 */
	public function create($source="", $compiler=1, $input="")
	{
	    $postParams = [
	        'sourceCode' => $source,
	        'language' => $compiler,
	        'input' => $input
	    ];
	    return $this->apiClient->callApi('/submissions', 'POST', null, null, $postParams, null);
	}
	
	/**
	 * get
	 *
	 * Fetch submission details
	 *
	 * @param int $id Submission id (required)
	 * @param bool $withSource determines whether source code of the submission should be returned, default: false (optional)
	 * @param bool $withInput determines whether input data of the submission should be returned, default: false (optional)
	 * @param bool $withOutput determines whether output produced by the program should be returned, default: false (optional)
	 * @param bool $withStderr determines whether stderr should be returned, default: false (optional)
	 * @param bool $withCmpinfo determines whether compilation information should be returned, default: false (optional)
	 * @return string
	 */
	public function get($id, $withSource=false, $withInput=false, $withOutput=false, $withStderr=false, $withCmpinfo=false)
	{
	    $urlParams = [
	      'id' => $id  
	    ];
	    $queryParams = [
	        'withSource' => $withSource,
	        'withInput' => $withInput,
	        'withOutput' => $withOutput,
	        'withStderr' => $withStderr,
	        'withCmpinfo' => $withCmpinfo
	    ];
	    return $this->apiClient->callApi('/submissions/{id}', 'GET', $urlParams, $queryParams, null, null);
	}
}
