<?php
/**
 * Example presents error handling for getSubmission() API method
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
try {
	$response = $client->getSubmission(2016);
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	} elseif ($e->getCode() == 404) {
		echo 'Submission does not exist';
	}
}
