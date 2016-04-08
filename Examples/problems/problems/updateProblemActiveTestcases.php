<?php
/**
 * Example presents usage of the successful updateProblem() API method
*/

use SphereEngine\Api\ProblemsClientV3;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = getenv("SE_ACCESS_TOKEN_PROBLEMS");
$endpoint = getenv("SE_ENDPOINT_PROBLEMS");

// initialization
$client = new ProblemsClientV3($accessToken, $endpoint);

// API usage
$activeTestcases = [1,2,3];

$response = $client->updateProblemActiveTestcases('EXAMPLE', $activeTestcases);
