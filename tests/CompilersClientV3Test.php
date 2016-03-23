<?php

use SphereEngine\Api\CompilersClientV3;

class CompilersClientV3Test extends PHPUnit_Framework_TestCase
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
        $client = new CompilersClientV3(
        		$access_token, 
        		$endpoint);

        $this->expectException(SphereEngineResponseException::class);
        $this->expectExceptionCode(401);
        $client->test();
    }

    public function testAutorizationSuccess()
    {
        $this->assertEquals(true, array_key_exists('pi', self::$client->test()));
    }

    public function testTestMethodSuccess()
    {        
        $this->assertEquals(3.14, self::$client->test()['pi']);
    }

    public function testCompilersMethodSuccess()
    {
        $this->assertEquals('C', self::$client->getCompilers()[11][0]);
    }

    public function testGetSubmissionMethodSuccess()
    {
        $this->assertEquals("//test", self::$client->getSubmission(25, true)['source']);
    }

    public function testGetSubmissionMethodNotExisting()
    {
    	$nonexistingSubmission = 9999999999;
    	
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getSubmission($nonexistingSubmission);
        //$this->assertEquals('ACCESS_DENIED', self::$client->getSubmission(9999999999)['error']);
    }

    public function testCreateSubmissionMethodSuccess()
    {
        $this->assertTrue(self::$client->createSubmission("unit_test")['id'] > 0);
    }
    
    public function testCreateSubmissionMethodWrongCompiler()
    {
    	$wrong_compiler_id = 9999;
    	
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->createSubmission("unit_test", $wrong_compiler_id);
    	//$this->assertEquals("WRONG_LANG_ID", self::$client->createSubmission("unit_test", $wrong_compiler_id)['error']);
    }
}