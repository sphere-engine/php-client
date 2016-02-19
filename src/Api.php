<?php
/**
 * Api
 * 
 * PHP version 5
 *
 * @category Class
 * @package  SphereEngine 
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

namespace SphereEngine;

class Api
{
    
    /**
     * Compilers module
     * @var SphereEngine\Api\Compilers instance of the Compilers
     */
	private $compilers = null;
	
	/**
	 * Problems module
	 * @var SphereEngine\Api\Problems instance of the Problems
	 */
	private $problems = null;
	
	private $accessToken = null;
	private $version = null;
	private $endpoint = null;

	/**
	 * 
	 * @param string $accessToken
	 * @param string $version 
	 * @param string $endpoint
	 */
	function __construct($accessToken, $version, $endpoint=null)
	{
		$this->accessToken = $accessToken;
		$this->version = $version;
		$this->endpoint = $endpoint;
	}

	/**
	 * @return \SphereEngine\Api\Compilers
	 */
	public function getCompilersClient($endpoint=null)
	{
		if ($this->compilers === null) {
			if($endpoint == null){
				$endpoint = $this->endpoint;
			}
			$this->compilers = new Api\Compilers($this->accessToken, $this->version, $endpoint);
		}
		return $this->compilers;
	}

	/**
	 * @return \SphereEngine\Api\Problems
	 */
	public function getProblemsClient($endpoint=null)
	{
		if ($this->problems === null) {
			if($endpoint == null){
				$endpoint = $this->endpoint;
			}
			$this->problems = new Api\Problems($this->accessToken, $this->version, $endpoint);
		}
		return $this->problems;
	}
}