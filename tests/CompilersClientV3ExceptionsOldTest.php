<?php

use SphereEngine\Api\CompilersClientV3;

class CompilersClientV3ExceptionsOldTest extends PHPUnit_Framework_TestCase
{
	protected static $client;
	
	public static function setUpBeforeClass()
	{
		$access_token = getenv("SE_ACCESS_TOKEN_COMPILERS");
		$endpoint = getenv("SE_ENDPOINT_COMPILERS");
		self::$client = new CompilersClientV3(
				$access_token,
				$endpoint);
	}
	
    public function testAutorizationFail()
    {
        $access_token = "fake access token";
        $endpoint = getenv("SE_ENDPOINT_COMPILERS");
        $invalidClient = new CompilersClientV3(
        		$access_token, 
        		$endpoint);

        try {
        	$invalidClient->test();
        	$this->assertTrue(false);
        } catch (SphereEngineResponseException $e) {
        	$this->assertTrue($e->getCode() == 401);
        }
    }

    public function testGetSubmissionMethodNotExisting()
    {
    	$nonexistingSubmission = 9999999999;
    	
    	try {
    		self::$client->getSubmission($nonexistingSubmission);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testCreateSubmissionMethodWrongCompiler()
    {
    	$wrong_compiler_id = 9999;
    	
    	try {
    		self::$client->createSubmission("unit_test", $wrong_compiler_id);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
}
