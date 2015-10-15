<?php
/**
 * Problems
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

class Problems
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
	 * List of all problems
	 *
	 * @param int $limit limit of problems to get, default: 10, max: 100 (optional)
	 * @param int $offset offset, default: 0 (optional)
	 * @return string
	 */
	public function all($limit=10, $offset=0)
	{
	    $queryParams = [
	        'limit' => $limit,
	        'offset' => $offset
	    ];
	    return $this->apiClient->callApi('/problems', 'GET', null, $queryParams, null, null);
	}
	
	/**
	 * create
	 *
	 * Create a new problem
	 *
	 * @param string $code Problem code (required)
	 * @param string $name Problem name (required)
	 * @param string $body Problem body (optional)
	 * @param string $type Problem type, enum: binary|min|max, default: binary (optional)
	 * @param bool $interactive interactive problem flag, default: 0 (optional)
	 * @param int $masterjudge_id Masterjudge ID, default: 1001 (i.e. Score is % of correctly solved testcases) (optional)
	 * @return string
	 */
	public function create($code, $name, $body="", $type="binary", $interactive=0, $masterjudgeId=1001)
	{
	    $postParams = [
	       'code' => $code,
	       'name' => $name,
	       'body' => $body,
	       'type' => $type,
	       'interactive' => $interactive,
	       'masterjudgeId' => $masterjudgeId
	    ];
	    return $this->apiClient->callApi('/problems', 'POST', null, null, $postParams, null);
	}
	
	/**
	 * get
	 *
	 * Retrieve an existing problem
	 *
	 * @param string $code Problem code (required)
	 * @return string
	 */
	public function get($code)
	{
	    $urlParams = [
	        'code' => $code
	    ];
	    return $this->apiClient->callApi('/problems/{code}', 'GET', $urlParams, null, null, null);
	}
	
	/**
	 * update
	 *
	 * Update an existing problem
	 *
	 * @param string $code Problem code (required)
	 * @param string $name Problem name (optional)
	 * @param string $body Problem body (optional)
	 * @param string $type Problem type, enum: binary|min|max, default: binary (optional)
	 * @param bool $interactive interactive problem flag (optional)
	 * @param int $masterjudgeId Masterjudge ID (optional)
	 * @param int[] $activeTestcases list of active testcases IDs (optional)
	 * @return void
	 */
	public function update($code, $name=null, $body=null, $type=null, $interactive=null, $masterjudgeId=null, $activeTestcases=null)
	{
	    $urlParams = [
	       'code' => $code  
	    ];
	    $postParams = [];
	    if (isset($name)) $postParams['name'] = $name;
	    if (isset($body)) $postParams['body'] = $body;
	    if (isset($type)) $postParams['type'] = $type;
	    if (isset($interactive)) $postParams['interactive'] = $interactive;
	    if (isset($masterjudgeId)) $postParams['masterjudgeId'] = $masterjudgeId;
	    if (isset($activeTestcases) && is_array($activeTestcases)) $postParams['activeTestcases'] = implode(',', $activeTestcases);

	    return $this->apiClient->callApi('/problems/{code}', 'PUT', $urlParams, null, $postParams, null);
	}
	
	/**
	 * updateActiveTestcases
	 *
	 * Update active testcases related to the problem
	 *
	 * @param string $problemCode Problem code (required)
	 * @param int[] $activeTestcases Active testcases (required)
	 * @return void
	 */
	public function updateActiveTestcases($problemCode, $activeTestcases)
	{
	     return $this->update($problemCode, null, null, null, null, null, $activeTestcases);
	}
	
	/**
	 * allTestcases
	 *
	 * Retrieve list of Problem testcases
	 *
	 * @param string $problemCode Problem code (required)
	 * @return string
	 */
	public function allTestcases($problemCode)
	{
	    $urlParams = [
	        'problemCode' => $problemCode
	    ];
	    return $this->apiClient->callApi('/problems/{problemCode}/testcases', 'GET', $urlParams, null, null, null);
	}
	
	/**
	 * createTestcase
	 *
	 * Create a problem testcase
	 *
	 * @param string $problemCode Problem code (required)
	 * @param string $input input data, default: empty (optional)
	 * @param string $output output data, default: empty (optional)
	 * @param float $timelimit time limit in seconds, default: 1 (optional)
	 * @param int $judgeId Judge ID, default: 1 (Ignore extra whitespaces) (optional)
	 * @param bool $active if test should be active, default: true (optional)
	 * @return string
	 */
	public function createTestcase($problemCode, $input="", $output="", $timelimit=1, $judgeId=1, $active=true)
	{
	    $urlParams = [
	        'problemCode' => $problemCode
	    ];
	    $postParams = [
	        'input' => $input,
	        'output' => $output,
	        'timelimit' => $timelimit,
	        'judgeId' => $judgeId,
	        'active' => $active
	    ];
	    return $this->apiClient->callApi('/problems/{problemCode}/testcases', 'POST', $urlParams, null, $postParams, null);
	}
	
	/**
	 * getTestcase
	 *
	 * Retrieve problem testcase
	 *
	 * @param string $problemCode Problem code (required)
	 * @param int $number Testcase number (required)
	 * @return string
	 */
	public function getTestcase($problemCode, $number)
	{
	    $urlParams = [
	        'problemCode' => $problemCode,
	        'number' => $number
	    ];
	    return $this->apiClient->callApi('/problems/{problemCode}/testcases/{number}', 'GET', $urlParams, null, null, null);
	}
	
	/**
	 * updateTestcase
	 *
	 * Update the problem testcase
	 *
	 * @param string $problemCode Problem code (required)
	 * @param int $number Testcase number (required)
	 * @param string $input input data (optional)
	 * @param string $output output data (optional)
	 * @param float $timelimit time limit in seconds (optional)
	 * @param int $judgeId Judge ID (optional)
	 * @return void
	 */
	public function updateTestcase($problemCode, $number, $input=null, $output=null, $timelimit=null, $judgeId=null)
	{
	    $urlParams = [
	        'problemCode' => $problemCode,
	        'number' => $number
	    ];
	    $postParams = [];
	    if (isset($input)) $postParams['input'] = $input;
	    if (isset($output)) $postParams['output'] = $output;
	    if (isset($timelimit)) $postParams['timelimit'] = $timelimit;
	    if (isset($judgeId)) $postParams['judgeId'] = $judgeId;
	    
	    return $this->apiClient->callApi('/problems/{problemCode}/testcases/{number}', 'PUT', $urlParams, null, $postParams, null);
	}
	
	/**
	 * getTestcaseFile
	 *
	 * Retrieve Problem testcase file
	 *
	 * @param string $problemCode Problem code (required)
	 * @param int $number Testcase number (required)
	 * @param string $filename stream name (required)
	 * @return file
	 */
	public function getTestcaseFile($problemCode, $number, $filename)
	{
	    $urlParams = [
	        'problemCode' => $problemCode,
	        'number' => $number,
	        'filename' => $filename
	    ];
	    return $this->apiClient->callApi('/problems/{problemCode}/testcases/{number}/{filename}', 'GET', $urlParams, null, null, null, 'file');   
	}
}
