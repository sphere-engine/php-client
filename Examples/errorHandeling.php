<?php

// require library
require_once('../autoload.php');

// initialization
$se = new SphereEngine\Api("access_token", "v3", "endpoint");
$client = $se->getProblemsClient();

// API usage
$problemCode = 'PROBLEMNAME';
$source = '//source code';
$compiler = 11; // C language

// proper error handeling with accordance to the documentation
try {
    $response = $client->submissions->create($problemCode, $source, $compiler);
    $submissionId = $response['id'];
} catch (SphereEngine\SphereEngineResponseException $e) {
    if ($e->getCode() == 403) {
        echo "Error: unauthorized access";
    } elseif ($e->getCode() == 404) {
        if ($e->getMessage() == 'Problem not found') {
            // handle wrong problem code    
        } elseif ($e->getMessage() == 'Compiler not found') {
            // handle wrong compiler ID
        } elseif ($e->getMessage() == 'User not found') {
            // handle wrong user ID
        } else {
            // handle unexpected error
        }
    } elseif ($e->getCode() == 400) {
        if ($e->getMessage() == 'Empty source code') {
            // handle empty source code
        } else {
            // handle unexpected error
        }
    } else {
        // handle unexpected error code
    }
} catch (SphereEngine\SphereEngineConnectionException $e) {
    if ($e->getCode() == 500) {
        echo "Error: API connection problem";
    } else {
        // handle unexpected API connection errors
    }
} finally { // or catch (Exception $e) { in older PHP versions
    // handle other exceptions (connection or network errors etc.)
}
