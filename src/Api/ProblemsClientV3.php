<?php
/**
 * ProblemsClientV3
 * 
 * PHP version 5
 *
 * @category Class
 * @package  SphereEngine\Api
 * @author   https://github.com/sphere-engine/php-client
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/sphere-engine/php-client
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

class ProblemsClientV3
{
    /**
     * API Client
     * @var \SphereEngine\ApiClient instance of the ApiClient
     */
	private $apiClient;
	
	/**
	 * API module
	 * @var String module name of the API
	 */
	private $module = 'problems';
	
	/**
	 * API version
	 * @var String version of the API
	 */
	private $version = 'v3';

    /**
     * Constructor
     * @param string $accessToken Access token to Sphere Engine service
     * @param string $endpoint link to the endpoint
     */
	function __construct($accessToken, $endpoint)
	{
		$this->apiClient = new ApiClient($accessToken, $this->createEndpointLink($endpoint));
	}
	
	/**
	 * createEndpointLink
	 * 
	 * @param string $endpoint Sphere Engine Problems endpoint
	 * @return string
	 */
	private function createEndpointLink($endpoint)
	{
		if (strpos($endpoint, '.') === false) {
			return $endpoint . '.' . $this->module . '.sphere-engine.com/api/' . $this->version;
		} else {
			return $endpoint . '/api/' . $this->version;
		}
	}
	
	/**
	 * test
	 *
	 * Test method
	 *
	 * @return string
	 */
	public function test()
	{
		return $this->apiClient->callApi('/test', 'GET', null, null, null, null);
	}

	/**
	 * compilers
	 *
	 * List of all compilers
	 *
	 * @return array
	 */	
	public function getCompilers()
	{
	    return $this->apiClient->callApi('/compilers', 'GET', null, null, null, null);
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
	public function getJudges($limit=10, $offset=0, $type="testcase")
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
	public function createJudge($source, $compiler=1, $type="testcase", $name="")
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
	public function getJudge($id)
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
	public function updateJudge($id, $source=null, $compiler=null, $name=null)
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
	
	/**
	 * all
	 *
	 * List of all problems
	 *
	 * @param int $limit limit of problems to get, default: 10, max: 100 (optional)
	 * @param int $offset offset, default: 0 (optional)
	 * @return string
	 */
	public function getProblems($limit=10, $offset=0)
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
	 * @param int $masterjudgeId Masterjudge ID, default: 1001 (i.e. Score is % of correctly solved testcases) (optional)
	 * @return string
	 */
	public function createProblem($code, $name, $body="", $type="binary", $interactive=0, $masterjudgeId=1001)
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
	public function getProblem($code)
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
	public function updateProblem($code, $name=null, $body=null, $type=null, $interactive=null, $masterjudgeId=null, $activeTestcases=null)
	{
		if (empty($code)) 
			throw new SphereEngineResponseException("empty code", 400);
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
	public function updateProblemActiveTestcases($problemCode, $activeTestcases)
	{
		return $this->updateProblem($problemCode, null, null, null, null, null, $activeTestcases);
	}
	
	/**
	 * allTestcases
	 *
	 * Retrieve list of Problem testcases
	 *
	 * @param string $problemCode Problem code (required)
	 * @return string
	 */
	public function getProblemTestcases($problemCode)
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
	 * @param int $active if test should be active, default: true (optional)
	 * @return string
	 */
	public function createProblemTestcase($problemCode, $input="", $output="", $timelimit=1, $judgeId=1, $active=1)
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
	public function getProblemTestcase($problemCode, $number)
	{
		$urlParams = [
				'problemCode' => $problemCode,
				'number' => $number
		];
		return $this->apiClient->callApi('/problems/{problemCode}/testcases/{number}', 'GET', $urlParams, null, null, null);
	}
	
	/**
	 * updateProblemTestcase
	 *
	 * Update the problem testcase
	 *
	 * @param string $problemCode Problem code (required)
	 * @param int $number Testcase number (required)
	 * @param string $input input data (optional)
	 * @param string $output output data (optional)
	 * @param float $timelimit time limit in seconds (optional)
	 * @param int $judgeId Judge ID (optional)
	 * @param int $active if test should be active, default: true (optional)
	 * @return void
	 */
	public function updateProblemTestcase($problemCode, $number, $input=null, $output=null, $timelimit=null, $judgeId=null, $active=null)
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
		if (isset($active)) $postParams['active'] = $active;
		 
		return $this->apiClient->callApi('/problems/{problemCode}/testcases/{number}', 'PUT', $urlParams, null, $postParams, null);
	}
	
	/**
	 * Delete the problem testcase
	 *
	 * @param string $problemCode Problem code (required)
	 * @param int $number Testcase number (required)
	 * @return void
	 */
	public function deleteProblemTestcase($problemCode, $number)
	{
		$urlParams = [
				'problemCode' => $problemCode,
				'number' => $number
		];
		
		return $this->apiClient->callApi('/problems/{problemCode}/testcases/{number}', 'DELETE', $urlParams, null, null, null);
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
	public function getProblemTestcaseFile($problemCode, $number, $filename)
	{
		$urlParams = [
				'problemCode' => $problemCode,
				'number' => $number,
				'filename' => $filename
		];
		return $this->apiClient->callApi('/problems/{problemCode}/testcases/{number}/{filename}', 'GET', $urlParams, null, null, null, 'file');
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
	public function createSubmission($problemCode, $source, $compiler, $user=null)
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
	public function getSubmission($id)
	{
		$urlParams = [
				'id' => $id
		];
		return $this->apiClient->callApi('/submissions/{id}', 'GET', $urlParams, null, null, null);
	}
}
