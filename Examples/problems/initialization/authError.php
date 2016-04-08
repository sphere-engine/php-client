<?php
/**
 * Example presents authorization error handeling for 
 * Sphere Engine Problems API client
*/

use SphereEngine\Api\ProblemsClientV3;
use SphereEngine\Api\SphereEngineResponseException;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = "wrong access token";
$endpoint = getenv("SE_ENDPOINT_PROBLEMS");

// initialization
try {
	$client = new ProblemsClientV3($accessToken, $endpoint);
	$client->test();
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	}
}