<?php
/**
 * Example presents usage of the successful getCompilers() API method
*/

use SphereEngine\Api\CompilersClientV3;

// require library
require_once('../../autoload.php');

// define access parameters
$accessToken = getenv("SE_ACCESS_TOKEN_COMPILERS");
$endpoint = getenv("SE_ENDPOINT_COMPILERS");

// initialization
$client = new CompilersClientV3($accessToken, $endpoint);

// API usage
$response = $client->getCompilers();
