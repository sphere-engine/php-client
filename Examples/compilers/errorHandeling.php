<?php
/**
 * Example presents complete error handeling schema for calling API methods of 
 * Sphere Engine Compilers API client
*/

use SphereEngine\Api\CompilersClientV3;
use SphereEngine\Api\SphereEngineResponseException;
use SphereEngine\Api\SphereEngineConnectionException;

// require library
require_once('../../autoload.php');

// define access parameters
$accessToken = getenv("SE_ACCESS_TOKEN_COMPILERS");
$endpoint = getenv("SE_ENDPOINT_COMPILERS");

// initialization
$client = new CompilersClientV3($accessToken, $endpoint);

// complete error handeling
try {
    // any API method usage
    // $client->methodName(parameters..)
} catch (SphereEngineResponseException $e) {
	if ($e->getCode() == 401) {
		echo "Invalid access token";
	} elseif ($e->getCode() == 402) {
		echo "Payment reqired";
	} elseif ($e->getCode() == 403) {
        echo "Access to the resource is forbideen";
    } elseif ($e->getCode() == 404) {
    	echo "Resource does not exist";
    	// more details about missing resource are provided in $e->getMessage() 
        // possible missing resources depend on called API method
    } elseif ($e->getCode() == 400) {
    	echo "Bad request";
    	// more details about missing resource are provided in $e->getMessage()
    	// possible reasons depend on called API method
    } else {
        // handle unexpected error code
    }
} catch (SphereEngineConnectionException $e) {
    if ($e->getCode() == 500) {
        echo "Error: API connection problem";
    } else {
        // handle unexpected API connection errors
    }
} finally { // or catch (Exception $e) { for older PHP versions
    // handle other exceptions (connection or network errors etc.)
}
