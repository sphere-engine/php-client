<?php
/**
 * Judges
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

class Judges
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
	 * all
	 *
	 * List of all judges
	 *
	 * @param int $limit limit of judges to get, default: 10, max: 100 (optional)
	 * @param int $offset offset, default: 0 (optional)
	 * @param string $type Judge type, enum: testcase|master, default: testcase (optional)
	 * @return string
	 */
	public function all($limit=10, $offset=0, $type="testcase")
	{
	    $queryParams = [
	           'limit' => $limit,
	           'offset' => $offset
	       ];
	    return $this->apiClient->callApi('/judges', 'GET', null, $queryParams, null, null);
	}
	
	/**
	 * create
	 *
	 * Create a new judge
	 *
	 * @param string $source source code (required)
	 * @param int $compiler Compiler ID, default: 1 (C++) (optional)
	 * @param string $type Judge type, testcase|master, default: testcase (optional)
	 * @param string $name Judge name, default: empty (optional)
	 * @return string
	 */
	public function create($source, $compiler=1, $type="testcase", $name="")
	{
	    $postParams = [
	        'source' => $source,
	        'compilerId' => $compiler,
	        'type' => $type,
	        'name' => $name,
	    ];
	    return $this->apiClient->callApi('/judges', 'POST', null, null, $postParams, null);
	}
	
	/**
	 * get
	 *
	 * Get judge details
	 *
	 * @param int $id Judge ID (required)
	 * @return \Swagger\Client\Model\JudgeDetails
	 */
	public function get($id)
	{
	    $urlParams = [
	        'id' => $id
	    ];
	    return $this->apiClient->callApi('/judges/{id}', 'GET', $urlParams, null, null, null);
	}
	
	/**
	 * update
	 *
	 * Update judge
	 *
	 * @param int $id Judge ID (required)
	 * @param string $source source code (optional)
	 * @param int $compiler Compiler ID (optional)
	 * @param string $name Judge name (optional)
	 * @return void
	 */
	public function update($id, $source=null, $compiler=null, $name=null)
	{
	    $urlParams = [
	        'id' => $id
	    ];
	    $postParams = [];
	    if (isset($source)) $postParams['source'] = $source;
	    if (isset($compiler)) $postParams['compilerId'] = $compiler;
	    if (isset($name)) $postParams['name'] = $name;
	    
	    return $this->apiClient->callApi('/judges/{id}', 'PUT', $urlParams, null, $postParams, null);
	}
}
