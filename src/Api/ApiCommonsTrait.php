<?php

/**
 * ApiCommonsTrait
 * 
 * PHP version 5
 *
 * Common function for all Sphere Engine modules.
 *
 */

namespace SphereEngine\Api;

trait ApiCommonsTrait
{
    /**
	 * Create endpoint link
	 * 
	 * @param string $endpoint Sphere Engine Compilers endpoint
	 * @return string
	 */
	protected function createEndpointLink($endpoint)
	{
		if (strpos($endpoint, '.') === false) {
			return $endpoint . '.' . $this->module . '.sphere-engine.com/api/' . $this->version;
		} else {
			$endpoint = preg_replace("(^https?://)", "", $endpoint);
			return $endpoint . '/api/' . $this->version;
		}
	}
}
