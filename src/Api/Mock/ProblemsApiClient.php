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

namespace SphereEngine\Api\Mock;

use SphereEngine\Api\ApiClient;
use SphereEngine\Api\SphereEngineResponseException;
use SphereEngine\Api\SphereEngineConnectionException;

class ProblemsApiClient extends ApiClient
{	
	private function isAccessTokenCorrect()
	{
		return $this->accessToken == "correctAccessToken";
	}

	/**
	 * Mock HTTP call
	 *
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
		if ( ! $this->isAccessTokenCorrect() ) {
			throw new SphereEngineResponseException("Unauthorized", 401);
		}

		$queryParams['access_token'] = $this->accessToken;

		if ($resourcePath == '/test') {
			return $this->mockTestMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == '/compilers') {
			return $this->mockCompilersMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		//
		// Mocks for "problems"
		// 
		if ($resourcePath == '/problems') {
			return $this->mockProblemsMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == '/problems/{code}') {
			return $this->mockProblemMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == '/problems/{problemCode}/testcases') {
			return $this->mockProblemTestcasesMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == '/problems/{problemCode}/testcases/{number}') {
			return $this->mockProblemTestcaseMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == '/problems/{problemCode}/testcases/{number}/{filename}') {
			return $this->mockProblemTestcaseFileMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		//
		// Mocks for "judges"
		// 
		if ($resourcePath == '/judges') {
			return $this->mockJudgesMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == '/judges/{id}') {
			return $this->mockJudgeMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		//
		// Mocks for "submissions"
		//
		if ($resourcePath == '/submissions/{id}') {
			return $this->mockSubmissionMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == '/submissions') {
			return $this->mockSubmissionsMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

	    throw new \Exception("Resource url beyond mock functionality");
	}

	public function mockTestMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			$response = [
				'message' => 'You can use Sphere Engine Problems API.'
			];
			return $response;
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockCompilersMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			$response = [
				'items' => [
					['name' => 'C++', 'short' => 'cpp', 'geshi' => 'cpp', 'ace' => 'c_cpp', 'ver' => '5.1.1'],
					['name' => 'Python', 'short' => 'py', 'geshi' => 'py', 'ace' => 'py', 'ver' => '2.7'],
					['name' => 'Haskell', 'short' => 'hs', 'geshi' => 'hs', 'ace' => 'hs', 'ver' => '7.10.3'],
				]
			];
			return $response;
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockProblemsMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {

			$limit = (isset($queryParams['limit'])) ? $queryParams['limit'] : -1;
			$offset = (isset($queryParams['offset'])) ? $queryParams['offset'] : -1;
			$shortBody = (isset($queryParams['shortBody'])) ? $queryParams['shortBody'] : -1;
			
			$response = [
				'items' => [[
					'lastModifiedBody' => 123,
					'lastModifiedSettings' => 123,
				]], 
				'paging' => [
					'limit' => $limit,
					'offset' => $offset,
					'shortBody' => $shortBody
				]
			];

			if ($shortBody === true) {
				$response['items'][0]['shortBody'] = 'short body';
			}

			return $response;
		} elseif ($method == 'POST') {
			
			if (isset($postData['code'])) {
				$code = $postData['code'];
			} else {
				throw new \Exception('Lack of code parameter');
			}

			if (isset($postData['name'])) {
				$name = $postData['name'];
			} else {
				throw new \Exception('Lack of name parameter');
			}

			if (isset($postData['body'])) {
				$body = $postData['body'];
			} else {
				throw new \Exception('Lack of body parameter');
			}

			if (isset($postData['type'])) {
				$type = $postData['type'];
			} else {
				throw new \Exception('Lack of type parameter');
			}

			if (isset($postData['interactive'])) {
				$interactive = $postData['interactive'];
			} else {
				throw new \Exception('Lack of interactive parameter');
			}

			if (isset($postData['masterjudgeId'])) {
				$masterjudgeId = $postData['masterjudgeId'];
			} else {
				throw new \Exception('Lack of masterjudgeId parameter');
			}

			if ($code == 'TEST') {
				throw new SphereEngineResponseException("Problem code is not available", 400);
			}

			if ($code == '!@#$%^') {
				throw new SphereEngineResponseException("Problem code is invalid", 400);
			}

			// if ($code == '') {
			// 	throw new SphereEngineResponseException("Problem code is empty", 400);
			// }

			// if ($name == '') {
			// 	throw new SphereEngineResponseException("Problem name is empty", 400);
			// }

			if ($masterjudgeId < 1000 || $masterjudgeId > 2000) {
				throw new SphereEngineResponseException("Masterjudge doesn't exist", 404);
			}

			if ($name !== 'Name') {
				throw new \Exception('Wrong value of "name" parameter passed');
			}

			if ($body !== 'Body') {
				throw new \Exception('Wrong value of "body" parameter passed');
			}

			if ($type !== 'maximize') {
				throw new \Exception('Wrong value of "maximize" parameter passed');
			}

			if ($interactive !== 1) {
				throw new \Exception('Wrong value of "interactive" parameter passed');
			}

			if ($masterjudgeId !== 1002) {
				throw new \Exception('Wrong value of "masterjudgeId" parameter passed');
			}

			$response = [
				'code' => $code
			];

			return $response;
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockProblemMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			
			$code = (isset($urlParams['code'])) ? $urlParams['code'] : '';
			$shortBody = (isset($queryParams['shortBody'])) ? $queryParams['shortBody'] : -1;

			if ($code == 'NON_EXISTING_PROBLEM' || $code == '') {
				throw new SphereEngineResponseException("Problem doesn't exist", 404);
			}

			$response = [
				'code' => $code,
				'lastModifiedBody' => 123,
				'lastModifiedSettings' => 123
			];

			if ($shortBody === true) {
				$response['shortBody'] = 'short body';
			}

			return $response;
		} elseif ($method == 'PUT') {			
			if (isset($urlParams['code'])) {
				$code = $urlParams['code'];
			} else {
				throw new \Exception('Lack of code parameter');
			}

			$name = (isset($postData['name'])) ? $postData['name'] : null;
			$body = (isset($postData['body'])) ? $postData['body'] : null;
			$type = (isset($postData['type'])) ? $postData['type'] : null;
			$interactive = (isset($postData['interactive'])) ? $postData['interactive'] : null;
			$masterjudgeId = (isset($postData['masterjudgeId'])) ? $postData['masterjudgeId'] : null;
			$activeTestcases = (isset($postData['activeTestcases'])) ? $postData['activeTestcases'] : null;

			if ($code == 'NON_EXISTING_CODE') {
				throw new SphereEngineResponseException("Problem doesn't exist", 404);
			}

			if ($masterjudgeId !== null && $masterjudgeId < 1000 || $masterjudgeId > 2000) {
				throw new SphereEngineResponseException("Masterjudge doesn't exist", 404);
			}

			if ($name !== null && $name !== 'Updated name') {
				throw new \Exception('Wrong value of "name" parameter passed');
			}

			if ($body !== null && $body !== 'update') {
				throw new \Exception('Wrong value of "body" parameter passed');
			}

			if ($type !== null && $type !== 'maximize') {
				throw new \Exception('Wrong value of "maximize" parameter passed');
			}

			if ($interactive !== null && $interactive !== 1) {
				throw new \Exception('Wrong value of "interactive" parameter passed');
			}

			if ($masterjudgeId !== null && $masterjudgeId !== 1002) {
				throw new \Exception('Wrong value of "masterjudgeId" parameter passed');
			}

			if ($activeTestcases !== null && $activeTestcases !== '0,1,2') {
				throw new \Exception('Wrong value of "activeTestcases" parameter passed');
			}

			return [];

		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockProblemTestcasesMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			if (isset($urlParams['problemCode'])) {
				$problemCode = $urlParams['problemCode'];
			} else {
				throw new \Exception('Lack of code parameter');
			}

			if ($problemCode == 'NON_EXISTING_CODE') {
				throw new SphereEngineResponseException("Problem doesn't exist", 404);
			}

			return [
				'testcases' => [
					['number' => 0],
					['number' => 1],
					['number' => 2],
				]
			];
		} elseif ($method == 'POST') {
			if (isset($urlParams['problemCode'])) {
				$problemCode = $urlParams['problemCode'];
			} else {
				throw new \Exception('Lack of code parameter');
			}

			if (isset($postData['input'])) {
				$input = $postData['input'];
			} else {
				throw new \Exception('Lack of input parameter');
			}

			if (isset($postData['output'])) {
				$output = $postData['output'];
			} else {
				throw new \Exception('Lack of output parameter');
			}

			if (isset($postData['timelimit'])) {
				$timelimit = $postData['timelimit'];
			} else {
				throw new \Exception('Lack of timelimit parameter');
			}

			if (isset($postData['judgeId'])) {
				$judgeId = $postData['judgeId'];
			} else {
				throw new \Exception('Lack of judgeId parameter');
			}

			if (isset($postData['active'])) {
				$active = $postData['active'];
			} else {
				throw new \Exception('Lack of active parameter');
			}

			if ($problemCode == 'NON_EXISTING_CODE') {
				throw new SphereEngineResponseException("Problem doesn't exist", 404);
			}

			if ($judgeId > 9000) {
				throw new SphereEngineResponseException("Judge doesn't exist", 404);
			}

			if ($problemCode !== 'TEST') {
				throw new \Exception('Wrong value of "problemCode" parameter passed');
			}

			if ($input !== 'in0') {
				throw new \Exception('Wrong value of "input" parameter passed');
			}

			if ($output !== 'out0') {
				throw new \Exception('Wrong value of "output" parameter passed');
			}

			if ($timelimit !== 10) {
				throw new \Exception('Wrong value of "timelimit" parameter passed');
			}

			if ($judgeId !== 2) {
				throw new \Exception('Wrong value of "judgeId" parameter passed');
			}

			if ($active !== 0) {
				throw new \Exception('Wrong value of "active" parameter passed');
			}

			return [
				'number' => 0
			];

		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockProblemTestcaseMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			if (isset($urlParams['problemCode'])) {
				$problemCode = $urlParams['problemCode'];
			} else {
				throw new \Exception('Lack of code parameter');
			}

			if (isset($urlParams['number'])) {
				$number = $urlParams['number'];
			} else {
				throw new \Exception('Lack of code parameter');
			}

			if ($problemCode == 'NON_EXISTING_CODE') {
				throw new SphereEngineResponseException("Problem doesn't exist", 404);
			}

			if ($number > 100) {
				throw new SphereEngineResponseException("Testcase doesn't exist", 404);
			}

			return [
				'number' => $number
			];
		} elseif ($method == 'PUT') {
			if (isset($urlParams['problemCode'])) {
				$problemCode = $urlParams['problemCode'];
			} else {
				throw new \Exception('Lack of code parameter');
			}

			if (isset($urlParams['number'])) {
				$number = $urlParams['number'];
			} else {
				throw new \Exception('Lack of code parameter');
			}

			$input = (isset($postData['input'])) ? $postData['input'] : null;
			$output = (isset($postData['output'])) ? $postData['output'] : null;
			$timelimit = (isset($postData['timelimit'])) ? $postData['timelimit'] : null;
			$judgeId = (isset($postData['judgeId'])) ? $postData['judgeId'] : null;
			$active = (isset($postData['active'])) ? $postData['active'] : null;

			if ($problemCode == 'NON_EXISTING_CODE') {
				throw new SphereEngineResponseException("Problem doesn't exist", 404);
			}

			if ($number > 100) {
				throw new SphereEngineResponseException("Testcase doesn't exist", 404);
			}

			if ($judgeId > 9000) {
				throw new SphereEngineResponseException("Judge doesn't exist", 404);
			}

			if ($problemCode !== 'TEST') {
				throw new \Exception('Wrong value of "problemCode" parameter passed');
			}

			if ($number !== 0) {
				throw new \Exception('Wrong value of "number" parameter passed');
			}

			if ($input !== null && $input !== 'in0updated') {
				throw new \Exception('Wrong value of "input" parameter passed');
			}

			if ($output !== null && $output !== 'out0updated') {
				throw new \Exception('Wrong value of "output" parameter passed');
			}

			if ($timelimit !== null && $timelimit !== 10) {
				throw new \Exception('Wrong value of "timelimit" parameter passed');
			}

			if ($judgeId !== null && $judgeId !== 2) {
				throw new \Exception('Wrong value of "judgeId" parameter passed');
			}

			if ($active !== null && $active !== 0) {
				throw new \Exception('Wrong value of "active" parameter passed');
			}

			return [];
		} elseif ($method == 'DELETE') {
			if (isset($urlParams['problemCode'])) {
				$problemCode = $urlParams['problemCode'];
			} else {
				throw new \Exception('Lack of code parameter');
			}

			if (isset($urlParams['number'])) {
				$number = $urlParams['number'];
			} else {
				throw new \Exception('Lack of code parameter');
			}

			if ($problemCode == 'NON_EXISTING_CODE') {
				throw new SphereEngineResponseException("Problem doesn't exist", 404);
			}

			if ($number > 100) {
				throw new SphereEngineResponseException("Testcase doesn't exist", 404);
			}

		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockProblemTestcaseFileMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			if (isset($urlParams['problemCode'])) {
				$problemCode = $urlParams['problemCode'];
			} else {
				throw new \Exception('Lack of code parameter');
			}

			if (isset($urlParams['number'])) {
				$number = $urlParams['number'];
			} else {
				throw new \Exception('Lack of code parameter');
			}

			if (isset($urlParams['filename'])) {
				$filename = $urlParams['filename'];
			} else {
				throw new \Exception('Lack of filename parameter');
			}

			if ($problemCode == 'NON_EXISTING_CODE') {
				throw new SphereEngineResponseException("Problem doesn't exist", 404);
			}

			if ($number > 100) {
				throw new SphereEngineResponseException("Testcase doesn't exist", 404);
			}

			if ($filename == 'input' || $filename == 'stdin') {
				return 'in' . $number;
			}

			if ($filename == 'output' || $filename == 'stdout') {
				return 'out' . $number;
			}

			if ($filename == 'source') {
				return 'source' . $number;
			}

			if ($filename == 'error' || $filename == 'stderr') {
				return 'error' . $number;
			}

			if ($filename == 'cmpinfo') {
				return 'cmpinfo' . $number;
			}

		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockJudgesMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {

			$limit = (isset($queryParams['limit'])) ? $queryParams['limit'] : -1;
			$offset = (isset($queryParams['offset'])) ? $queryParams['offset'] : -1;
			$shortBody = (isset($queryParams['shortBody'])) ? $queryParams['shortBody'] : -1;
			
			$response = [
				'items' => [[], []], 
				'paging' => [
					'limit' => $limit,
					'offset' => $offset
				]
			];

			if ($shortBody === true) {
				$response['items'][0]['shortBody'] = 'short body';
			}

			return $response;
		} elseif ($method == 'POST') {
			if (isset($postData['source'])) {
				$source = $postData['source'];
			} else {
				throw new \Exception('Lack of source parameter');
			}

			if (isset($postData['compilerId'])) {
				$compilerId = $postData['compilerId'];
			} else {
				throw new \Exception('Lack of compilerId parameter');
			}

			if (isset($postData['type'])) {
				$type = $postData['type'];
			} else {
				throw new \Exception('Lack of type parameter');
			}

			if (isset($postData['name'])) {
				$name = $postData['name'];
			} else {
				throw new \Exception('Lack of name parameter');
			}

			if ($compilerId < 1 || $compilerId > 128) {
				throw new SphereEngineResponseException("Compiler doesn't exist", 404);
			}

			if ($source !== 'source') {
				throw new \Exception('Wrong value of "source" parameter passed');
			}

			if ($compilerId !== 2) {
				throw new \Exception('Wrong value of "compilerId" parameter passed');
			}

			if ($type !== 'testcase') {
				throw new \Exception('Wrong value of "type" parameter passed');
			}

			if ($name !== 'UT judge') {
				throw new \Exception('Wrong value of "name" parameter passed');
			}

			return [
				'id' => 1
			];
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockJudgeMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			if (isset($urlParams['id'])) {
				$id = $urlParams['id'];
			} else {
				throw new \Exception('Lack of id parameter');
			}

			if ($id > 9000) {
				throw new SphereEngineResponseException("Judge doesn't exist", 404);
			}

			$response = [
				'id' => $id,
			];

			return $response;
		} elseif($method == 'PUT') {
			if (isset($urlParams['id'])) {
				$id = $urlParams['id'];
			} else {
				throw new \Exception('Lack of id parameter');
			}

			$source = (isset($postData['source'])) ? $postData['source'] : null;
			$compilerId = (isset($postData['compilerId'])) ? $postData['compilerId'] : null;
			$name = (isset($postData['name'])) ? $postData['name'] : null;

			if ($id == 1) {
				throw new SphereEngineResponseException("Access denied", 403);
			}

			if ($id > 9000) {
				throw new SphereEngineResponseException("Judge doesn't exist", 404);
			}

			if ($compilerId < 1 || $compilerId > 128) {
				throw new SphereEngineResponseException("Compiler doesn't exist", 404);
			}

			if ($source !== null && $source !== 'updated source') {
				throw new \Exception('Wrong value of "source" parameter passed');
			}

			if ($compilerId !== null && $compilerId !== 11) {
				throw new \Exception('Wrong value of "compilerId" parameter passed');
			}

			if ($name !== null && $name !== 'UT judge updated') {
				throw new \Exception('Wrong value of "name" parameter passed');
			}

			return [];
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockSubmissionMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			if (isset($urlParams['id'])) {
				$id = $urlParams['id'];
			} else {
				throw new \Exception('Lack of id parameter');
			}

			if ($id > 9000) {
				throw new SphereEngineResponseException("Submission doesn't exist", 404);
			}

			return [
				'id' => $id
			];

		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockSubmissionsMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			if (isset($urlParams['id'])) {
				$id = $urlParams['id'];
			} else {
				throw new \Exception('Lack of id parameter');
			}

			if ($id > 9000) {
				throw new SphereEngineResponseException("Submission doesn't exist", 404);
			}

			return [
				'id' => $id
			];
		} elseif ($method == 'POST') {

			if (isset($postData['problemCode'])) {
				$problemCode = $postData['problemCode'];
			} else {
				throw new \Exception('Lack of problemCode parameter');
			}

			if (isset($postData['compilerId'])) {
				$compilerId = $postData['compilerId'];
			} else {
				throw new \Exception('Lack of compilerId parameter');
			}

			if (isset($postData['source'])) {
				$source = $postData['source'];
			} else {
				throw new \Exception('Lack of source parameter');
			}

			if (isset($postData['userId'])) {
				$userId = $postData['userId'];
			} else {
				$userId = null;
			}

			if ($problemCode == 'NON_EXISTING_CODE') {
				throw new SphereEngineResponseException("Problem doesn't exist", 404);
			}

			if ($userId !== null && $userId > 9000) {
				throw new SphereEngineResponseException("User doesn't exist", 404);
			}

			if ($compilerId < 1 || $compilerId > 128) {
				throw new SphereEngineResponseException("Compiler doesn't exist", 404);
			}

			if ($problemCode !== 'TEST') {
				throw new \Exception('Wrong value of "problemCode" parameter passed');
			}
			
			if ($compilerId !== 2) {
				throw new \Exception('Wrong value of "compilerId" parameter passed');
			}

			if ($source !== 'source') {
				throw new \Exception('Wrong value of "source" parameter passed');
			}

			return [
				'id' => 1
			];
			
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

}
