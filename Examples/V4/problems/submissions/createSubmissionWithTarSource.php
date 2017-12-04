<?php
/**
 * Example presents usage of the successful createSubmissionWithTarSource() API method
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
$tarSource = '<tar_source>';
$compiler = 11; // C language

$response = $client->createSubmissionWithTarSource($problemCode, $tarSource, $compiler);
// response['id'] stores the ID of the created submission
