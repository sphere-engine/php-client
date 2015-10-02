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
	function __construct()
	{
	    
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
	public function create($code, $name, $body="", $type="binary", $interactive=0, $masterjudge_id=1001)
	{
	    
	}
	
	/**
	 * get
	 *
	 * Retrieve an existing problem
	 *
	 * @param string $code Problem code (required)
	 * @return string
	 */
	public function get($access_token, $code)
	{
	    
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
	 * @param int $masterjudge_id Masterjudge ID (optional)
	 * @param string $active_testcases list of active testcases IDs (optional)
	 * @return void
	 */
	public function update($code, $name=null, $body=null, $type=null, $interactive=null, $masterjudge_id=null)
	{
	    
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
	 * @param int $judge_id Judge ID, default: 1 (Ignore extra whitespaces) (optional)
	 * @param bool $active if test should be active, default: true (optional)
	 * @return string
	 */
	public function createTestcase($problemCode, $input="", $output="", $timelimit=1, $judge_id=1, $active=true)
	{
	    
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
	 * @param int $judge_id Judge ID (optional)
	 * @return void
	 */
	public function updateTestcase($problemCode, $number, $input=null, $output=null, $timelimit=null, $judge_id=null)
	{
	    
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
	    
	}
}
