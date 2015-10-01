<?php
/**
 * Api
 * 
 * PHP version 5
 *
 * @category Class
 * @package  SphereEngine\Client 
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
	private $compilers = null;
	private $problems = null;
	private $access_token = null;

	function __construct($access_token)
	{

	}

	public function getCompilersClient($version, $endpoint)
	{
		if ($this->compilers === null) {
			$this->compilers = new Api\CompilersClient($this->access_token, $version, $endpoint);
		}
		return $this->compilers;
	}

	public function getProblemsClient($version, $endpoint)
	{
		if ($this->problems === null) {
			$this->problems = new Api\ProblemsClient($this->access_token, $version, $endpoint);
		}
		return $this->problems;
	}
}