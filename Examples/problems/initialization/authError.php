<?php

// require library
require_once('../autoload.php');

// initialization
$se = new SphereEngine\Api("WRONG_access_token", "v3", "endpoint");
$client = $se->getProblemsClient();

// API usage
try {
    $client->test();
} catch (SphereEngine\SphereEngineResponseException $e) {
    if ($e.getCode() == 403) {
        echo "Error: authorization error, wrong access token";
    }
}
