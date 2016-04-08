<?php
/**
 * Example presents authorization error handeling for 
 * Sphere Engine Compilers API client
*/

use SphereEngine\Api\CompilersClientV3;
use SphereEngine\Api\SphereEngineResponseException;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = "wrong access token";
$endpoint = getenv("SE_ENDPOINT_COMPILERS");

// initialization
try {
	$client = new CompilersClientV3($accessToken, $endpoint);
	$client->test();
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	}
}