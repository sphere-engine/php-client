<?php
/**
 * Example presents usage of the successful createSubmission() API method
*/

use SphereEngine\Api\CompilersClientV3;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = getenv("SE_ACCESS_TOKEN_COMPILERS");
$endpoint = getenv("SE_ENDPOINT_COMPILERS");

// initialization
$client = new CompilersClientV3($accessToken, $endpoint);

// API usage
$source = 'int main() { return 0; }';
$compiler = 11; // C language
$input = '2016';
$response = $client->createSubmission($source, $compiler, $input);
// response['id'] stores the ID of the created submission