<?php

use SphereEngine\Api\Mock\CompilersClientV3;
use SphereEngine\Api\SphereEngineResponseException;

class CompilersClientV3ExceptionsOldTest extends PHPUnit_Framework_TestCase
{
	protected static $client;
	
	public static function setUpBeforeClass()
	{
		$access_token = 'correctAccessToken';
		$endpoint = 'unittest';
		self::$client = new CompilersClientV3(
				$access_token,
				$endpoint);
	}
	
    public function testAutorizationFail()
    {
        $access_token = 'fakeAccessToken';
        $endpoint = 'unittest';
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

    public function testGetSubmissionMethodInvalidResponse()
    {
        $invalidSubmission = 4;
		try {
            self::$client->getSubmission($invalidSubmission);
            $this->assertTrue(false);
        } catch (SphereEngineResponseException $e) {
            $this->assertTrue($e->getCode() == 422);
        }
    }
    
    public function testGetSubmissionsMethodInvalidParams()
    {
    	try {
    		$response = self::$client->getSubmissions('1');
    		$this->assertTrue(false);
    	} catch (InvalidArgumentException $e) {
    		$this->assertTrue(true);
    	}
    }

    public function testGetSubmissionsMethodInvalidResponse()
    {
		try {
            self::$client->getSubmissions([911]);
            $this->assertTrue(false);
        } catch (SphereEngineResponseException $e) {
            $this->assertTrue($e->getCode() == 422);
        }
    }

    public function testGetSubmissionStreamMethodAccessDenied()
    {
    	$deniedSubmission = 1;
    	
		try {
    		self::$client->getSubmissionStream($deniedSubmission, 'output');
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
