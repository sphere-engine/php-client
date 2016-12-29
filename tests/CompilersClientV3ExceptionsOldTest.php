<?php

use SphereEngine\Api\CompilersClientV3;
use SphereEngine\Api\SphereEngineResponseException;

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
    	$nonexistingSubmission = 3;
    	
    	try {
    		self::$client->getSubmission($nonexistingSubmission);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }

    public function testGetSubmissionMethodAccessDenied()
    {
        $foreignSubmission = 1;

        try {
            self::$client->getSubmission($foreignSubmission);
            $this->assertTrue(false);
        } catch (SphereEngineResponseException $e) {
            $this->assertTrue($e->getCode() == 403);
        }
    }

	public function testGetSubmissionStreamMethodNotExistingSubmission()
    {
    	$nonexistingSubmission = 3;
    	
    	try {
    		self::$client->getSubmissionStream($nonexistingSubmission, 'output');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }

	public function testGetSubmissionStreamMethodNotExistingStream()
    {	
    	try {
    		self::$client->getSubmissionStream(2, 'notexistingstream');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testCreateSubmissionMethodWrongCompiler()
    {
    	$wrongCompilerId = 9999;
    	
    	try {
    		self::$client->createSubmission("unit_test", $wrongCompilerId);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
}
