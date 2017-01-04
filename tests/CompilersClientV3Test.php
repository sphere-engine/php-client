<?php

use SphereEngine\Api\Mock\CompilersClientV3;

class CompilersClientV3Test extends PHPUnit_Framework_TestCase
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
        $this->assertEquals('C++', self::$client->getCompilers()['items'][0]['name']);
    }

    public function testGetSubmissionMethodSuccess()
    {
        $s = self::$client->getSubmission(2, true);
        $this->assertEquals("abc", $s['source'], 'Submission source');
        $this->assertEquals(11, $s['compiler']['id'], 'Submission compiler');
    }

    public function testGetSubmissionStreamMethodSuccess()
    {
        $source = self::$client->getSubmissionStream(2, 'source');
        $this->assertEquals("abc", $source, 'Submission source');
    }

    public function testCreateSubmissionMethodSuccess()
    {
    	$submission_source = "unit test";
		$submission_compiler = 11;
		$submission_input = "unit test input";
		$response = self::$client->createSubmission($submission_source, $submission_compiler, $submission_input);
		$submission_id = $response['id']; 
        
		$this->assertTrue($submission_id > 0, 'New submission id should be greater than 0');
    }
}
