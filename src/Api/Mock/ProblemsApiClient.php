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

		if ($resourcePath == "/test") {
			return $this->mockTestMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == "/compilers") {
			return $this->mockCompilersMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == "/problems") {
			return $this->mockProblemsMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
		}

		if ($resourcePath == "/problems/{code}") {
			return $this->mockProblemMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType);
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
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}

	public function mockProblemMethod($method, $urlParams, $queryParams, $postData, $headerParams, $responseType)
	{
		if ($method == 'GET') {
			return [];
		} else {
			throw new \Exception("Method of this type is not supported by mock");
		}
	}
}
