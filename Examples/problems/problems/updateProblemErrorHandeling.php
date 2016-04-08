<?php
/**
 * Example presents error handeling for updateProblem() API method  
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
$problemCode = 'NONEXISTING_CODE';
$newProblemName = 'New example problem name';

try {
	$response = $client->updateProblem($problemCode, $newProblemName);
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	} elseif ($e->getCode() == 403) {
		echo 'Access to the problem is forbidden';
	} elseif ($e->getCode() == 400) {
		// agregates two possible reasons of 400 error
		// empty problem code, empty problem name
		echo 'Bad requiest (empty problem code, empty problem name), details available in the message: ' . $e->getMessage();
	} elseif ($e->getCode() == 404) {
		// agregates two possible reasons of 404 error
		// non existing problem or masterjudge
		echo 'Non existing resource (problem, masterjudge), details available in the message: ' . $e->getMessage();
	}
}
