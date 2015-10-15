<?php

// require library
require_once('../autoload.php');

// initialization
$se = new SphereEngine\Api("access_token", "v3", "endpoint");
$client = $se->getProblemsClient();

// API usage
$problemCode = 'TEST';
$source = 'int main() { return 0; }';
$compiler = 11; // C language

$response = $client->submissions->create($problemCode, $source, $compiler);
// response['id'] stores the ID of the created submission
