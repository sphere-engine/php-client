<?php
/**
 * Example presents usage of the successful test() API method  
 */

use SphereEngine\Api\CompilersClientV3;

// require library
require_once('../../autoload.php');

// define access parameters
$accessToken = 'your_access_token';
$endpoint = 'compilers.sphere-engine.com';

// initialization
$client = new CompilersClientV3($accessToken, $endpoint);

// API usage
$response = $client->test();
