<?php
/**
 * Example presents error handling for getJudge() API method    
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
$nonexisting_judge_id = 999999;
try {
	$response = $client->getJudge($nonexisting_judge_id);
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	} elseif ($e->getCode() == 404) {
		echo 'Judge does not exist';
	} elseif ($e->getCode() == 403) {
		echo 'Access to the judge is forbidden';
	}
}
