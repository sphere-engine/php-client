<?php
/**
 * Example presents usage of the successful updateProblemTestcase() API method  
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
$problemCode = 'TEST';
$testcaseNumber = 0;
$newInput = 'New testcase input';

$response = $client->updateProblemTestcase($problemCode, $testcaseNumber, $newInput);