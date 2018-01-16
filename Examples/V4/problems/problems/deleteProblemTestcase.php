<?php
/**
 * Example presents usage of the successful deleteProblemTestcase() API method  
 */

use SphereEngine\Api\ProblemsClientV4;

// require library
require_once('../../../../vendor/autoload.php');

// define access parameters
$accessToken = '<access_token>';
$endpoint = '<endpoint>';

// initialization
$client = new ProblemsClientV4($accessToken, $endpoint);

// API usage
$problemCode = 'EXAMPLE';
$testcaseNumber = 0;

$response = $client->deleteProblemTestcase($problemCode, $testcaseNumber);
