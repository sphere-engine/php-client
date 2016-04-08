<?php
/**
 * Example presents connection error handling for 
 * Sphere Engine Problems API client
*/

use SphereEngine\Api\ProblemsClientV3;
use SphereEngine\Api\SphereEngineConnectionException;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = getenv("SE_ACCESS_TOKEN_PROBLEMS");
$endpoint = 'unavailable.endpoint.url';

// initialization
try {
	$client = new ProblemsClientV3($accessToken, $endpoint);
	$client->test();
} catch (SphereEngineConnectionException $e) {
	echo "Error: API connection error " . $e->getCode() . ": " . $e->getMessage();
}