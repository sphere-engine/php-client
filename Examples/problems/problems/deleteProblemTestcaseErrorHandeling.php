<?php
/**
 * Example presents error handeling for deleteProblemTestcase() API method    
 */

use SphereEngine\Api\ProblemsClientV3;
use SphereEngine\Api\SphereEngineResponseException;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = getenv("SE_ACCESS_TOKEN_PROBLEMS");
$endpoint = getenv("SE_ENDPOINT_PROBLEMS");

// initialization
$client = new ProblemsClientV3($accessToken, $endpoint);

// API usage
$problemCode = 'EXAMPLE';
$nonexistingTestcaseNumber = 9999;

try {
	$response = $client->deleteProblemTestcase($problemCode, $nonexistingTestcaseNumber);
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	} elseif ($e->getCode() == 403) {
		echo 'Access to the problem is forbidden';
	} elseif ($e->getCode() == 404) {
		// agregates two possible reasons of 404 error
		// non existing problem or testcase
		echo 'Non existing resource (problem, testcase), details available in the message: ' . $e->getMessage();
	}
}
