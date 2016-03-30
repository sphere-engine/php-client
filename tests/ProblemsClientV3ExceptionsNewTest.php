<?php

use SphereEngine\Api\ProblemsClientV3;
use SphereEngine\Api\SphereEngineResponseException;

class ProblemsClientV3ExceptionsNewTest extends PHPUnit_Framework_TestCase
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
	
	/**
	 * @requires PHPUnit 5
	 */
    public function testAutorizationFail()
    {
        $access_token = "fake access token";
        $endpoint = getenv("SE_ENDPOINT_PROBLEMS");
        $client = new ProblemsClientV3(
        		$access_token,
        		$endpoint);
        
        $this->expectException(SphereEngineResponseException::class);
        $this->expectExceptionCode(401);
        $client->test();
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemMethodWrongCode()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getProblem('NON_EXISTING_PROBLEM');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemMethodCodeTaken()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(400);
    	self::$client->createProblem('TEST', 'Taken problem code');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemMethodCodeEmpty()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(400);
    	self::$client->createProblem('', 'Empty problem code');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemMethodCodeInvalid()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(400);
    	self::$client->createProblem('!@#$%^', 'Invalid problem code');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemMethodEmptyName()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(400);
    	self::$client->createProblem('UNIQUE_CODE', '');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemMethodNonexistingMasterjudge()
    {
    	$nonexistingMasterjudgeId = 9999;
    	
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->createProblem(
    			'UNIQUE_CODE', 
    			'Nonempty name', 
    			'body', 
    			'binary',
    			0,
    			$nonexistingMasterjudgeId);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblem('NON_EXISTING_CODE', 'Nonexisting problem code');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemMethodNonexistingMasterjudge()
    {
    	$nonexistingMasterjudgeId = 9999;
    	
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblem(
    			'TEST', 
    			'Nonempty name',
    			'body',
    			'binary',
    			0,
    			$nonexistingMasterjudgeId
    			);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemMethodEmptyCode()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(400);
    	self::$client->updateProblem('', 'Nonempty name');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemMethodEmptyName()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(400);
    	self::$client->updateProblem('TEST', '');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemTestcasesMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcases('NON_EXISTING_CODE');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemTestcaseMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcase('NON_EXISTING_CODE', 0);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemTestcaseMethodNonexistingTestcase()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcase('TEST', 1);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemTestcaseMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->createProblemTestcase("NON_EXISTING_CODE", "in0", "out0", 10, 2, 1);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemTestcaseMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->createProblemTestcase("TEST", "in0", "out0", 10, $nonexistingJudge, 1);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemTestcaseMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblemTestcase("NON_EXISTING_CODE", 0, 'updated input');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemTestcaseMethodNonexistingTestcase()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblemTestcase("TEST", 1, 'updated input');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemTestcaseMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblemTestcase("TEST", 0, 'updated input', 'updated output', 1, $nonexistingJudge, 0);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testDeleteProblemTestcaseMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->deleteProblemTestcase("NON_EXISTING_CODE", 0);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testDeleteProblemTestcaseMethodNonexistingTestcase()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->deleteProblemTestcase("TEST", 1);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemTestcaseFileMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcaseFile("NON_EXISTING_CODE", 0, 'input');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemTestcaseFileMethodNonexistingTestcase()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcaseFile("TEST", 1, 'input');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemTestcaseFileMethodNonexistingFile()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcaseFile("TEST", 0, 'fakefile');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetJudgeMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getJudge($nonexistingJudge);
    }
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testCreateJudgeMethodEmptySource()
	{
		$this->expectException(SphereEngineResponseException::class);
		$this->expectExceptionCode(400);
		self::$client->createJudge('', 1, 'testcase', '');
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testCreateJudgeMethodNonexistingCompiler()
	{
		$nonexistingCompiler = 9999;
		
		$this->expectException(SphereEngineResponseException::class);
		$this->expectExceptionCode(404);
		self::$client->createJudge('nonempty source', $nonexistingCompiler, 'testcase', '');
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testUpdateJudgeMethodEmptySource()
	{
		$response = self::$client->createJudge('source', 1, 'testcase', 'UT judge');
		$judge_id = $response['id'];
		
		$this->expectException(SphereEngineResponseException::class);
		$this->expectExceptionCode(400);
		self::$client->updateJudge($judge_id, '', 1, '');
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testUpdateJudgeMethodNonexistingJudge()
	{
		$nonexistingJudge = 99999999;
		
		$this->expectException(SphereEngineResponseException::class);
		$this->expectExceptionCode(404);
		self::$client->updateJudge($nonexistingJudge, 'nonempty source', 1, '');
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testUpdateJudgeMethodNonexistingCompiler()
	{
		$response = self::$client->createJudge('source', 1, 'testcase', 'UT judge');
		$judge_id = $response['id'];
		$nonexistingCompiler = 9999;
	
		$this->expectException(SphereEngineResponseException::class);
		$this->expectExceptionCode(404);
		self::$client->updateJudge($judge_id, 'nonempty source', $nonexistingCompiler, '');
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testUpdateJudgeMethodForeignJudge()
	{
		$this->expectException(SphereEngineResponseException::class);
		$this->expectExceptionCode(403);
		self::$client->updateJudge(1, 'nonempty source', 1, '');
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testGetSubmissionMethodNonexistingSubmission()
	{
		$nonexistingSubmission = 9999999999;
		$this->expectException(SphereEngineResponseException::class);
		$this->expectExceptionCode(404);
		self::$client->getSubmission($nonexistingSubmission);
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testCreateSubmissionMethodEmptySource()
	{
		$this->expectException(SphereEngineResponseException::class);
		$this->expectExceptionCode(400);
		self::$client->createSubmission('TEST', '', 1);
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testCreateSubmissionMethodNonexistingProblem()
	{
		$this->expectException(SphereEngineResponseException::class);
		$this->expectExceptionCode(404);
		self::$client->createSubmission('NON_EXISTING_CODE', 'nonempty source', 1);
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testCreateSubmissionMethodNonexistingCompiler()
	{
		$nonexistingCompiler = 9999;
		
		$this->expectException(SphereEngineResponseException::class);
		$this->expectExceptionCode(404);
		self::$client->createSubmission('TEST', 'nonempty source', $nonexistingCompiler);
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testCreateSubmissionMethodNonexistingUser()
	{
		$nonexistingUser = 9999999999;
	
		$this->expectException(SphereEngineResponseException::class);
		$this->expectExceptionCode(404);
		self::$client->createSubmission('TEST', 'nonempty source', 1, $nonexistingUser);
	}
}
