<?php

class ApiTest extends PHPUnit_Framework_TestCase
{
    public function testCompilersAutorizationFail()
    {
        $access_token = "fake access token";
        $se = new SphereEngine\Api($access_token, "v3", "endpoint");
        $compilersClient = $se->getCompilersClient();

        $this->assertEquals(false, array_key_exists('pi', $compilersClient->test()));
    }

    public function testCompilersAutorizationSuccess()
    {
        $access_token = getenv("SE_ACCESS_TOKEN_COMPILERS");
        $se = new SphereEngine\Api($access_token, "v3", "endpoint");
        $compilersClient = $se->getCompilersClient();

        $this->assertEquals(true, array_key_exists('pi', $compilersClient->test()));
    }

    public function testCompilersTestMethodSuccess()
    {
        $access_token = getenv("SE_ACCESS_TOKEN_COMPILERS");
        $se = new SphereEngine\Api($access_token, "v3", "endpoint");
        $compilersClient = $se->getCompilersClient();
        
        $this->assertEquals(3.14, $compilersClient->test()['pi']);
    }

    public function testCompilersCompilersMethodSuccess()
    {
        $access_token = getenv("SE_ACCESS_TOKEN_COMPILERS");
        $se = new SphereEngine\Api($access_token, "v3", "endpoint");
        $compilersClient = $se->getCompilersClient();
        
        $this->assertEquals('C', $compilersClient->getCompilers()[11][0]);
    }

    public function testCompilersGetSubmissionMethodSuccess()
    {
        $access_token = getenv("SE_ACCESS_TOKEN_COMPILERS");
        $se = new SphereEngine\Api($access_token, "v3", "endpoint");
        $compilersClient = $se->getCompilersClient();
        
        $this->assertEquals(15, $compilersClient->getSubmission(38436074)['result']);
    }

    public function testCompilersGetSubmissionMethodNotExisting()
    {
        $access_token = getenv("SE_ACCESS_TOKEN_COMPILERS");
        $se = new SphereEngine\Api($access_token, "v3", "endpoint");
        $compilersClient = $se->getCompilersClient();

        $this->assertEquals('ACCESS_DENIED', $compilersClient->getSubmission(9999999999)['error']);
    }

    public function testCompilersCreateSubmissionMethodSuccess()
    {
        $access_token = getenv("SE_ACCESS_TOKEN_COMPILERS");
        $se = new SphereEngine\Api($access_token, "v3", "endpoint");
        $compilersClient = $se->getCompilersClient();
        
        // TODO - lepiej na develu robić nowe zadania a jeszcze lepiej MOCK
    }


    public function testProblemsAutorizationFail()
    {
        $access_token = "fake access token";
        $se = new SphereEngine\Api($access_token, "v3", "endpoint");
        $problemsClient = $se->getProblemsClient();
        try {
            $problemsClient->test();
            $this->assertTrue(false);
        } catch (SphereEngine\SphereEngineResponseException $e) {
            $this->assertTrue(true);
        }
    }

    public function testProblemsAutorizationSuccess()
    {
        $access_token = getenv("SE_ACCESS_TOKEN_PROBLEMS");
        $se = new SphereEngine\Api($access_token, "v3", "endpoint");
        $problemsClient = $se->getProblemsClient();
        try {
            $problemsClient->test();
            $this->assertTrue(true);
        } catch (SphereEngine\SphereEngineResponseException $e) {
            $this->assertTrue(false);
        }
    }

    public function testProblemsTestMethodSuccess()
    {
        $access_token = getenv("SE_ACCESS_TOKEN_PROBLEMS");
        $se = new SphereEngine\Api($access_token, "v3", "endpoint");
        $problemsClient = $se->getProblemsClient();
        
        $this->assertEquals(true, count($problemsClient->test() > 0));
    }
}
