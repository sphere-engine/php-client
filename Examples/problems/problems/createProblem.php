<?php
/**
 * Example presents usage of the successful createProblem() API method  
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
$code = "EXAMPLE";
$name = "Example problem";

$response = $client->createProblem($code, $name);
// response['id'] stores the ID of the created problem
