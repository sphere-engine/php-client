<?php
/**
 * Example presents error handling for createSubmission() API method
*/

use SphereEngine\Api\CompilersClientV3;

// require library
require_once('../../../../vendor/autoload.php');

// define access parameters
$accessToken = '<access_token>';
$endpoint = '<endpoint>';

// initialization
$client = new CompilersClientV3($accessToken, $endpoint);

// API usage
$source = '<source code>';
$compiler = 11; // C language
$input = '2016';

try {
    $response = $client->createSubmission($source, $compiler, $input);
    // response['id'] stores the ID of the created submission
} catch (SphereEngineResponseException $e) {
    if ($e->getCode() == 401) {
        echo 'Invalid access token';
    }
}
