<?php
/**
 * ProblemsClientV4
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
 *  Copyright 2017 Sphere Research Sp z o.o.
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

class ProblemsClientV4
{
	/**
	 * Common utilities for all Sphere Engine modules
	 */
	use ApiCommonsTrait;

    /**
     * API Client
     * @var ApiClient instance of the ApiClient
     */
	protected $apiClient;

	/**
	 * API module
	 * @var String module name of the API
	 */
	protected $module = 'problems';

	/**
	 * API version
	 * @var String version of the API
	 */
	protected $version = 'v4';

    /**
     * Constructor
     * @param string $accessToken Access token to Sphere Engine service
     * @param string $endpoint link to the endpoint
     * @param boolean $strictEndpoint strict endpoint (false if you need use another endpoint than sphere-engine.com)
     * @throws \RuntimeException
     */
	function __construct($accessToken, $endpoint, $strictEndpoint = true)
	{
	    $this->apiClient = new ApiClient($accessToken, $this->createEndpointLink('problems', $endpoint, $strictEndpoint));
	}

	/**
	 * Test method
	 *
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function test()
	{
	    $response = $this->apiClient->callApi('/test', 'GET', null, null, null, null, null);

		if ( ! in_array('message', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * List of all compilers
	 *
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function getCompilers()
	{
	    $response = $this->apiClient->callApi('/compilers', 'GET', null, null, null, null, null);

		if ( ! in_array('items', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * List of all judges
	 *
	 * @param int $limit limit of judges to get, default: 10, max: 100 (optional)
	 * @param int $offset offset, default: 0 (optional)
	 * @param string $type Judge type, enum: testcase|master, default: testcase (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function getJudges($limit=10, $offset=0, $type="testcase")
	{
		$queryParams = [
				'limit' => $limit,
				'offset' => $offset,
				'type' => $type
		];
		$response = $this->apiClient->callApi('/judges', 'GET', null, $queryParams, null, null, null);

		if ( ! in_array('paging', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * Create a new judge
	 *
	 * @param string $source source code (required)
	 * @param int $compiler Compiler ID, default: 1 (C++) (optional)
	 * @param string $type Judge type, testcase|master, default: testcase (optional)
	 * @param string $name Judge name, default: empty (optional)
	 * @param string $shared shared, default: false (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function createJudge($source, $compiler=1, $type="testcase", $name="", $shared = false)
	{
		if ($source == '') {
			throw new SphereEngineResponseException("empty source", 400);
		}

		$postParams = [
				'source' => $source,
				'compilerId' => $compiler,
				'type' => $type,
				'name' => $name,
		        'shared' => $shared ? true : false,
		];
		$response = $this->apiClient->callApi('/judges', 'POST', null, null, $postParams, null, null);

		if ( ! in_array('id', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * Get judge details
	 *
	 * @param int $id Judge ID (required)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function getJudge($id)
	{
		$urlParams = [
				'id' => $id
		];
		$response = $this->apiClient->callApi('/judges/{id}', 'GET', $urlParams, null, null, null, null);

		if ( ! in_array('id', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}
	
	/**
	 * Retrieve judge file
	 *
	 * @param int $id Judge ID (required)
	 * @param string $filename stream name (required)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return string file
	 */
	public function getJudgeFile($id, $filename)
	{
	    if ( ! in_array($filename, ['source'])) {
	        throw new SphereEngineResponseException("non existing stream", 404);
	    }
	    
	    $urlParams = [
	        'id' => $id,
	        'filename' => $filename
	    ];
	    $response = $this->apiClient->callApi('/judges/{id}/{filename}', 'GET', $urlParams, null, null, null, null, 'file');
	    
	    return $response;
	}

	/**
	 * Update judge
	 *
	 * @param int $id Judge ID (required)
	 * @param string $source source code (optional)
	 * @param int $compiler Compiler ID (optional)
	 * @param string $name Judge name (optional)
	 * @param string $shared shared (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function updateJudge($id, $source=null, $compiler=null, $name=null, $shared=null)
	{
		if (isset($source) && $source == '') {
			throw new SphereEngineResponseException("empty source", 400);
		}

		$urlParams = [
				'id' => $id
		];
		$postParams = [];
		if (isset($source)) $postParams['source'] = $source;
		if (isset($compiler)) $postParams['compilerId'] = $compiler;
		if (isset($name)) $postParams['name'] = $name;
		if (isset($shared)) $postParams['shared'] = $shared ? true : false;
		
		$response = $this->apiClient->callApi('/judges/{id}', 'PUT', $urlParams, null, $postParams, null, null);

		if ( ! is_array($response) || !empty($response)) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * List of all problems
	 *
	 * @param int $limit limit of problems to get, default: 10, max: 100 (optional)
	 * @param int $offset offset, default: 0 (optional)
	 * @param bool $shortBody determines whether shortened body should be returned, default: false (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function getProblems($limit=10, $offset=0, $shortBody = false)
	{
		$queryParams = [
				'limit' => $limit,
				'offset' => $offset,
				'shortBody' => $shortBody
		];
		$response = $this->apiClient->callApi('/problems', 'GET', null, $queryParams, null, null, null);

		if ( ! in_array('paging', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * Create a new problem
	 *
	 * @param string $code Problem code (required)
	 * @param string $name Problem name (required)
	 * @param string $body Problem body (optional)
	 * @param string $type Problem type, enum: binary|min|max, default: binary (optional)
	 * @param bool $interactive interactive problem flag, default: false (optional)
	 * @param int $masterjudgeId Masterjudge ID, default: 1001 (i.e. Score is % of correctly solved testcases) (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function createProblem($code, $name, $body="", $type="binary", $interactive=false, $masterjudgeId=1001)
	{
		if ($code == '') {
			throw new SphereEngineResponseException("empty code", 400);
		} elseif ($name == '') {
			throw new SphereEngineResponseException("empty name", 400);
		}

		$postParams = [
				'code' => $code,
				'name' => $name,
				'body' => $body,
				'type' => $type,
				'interactive' => intval($interactive),
				'masterjudgeId' => $masterjudgeId
		];
		$response = $this->apiClient->callApi('/problems', 'POST', null, null, $postParams, null, null);

		if ( ! in_array('code', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * Retrieve an existing problem
	 *
	 * @param string $code Problem code (required)
	 * @param bool $shortBody determines whether shortened body should be returned, default: false (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function getProblem($code, $shortBody = false)
	{
		$urlParams = [
				'code' => $code
		];
		$queryParams = [
				'shortBody' => $shortBody
		];
		$response = $this->apiClient->callApi('/problems/{code}', 'GET', $urlParams, $queryParams, null, null, null);

		if ( ! in_array('code', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * Update an existing problem
	 *
	 * @param string $code Problem code (required)
	 * @param string $name Problem name (optional)
	 * @param string $body Problem body (optional)
	 * @param string $type Problem type, enum: binary|min|max, default: binary (optional)
	 * @param bool $interactive interactive problem flag (optional)
	 * @param int $masterjudgeId Masterjudge ID (optional)
	 * @param int[] $activeTestcases list of active testcases IDs (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function updateProblem($code, $name=null, $body=null, $type=null, $interactive=null, $masterjudgeId=null, $activeTestcases=null)
	{
		if ($code == "") {
			throw new SphereEngineResponseException("empty code", 400);
		} elseif (isset($name) && $name == "") {
			throw new SphereEngineResponseException("empty name", 400);
		}

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

		$response = $this->apiClient->callApi('/problems/{code}', 'PUT', $urlParams, null, $postParams, null, null);

		if ( ! is_array($response) || !empty($response)) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * Update active testcases related to the problem
	 *
	 * @param string $problemCode Problem code (required)
	 * @param int[] $activeTestcases Active testcases (required)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function updateProblemActiveTestcases($problemCode, $activeTestcases)
	{
		return $this->updateProblem($problemCode, null, null, null, null, null, $activeTestcases);
	}

	/**
	 * Retrieve list of problem testcases
	 *
	 * @param string $problemCode Problem code (required)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function getProblemTestcases($problemCode)
	{
		$urlParams = [
				'problemCode' => $problemCode
		];
		$response = $this->apiClient->callApi('/problems/{problemCode}/testcases', 'GET', $urlParams, null, null, null, null);

		if ( ! in_array('testcases', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * Create a problem testcase
	 *
	 * @param string $problemCode Problem code (required)
	 * @param string $input input data, default: empty (optional)
	 * @param string $output output data, default: empty (optional)
	 * @param float $timelimit time limit in seconds, default: 1 (optional)
	 * @param int $judgeId Judge ID, default: 1 (Ignore extra whitespaces) (optional)
	 * @param int $active if test should be active, default: true (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function createProblemTestcase($problemCode, $input="", $output="", $timelimit=1, $judgeId=1, $active=true)
	{
		$urlParams = [
				'problemCode' => $problemCode
		];
		$postParams = [
				'input' => $input,
				'output' => $output,
				'timelimit' => $timelimit,
				'judgeId' => $judgeId,
				'active' => $active ? 1 : 0
		];
		$response = $this->apiClient->callApi('/problems/{problemCode}/testcases', 'POST', $urlParams, null, $postParams, null, null);

		if ( ! in_array('number', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * Retrieve problem testcase
	 *
	 * @param string $problemCode Problem code (required)
	 * @param int $number Testcase number (required)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function getProblemTestcase($problemCode, $number)
	{
		$urlParams = [
				'problemCode' => $problemCode,
				'number' => $number
		];
		$response = $this->apiClient->callApi('/problems/{problemCode}/testcases/{number}', 'GET', $urlParams, null, null, null, null);

		if ( ! in_array('number', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * Update the problem testcase
	 *
	 * @param string $problemCode Problem code (required)
	 * @param int $number Testcase number (required)
	 * @param string $input input data (optional)
	 * @param string $output output data (optional)
	 * @param float $timelimit time limit in seconds (optional)
	 * @param int $judgeId Judge ID (optional)
	 * @param int $active if test should be active, default: true (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
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
		if (isset($active)) $postParams['active'] = $active ? 1 : 0;

		$response = $this->apiClient->callApi('/problems/{problemCode}/testcases/{number}', 'PUT', $urlParams, null, $postParams, null, null);

		if ( ! is_array($response) || !empty($response)) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * Delete the problem testcase
	 *
	 * @param string $problemCode Problem code (required)
	 * @param int $number Testcase number (required)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function deleteProblemTestcase($problemCode, $number)
	{
		$urlParams = [
				'problemCode' => $problemCode,
				'number' => $number
		];

		$response = $this->apiClient->callApi('/problems/{problemCode}/testcases/{number}', 'DELETE', $urlParams, null, null, null, null);

		if ( ! is_array($response) || !empty($response)) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}

	/**
	 * Retrieve Problem testcase file
	 *
	 * @param string $problemCode Problem code (required)
	 * @param int $number Testcase number (required)
	 * @param string $filename stream name (required)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return string file
	 */
	public function getProblemTestcaseFile($problemCode, $number, $filename)
	{
		if ( ! in_array($filename, ['input', 'output'])) {
			throw new SphereEngineResponseException("non existing stream", 404);
		}

		$urlParams = [
				'problemCode' => $problemCode,
				'number' => $number,
				'filename' => $filename
		];
		$response = $this->apiClient->callApi('/problems/{problemCode}/testcases/{number}/{filename}', 'GET', $urlParams, null, null, null, null, 'file');

		return $response;
	}

	/**
	 * Create a new submission
	 *
	 * @param string $problemCode Problem code (required)
	 * @param string $source source code (required)
	 * @param int $compiler Compiler ID (required)
	 * @param bool $private private, default: false (optional)
	 * @param int $priority priority of the submission, default: normal priority (eg. 5 for range 1-9) (optional)
	 * @param int[] $tests tests to run, default: empty (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function createSubmission($problemCode, $source, $compiler, $private=false, $priority=null, $tests=[])
	{
	    if ($source == "") {
	        throw new SphereEngineResponseException("empty source", 400);
	    }
	    
		return $this->_createSubmission($problemCode, $source, $compiler, $private, $priority, [], $tests);
	}
	
	/**
	 * Create a new submission with multi files
	 *
	 * @param string $problemCode Problem code (required)
	 * @param string[] $files files [fileName=>fileContent] (required)
	 * @param int $compiler Compiler ID (required)
	 * @param bool $private private, default: false (optional)
	 * @param int $priority priority of the submission, default: normal priority (eg. 5 for range 1-9) (optional)
	 * @param int[] $tests tests to run, default: empty (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function createSubmissionMultiFiles($problemCode, $files, $compiler, $private=false, $priority=null, $tests=[])
	{
	    
	    if(is_array($files) === false || empty($files)) {
	        throw new SphereEngineResponseException("empty source", 400);
	    }
	    
	    return $this->_createSubmission($problemCode, '', $compiler, $private, $priority, $files, $tests);
	}
	
	/**
	 * Create a new submission from tar source
	 *
	 * @param string $problemCode Problem code (required)
	 * @param string $source tar(tar.gz) source (required)
	 * @param int $compiler Compiler ID (required)
	 * @param bool $private private, default: false (optional)
	 * @param int $priority priority of the submission, default: normal priority (eg. 5 for range 1-9) (optional)
	 * @param int[] $tests tests to run, default: empty (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function createSubmissionWithTarSource($problemCode, $tarSource, $compiler, $private=false, $priority=null, $tests=[])
	{
	    
	    if ($tarSource == "") {
	        throw new SphereEngineResponseException("empty source", 400);
	    }
	    
	    return $this->_createSubmission($problemCode, $tarSource, $compiler, $private, $priority, [], $tests);
	}
	
	/**
	 * Create a new submission
	 *
	 * @param string $problemCode Problem code (required)
	 * @param string $source source code (required)
	 * @param int $compiler Compiler ID (required)
	 * @param bool $private private, default: false (optional)
	 * @param int $priority priority of the submission, default: normal priority (eg. 5 for range 1-9) (optional)
	 * @param string[] $files files [fileName=>fileContent], default: empty (optional)
	 * @param int[] $tests tests to run, default: empty (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	private function _createSubmission($problemCode, $source, $compiler, $private=false, $priority=null, $files=[], $tests=[])
	{
	    $postParams = [
	        'problemCode' => $problemCode,
	        'compilerId' => $compiler,
	        'source' => $source,
	        'private' => $private ? 1 : 0
	    ];
	    $filesData = [];
	    
	    if (isset($priority)) {
	        $postParams['priority'] = intval($priority);
	    }
	    
	    if (!empty($files) && is_array($files)) {
	        $filesData['files'] = [];
	        foreach($files as $name => $content) {
	            if(is_string($content) === false) {
	                continue;
	            }
	            $filesData['files'][$name] = $content;
	        }
	        if(!empty($postParams['files'])) {
	            $postParams['source'] = '';
	        }
	    }
	    
	    if (!empty($tests) && is_array($tests)) {
	        $postParams['tests'] = implode(',', $tests);
	    }
	    
	    $response = $this->apiClient->callApi('/submissions', 'POST', null, null, $postParams, $filesData, null);
	    
	    if ( ! in_array('id', array_keys($response))) {
	        throw new SphereEngineResponseException("invalid or empty response", 422);
	    }
	    
	    return $response;
	}

	/**
	 * Update a submission
	 *
	 * @param int $id Submission ID (required)
	 * @param bool $private private (optional)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function updateSubmission($id, $private=null)
	{
	    $urlParams = [
	        'id' => $id
	    ];
	    
	    $postParams = [];
	    
	    if (isset($private)) $postParams['private'] = $private ? true : false;
	    
	    $response = $this->apiClient->callApi('/submissions/{id}', 'PUT', $urlParams, null, $postParams, null, null);
	    
	    if ( ! is_array($response) || !empty($response)) {
	        throw new SphereEngineResponseException("invalid or empty response", 422);
	    }
	    
	    return $response;
	}
	
	/**
	 * Fetch submission details
	 *
	 * @param string $id Submission ID (required)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return mixed API response
	 */
	public function getSubmission($id)
	{
		$urlParams = [
				'id' => $id
		];
		$response = $this->apiClient->callApi('/submissions/{id}', 'GET', $urlParams, null, null, null, null);

		if ( ! in_array('id', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}
	
	/**
	 * Retrieve submission file
	 *
	 * @param int $id Submission ID (required)
	 * @param string $filename stream name (required)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @return string file
	 */
	public function getSubmissionFile($id, $filename)
	{
	    if ( ! in_array($filename, ['source', 'stdout', 'stderr', 'cmperr', 'psinfo'])) {
	        throw new SphereEngineResponseException("non existing stream", 404);
	    }
	    
	    $urlParams = [
	        'id' => $id,
	        'filename' => $filename
	    ];
	    $response = $this->apiClient->callApi('/submissions/{id}/{filename}', 'GET', $urlParams, null, null, null, null, 'file');
	    
	    return $response;
	}
	
	/**
	 * Fetches status of multiple submissions (maximum 20 ids)
	 *
	 * @param array|int $ids Submission ids (required)
	 * @throws SphereEngineResponseException
	 * @throws SphereEngineConnectionException
	 * @throws \InvalidArgumentException for invalid $ids param
	 * @return mixed
	 */
	public function getSubmissions($ids)
	{
		if(is_array($ids) === false && is_int($ids) === false) {
			throw new \InvalidArgumentException('getSubmissions method accepts only array or integer.');
		}
	
		if(is_array($ids)) {
			$ids = array_map('intval', $ids);
			$ids = array_filter($ids, function ($id) { return $id > 0; });
			$ids = array_unique($ids);
			$ids = implode(',', $ids);
		}
	
		$queryParams = [
				'ids' => $ids
		];
	
		$response = $this->apiClient->callApi('/submissions', 'GET', null, $queryParams, null, null, null);

		if ( ! in_array('items', array_keys($response))) {
			throw new SphereEngineResponseException("invalid or empty response", 422);
		}

		return $response;
	}
}
