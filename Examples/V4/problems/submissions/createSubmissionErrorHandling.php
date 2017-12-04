<?php
/**
 * Example presents error handling for createSubmission() API method
*/

use SphereEngine\Api\ProblemsClientV4;
use SphereEngine\Api\SphereEngineResponseException;

// require library
require_once('../../../../autoload.php');

// define access parameters
$accessToken = '<access_token>';
$endpoint = '<endpoint>';

// initialization
$client = new ProblemsClientV4($accessToken, $endpoint);

// API usage
$problemCode = 'TEST';
$source = '<source code>';
$compiler = 1;

try {
    $response = $client->createSubmission($problemCode, $source, $compiler);
	// response['id'] stores the ID of the created submission
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	} elseif ($e->getCode() == 400) {
	    echo 'Error code: '.$e->getErrorCode().', details available in the message: ' . $e->getMessage();
	}
}
