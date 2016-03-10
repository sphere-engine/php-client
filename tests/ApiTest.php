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

        $response = $compilersClient->test();
        $this->assertEquals(3.14, $response['pi']);
    }
}
