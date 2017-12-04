<?php
/**
 * Example presents usage of the successful createProblem() API method  
 */

use SphereEngine\Api\ProblemsClientV4;

// require library
require_once('../../../../autoload.php');

// define access parameters
$accessToken = '<access_token>';
$endpoint = '<endpoint>';

// initialization
$client = new ProblemsClientV4($accessToken, $endpoint);

// API usage
$code = "EXAMPLE";
$name = "Example problem";

$response = $client->createProblem($code, $name);
// response['code'] stores the code of the created problem
