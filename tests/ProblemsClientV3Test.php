<?php

use SphereEngine\Api\ProblemsClientV3;
use SphereEngine\Api\SphereEngineResponseException;

class ProblemsClientV3Test extends PHPUnit_Framework_TestCase
{
	protected static $client;
	
	public static function setUpBeforeClass()
	{
		$access_token = getenv("SE_ACCESS_TOKEN_PROBLEMS");
		$endpoint = getenv("SE_ENDPOINT_PROBLEMS");
		self::$client = new ProblemsClientV3(
				$access_token,
				$endpoint);
	}
	
    public function testAutorizationFail()
    {
        $access_token = "fake access token";
        $endpoint = getenv("SE_ENDPOINT_PROBLEMS");
        $client = new ProblemsClientV3(
        		$access_token,
        		$endpoint);
        
        try {
            $client->test();
            $this->assertTrue(false);
        } catch (SphereEngineResponseException $e) {
            $this->assertTrue(true);
        }
    }

    public function testAutorizationSuccess()
    {
        try {
            self::$client->test();
            $this->assertTrue(true);
        } catch (SphereEngineResponseException $e) {
            $this->assertTrue(false);
        }
    }

    public function testProblemsTestMethodSuccess()
    {
        $this->assertEquals(true, count(self::$client->test() > 0));
    }
}
