<?php
/**
 * Example presents error handling for getProblem() API method    
 */

use SphereEngine\Api\ProblemsClientV4;
use SphereEngine\Api\SphereEngineResponseException;

// require library
require_once('../../../../autoload.php');

// define access parameters
$accessToken = '<access_token>';
$endpoint = '<endpoint>';

// initialization
$client = new ProblemsClientV4($accessToken, $endpoint);

// API usage
$problemCode = 'NONEXISTING_CODE';
try {
	$response = $client->getProblem($problemCode);
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	} elseif ($e->getCode() == 403) {
	    echo 'Access to the problem is forbidden';
	} elseif ($e->getCode() == 404) {
		echo 'Problem does not exist';
	}
}
