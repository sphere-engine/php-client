<?php
/**
 * Example presents error handling for createProblemTestcase() API method  
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
$code = "EXAMPLE";
$input = "model input";
$output = "model output";
$timelimit = 5;
$judgeId = 1;
$active = true;

try {
    $response = $client->createProblemTestcase($code, $input, $output, $timelimit, $judgeId, $active);
	// response['number'] stores the number of created testcase
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	} elseif ($e->getCode() == 403) {
		echo 'Access to the problem is forbidden';
	} elseif ($e->getCode() == 404) {
		echo 'Problem does not exist';
	} elseif ($e->getCode() == 400) {
	    echo 'Error code: '.$e->getErrorCode().', details available in the message: ' . $e->getMessage();
	}
}
