<?php

// require library
require_once('../autoload.php');

// initialization
$se = new SphereEngine\Api("access_token", "v3", "endpoint");
$client = $se->getProblemsClient();

// API usage
$submissionId = 9999999999; // nonexisting submission ID

try {
    $response = $client->submissions->get($submissionId);
} catch (SphereEngine\SphereEngineResponseException $e) {
    if ($e->getCode() == 404) {
        echo "Error: submission does not exists";
    }
}
