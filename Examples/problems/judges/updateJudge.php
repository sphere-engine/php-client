<?php
/**
 * Example presents usage of the successful updateJudge() API method
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
$source = 'int main() { return 0; }';
$compiler = 11; // C language

$response = $client->updateJudge(1, $source, $compiler);
