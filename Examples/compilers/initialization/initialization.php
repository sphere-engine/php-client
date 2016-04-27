<?php
/**
 * Example presents usage of the successful initialization of 
 * Sphere Engine Compilers API client
*/

use SphereEngine\Api\CompilersClientV3;

// require library
require_once('../../../autoload.php');

// define access parameters
$accessToken = 'your_access_token';
$endpoint = 'compilers.sphere-engine.com';

// initialization
$client = new CompilersClientV3($accessToken, $endpoint);
