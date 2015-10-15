<?php

// require library
require_once('../autoload.php');

// initialization
$se = new SphereEngine\Api("access_token", "v3", "endpoint");
$client = $se->getCompilersClient();

// API usage
$source = 'int main() { return 0; }';
$compiler = 11; // C language
$input = "2015";

$response = $client->submissions->create($source, $compiler, $input);
// response['id'] stores the ID of the created submission
