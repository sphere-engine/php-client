<?php
/**
 * Example presents error handeling for createJudge() API method  
*/

use SphereEngine\Api\ProblemsClientV3;
use SphereEngine\Api\SphereEngineResponseException;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = getenv("SE_ACCESS_TOKEN_PROBLEMS");
$endpoint = getenv("SE_ENDPOINT_PROBLEMS");

// initialization
$client = new ProblemsClientV3($accessToken, $endpoint);

// API usage
$source = 'int main() { return 0; }';
$nonexisting_compiler = 9999;

try {
	$response = $client->createJudge($source, $nonexisting_compiler);
	// response['id'] stores the ID of the created judge
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	} elseif ($e->getCode() == 400) {
		echo 'Empty source';
	} elseif ($e->getCode() == 404) {
		echo 'Compiler does not exist';
	}
}
