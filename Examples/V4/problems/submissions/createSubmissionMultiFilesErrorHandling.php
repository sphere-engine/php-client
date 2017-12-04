<?php
/**
 * Example presents error handling for createSubmissionMultiFiles() API method
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
$files = array(
    'prog.c' => '<source_code>',
    'prog.h' => '<source_code>'
);
$compiler = 11; // C language

try {
    $response = $client->createSubmissionMultiFiles($problemCode, $files, $compiler);
    // response['id'] stores the ID of the created submission
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo 'Invalid access token';
	} elseif ($e->getCode() == 400) {
	    echo 'Error code: '.$e->getErrorCode().', details available in the message: ' . $e->getMessage();
	}
}
