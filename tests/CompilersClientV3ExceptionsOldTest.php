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
        $client = new CompilersClientV3(
        		$access_token, 
        		$endpoint);

        try {
        	$client->test();
        	$this->assertTrue(false);
        } catch (SphereEngineResponseException $e) {
        	$this->assertTrue(true);
        }
    }

    /**
     * @requires PHPUnit 5
     */
    public function testGetSubmissionMethodNotExisting()
    {
//     	$nonexistingSubmission = 9999999999;
    	
//     	$this->expectException(SphereEngineResponseException::class);
//     	$this->expectExceptionCode(404);
//     	self::$client->getSubmission($nonexistingSubmission);
//         //$this->assertEquals('ACCESS_DENIED', self::$client->getSubmission(9999999999)['error']);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateSubmissionMethodWrongCompiler()
    {
//     	$wrong_compiler_id = 9999;
    	
//     	$this->expectException(SphereEngineResponseException::class);
//     	$this->expectExceptionCode(404);
//     	self::$client->createSubmission("unit_test", $wrong_compiler_id);
//     	//$this->assertEquals("WRONG_LANG_ID", self::$client->createSubmission("unit_test", $wrong_compiler_id)['error']);
    }
}
