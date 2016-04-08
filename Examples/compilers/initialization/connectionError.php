<?php
/**
 * Example presents connection error handeling for 
 * Sphere Engine Compilers API client
*/

use SphereEngine\Api\CompilersClientV3;
use SphereEngine\Api\SphereEngineConnectionException;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = getenv("SE_ACCESS_TOKEN_COMPILERS");
$endpoint = 'unavailable.endpoint.url';

// initialization
try {
	$client = new CompilersClientV3($accessToken, $endpoint);
	$client->test();
} catch (SphereEngineConnectionException $e) {
	echo "Error: API connection error " . $e->getCode() . ": " . $e->getMessage();
}