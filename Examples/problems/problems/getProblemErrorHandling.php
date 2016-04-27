<?php
/**
 * Example presents error handling for getProblem() API method    
 */

use SphereEngine\Api\ProblemsClientV3;
use SphereEngine\Api\SphereEngineResponseException;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = 'your_access_token';
$endpoint = 'problems.sphere-engine.com';

// initialization
$client = new ProblemsClientV3($accessToken, $endpoint);

// API usage
$problemCode = 'NONEXISTING_CODE';
try {
	$response = $client->getProblem($problemCode);
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	} elseif ($e->getCode() == 404) {
		echo 'Problem does not exist';
	}
}
