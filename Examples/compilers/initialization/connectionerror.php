<?php

// require library
require_once('../autoload.php');

// initialization
$se = new SphereEngine\Api("WRONG_access_token", "v3", "endpoint");
$client = $se->getCompilersClient();

// API usage
try {
    $client->submissions->test();
} catch (SphereEngine\SphereEngineConnectionException $e) {
    echo "Error: API connection error " . $e->getCode() . ": " . $e->getMessage();
}
