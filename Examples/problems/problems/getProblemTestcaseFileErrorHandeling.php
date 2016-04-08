<?php
/**
 * Example presents error handeling for getProblemTestcaseFile() API method    
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
$problemCode = 'TEST';
$testcaseNumber = 0;
$nonexistingFile = 'nonexistingFile';

try {
	$response = $client->getProblemTestcaseFile($problemCode, $testcaseNumber, $nonexistingFile);
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	} elseif ($e->getCode() == 403) {
		echo 'Access to the problem is forbidden';
	} elseif ($e->getCode() == 404) {
		// agregates three possible reasons of 404 error
		// non existing problem, testcase or file
		echo 'Non existing resource (problem, testcase or file), details available in the message: ' . $e->getMessage();
	}
}