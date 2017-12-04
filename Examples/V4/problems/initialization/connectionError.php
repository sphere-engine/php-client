<?php
/**
 * Example presents connection error handling for 
 * Sphere Engine Problems API client
*/

use SphereEngine\Api\ProblemsClientV4;
use SphereEngine\Api\SphereEngineConnectionException;

// require library
require_once('../../../../autoload.php');

// define access parameters
$accessToken = '<access_token>';
$endpoint = 'unavailable.endpoint.url';

// initialization
try {
    $client = new ProblemsClientV4($accessToken, $endpoint);
	$client->test();
} catch (SphereEngineConnectionException $e) {
	echo "Error: API connection error " . $e->getCode() . ": " . $e->getMessage();
}