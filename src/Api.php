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

	function __construct($accessToken, $version, $endpoint)
	{
		$this->accessToken = $accessToken;
		$this->version = $version;
		$this->endpoint = $endpoint;
	}

	public function getCompilersClient()
	{
		if ($this->compilers === null) {
			$this->compilers = new Api\Compilers($this->accessToken, $this->version, $this->endpoint);
		}
		return $this->compilers;
	}

	public function getProblemsClient()
	{
		if ($this->problems === null) {
			$this->problems = new Api\Problems($this->accessToken, $this->version, $this->endpoint);
		}
		return $this->problems;
	}
}