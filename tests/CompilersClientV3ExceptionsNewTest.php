<?php

use SphereEngine\Api\CompilersClientV3;
use SphereEngine\Api\SphereEngineResponseException;

class CompilersClientV3ExceptionsNewTest extends PHPUnit_Framework_TestCase
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
	
	/**
	 * @requires PHPUnit 5
	 */
    public function testAutorizationFail()
    {
        $access_token = "fake access token";
        $endpoint = getenv("SE_ENDPOINT_COMPILERS");
        $client = new CompilersClientV3(
        		$access_token, 
        		$endpoint);

        $this->expectException(SphereEngineResponseException::class);
        $this->expectExceptionCode(401);
        $client->test();
    }

    /**
     * @requires PHPUnit 5
     */
    public function testGetSubmissionMethodNotExisting()
    {
    	$nonexistingSubmission = 3;
    	
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getSubmission($nonexistingSubmission);
        //$this->assertEquals('ACCESS_DENIED', self::$client->getSubmission(9999999999)['error']);
    }

    /**
     * @requires PHPUnit 5
     */
    public function testGetSubmissionMethodAccessDenied()
    {
        $foreignSubmission = 1;
        
        $this->expectException(SphereEngineResponseException::class);
        $this->expectExceptionCode(403);
        self::$client->getSubmission($foreignSubmission);
        //$this->assertEquals('ACCESS_DENIED', self::$client->getSubmission(9999999999)['error']);
    }
    

    /**
     * @requires PHPUnit 5
     */
    public function testGetSubmissionStreamMethodNotExistingSubmission()
    {
    	$nonexistingSubmission = 3;
    	
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getSubmissionStream($nonexistingSubmission, 'output');
    }

    /**
     * @requires PHPUnit 5
     */
    public function testGetSubmissionStreamMethodNotExistingStream()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getSubmissionStream(2, 'notexistingstream');
    }

    /**
     * @requires PHPUnit 5
     */
    public function testCreateSubmissionMethodWrongCompiler()
    {
    	$wrong_compiler_id = 9999;
    	
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->createSubmission("unit_test", $wrong_compiler_id);
    	//$this->assertEquals("WRONG_LANG_ID", self::$client->createSubmission("unit_test", $wrong_compiler_id)['error']);
    }
}
