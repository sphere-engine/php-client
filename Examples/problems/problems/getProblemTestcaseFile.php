<?php
/**
 * Example presents usage of the successful getProblemTestcaseFile() API method  
 */

use SphereEngine\Api\ProblemsClientV3;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = 'your_access_token';
$endpoint = 'problems.sphere-engine.com';

// initialization
$client = new ProblemsClientV3($accessToken, $endpoint);

// API usage
$problemCode = 'TEST';
$testcaseNumber = 0;
$file = 'input';

$response = $client->getProblemTestcaseFile($problemCode, $testcaseNumber, $file);
