<?php
/**
 * Compilers
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

namespace SphereEngine\Api;

use \SphereEngine\ApiClient;

class Compilers
{
	/**
	 * API Client
	 * @var \SphereEngine\ApiClient instance of the ApiClient
	 */
	private $apiClient;
	
	/**
	 * Submissions Submodule
	 * @var \SphereEngine\Api\Compilers\Submissions instance of the Submissions
	 */
	public $submissions;
	
	/**
	 * Constructor
	 * @param string $accessToken Access token to Sphere Engine service
	 * @param string $version version of the API
	 * @param string $endpoint link to the endpoint
	 */
	function __construct($accessToken, $version, $endpoint)
	{
		$this->apiClient = new ApiClient($accessToken, $this->createEndpointLink($version, $endpoint));
		$this->submissions = new Compilers\Submissions($this->apiClient);
	}
	
	private function createEndpointLink($version, $endpoint)
	{
	    return $endpoint . ".api.compilers.sphere-engine.com/api/" . $version;
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
	 * @return string
	 */
	public function compilers()
	{
	    return $this->apiClient->callApi('/languages', 'GET', null, null, null, null);
	}
}
