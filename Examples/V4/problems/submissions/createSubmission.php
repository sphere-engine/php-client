<?php
/**
 * Example presents usage of the successful createSubmission() API method
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
$problemCode = 'TEST';
$source = '<source code>';
$compiler = 11; // C language

$response = $client->createSubmission($problemCode, $source, $compiler);
// response['id'] stores the ID of the created submission
