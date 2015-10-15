<?php

// require library
require_once('../autoload.php');

// initialization
$se = new SphereEngine\Api("access_token", "v3", "endpoint");
$client = $se->getProblemsClient();

// API usage
try {
    $client->test();
} catch (SphereEngine\SphereEngineConnectionException $e) {
    echo "Error: API connection error " . $e->getCode() . ": " . $e->getMessage();
}
