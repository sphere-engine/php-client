<?php

// require library
require_once('../autoload.php');

// initialization
$se = new SphereEngine\Api("access_token", "v3", "endpoint");
$client = $se->getCompilersClient();

// API usage
$submissionId = 2015;
$client->submissions->get($submissionId);
