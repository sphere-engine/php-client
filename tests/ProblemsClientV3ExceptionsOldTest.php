<?php

use SphereEngine\Api\Mock\ProblemsClientV3;
use SphereEngine\Api\SphereEngineResponseException;

class ProblemsClientV3ExceptionsOldTest extends PHPUnit_Framework_TestCase
{
	protected static $client;
	
	public static function setUpBeforeClass()
	{
		$access_token = 'correctAccessToken';
		$endpoint = 'unittest';
		self::$client = new ProblemsClientV3(
				$access_token,
				$endpoint);
	}
	
    public function testAutorizationFail()
    {
        $access_token = 'fakeAccessToken';
        $endpoint = 'unittest';
        $invalidClient = new ProblemsClientV3(
        		$access_token,
        		$endpoint);
        
        try {
        	$invalidClient->test();
        	$this->assertTrue(false);
        } catch (SphereEngineResponseException $e) {
        	$this->assertTrue($e->getCode() == 401);
        }
    }
    
    public function testGetProblemMethodWrongCode()
    {	
    	try {
    		self::$client->getProblem('NON_EXISTING_PROBLEM');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testCreateProblemMethodCodeTaken()
    {
    	try {
    		self::$client->createProblem('TEST', 'taken_problem_code');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}
    }
    
    public function testCreateProblemMethodCodeEmpty()
    {
    	try {
    		self::$client->createProblem('', 'empty_problem_code');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}
    }
    
    public function testCreateProblemMethodCodeInvalid()
    {
    	try {
    		self::$client->createProblem('!@#$%^', 'invalid_problem_code');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}
    }
    
    public function testCreateProblemMethodEmptyName()
    {
    	try {
    		self::$client->createProblem('UNIQUE_CODE', '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}     	
    }
    
    public function testCreateProblemMethodNonexistingMasterjudge()
    {
    	$nonexistingMasterjudgeId = 9999;
    	try {
			self::$client->createProblem(
    			'UNIQUE_CODE',
				'nonempty_name',
				'body',
				'binary',
				0,
				$nonexistingMasterjudgeId);    	
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testUpdateProblemMethodNonexistingProblem()
    {
    	try {
    		self::$client->updateProblem('NON_EXISTING_CODE', 'nonexisting_problem_code');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testUpdateProblemMethodNonexistingMasterjudge()
    {
    	$nonexistingMasterjudgeId = 9999;
    	try {
    		self::$client->updateProblem(
    				'TEST',
    				'nonempty_name',
    				'body',
    				'binary',
    				0,
    				$nonexistingMasterjudgeId);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testUpdateProblemMethodEmptyCode()
    {
    	try {
    		self::$client->updateProblem('', 'nonempty_name');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}  	
    }
    
    public function testUpdateProblemMethodEmptyName()
    {
    	try {
    		self::$client->updateProblem('TEST', '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}	
    }
    
    public function testGetProblemTestcasesMethodNonexistingProblem()
    {
    	try {
    		self::$client->getProblemTestcases('NON_EXISTING_CODE');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testGetProblemTestcaseMethodNonexistingProblem()
    {
    	try {
    		self::$client->getProblemTestcase('NON_EXISTING_CODE', 0);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testGetProblemTestcaseMethodNonexistingTestcase()
    {
		$nonexistingTestcase = 9999;
    	try {
    		self::$client->getProblemTestcase('TEST', $nonexistingTestcase);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testCreateProblemTestcaseMethodNonexistingProblem()
    {
    	try {
    		self::$client->createProblemTestcase("NON_EXISTING_CODE", "in0", "out0", 10, 2, 1);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testCreateProblemTestcaseMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	try {
    		self::$client->createProblemTestcase("TEST", "in0", "out0", 10, $nonexistingJudge, 1);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testUpdateProblemTestcaseMethodNonexistingProblem()
    {
    	try {
    		self::$client->updateProblemTestcase("NON_EXISTING_CODE", 0, 'updated_input');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testUpdateProblemTestcaseMethodNonexistingTestcase()
    {
		$nonexistingTestcase = 9999;
    	try {
    		self::$client->updateProblemTestcase("TEST", $nonexistingTestcase, 'updated_input');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testUpdateProblemTestcaseMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	try {
    		self::$client->updateProblemTestcase("TEST", 0, 'updated_input', 'updated_output', 1, $nonexistingJudge, 0);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testDeleteProblemTestcaseMethodNonexistingProblem()
    {
		$nonexistingProblem = 'NON_EXISTING_CODE';
    	try {
    		self::$client->deleteProblemTestcase($nonexistingProblem, 0);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testDeleteProblemTestcaseMethodNonexistingTestcase()
    {
		$nonexistingTestcase = 9999;
    	try {
    		self::$client->deleteProblemTestcase('TEST', $nonexistingTestcase);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testGetProblemTestcaseFileMethodNonexistingProblem()
    {
		$nonexistingProblem = 'NON_EXISTING_CODE';
    	try {
    		self::$client->getProblemTestcaseFile($nonexistingProblem, 0, 'input');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testGetProblemTestcaseFileMethodNonexistingTestcase()
    {
		$nonexistingTestcase = 9999;
    	try {
    		self::$client->getProblemTestcaseFile("TEST", $nonexistingTestcase, 'input');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testGetProblemTestcaseFileMethodNonexistingFile()
    {
    	try {
    		self::$client->getProblemTestcaseFile("TEST", 0, 'fakefile');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
    
    public function testGetJudgeMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	try {
    		self::$client->getJudge($nonexistingJudge);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
    }
	
	public function testCreateJudgeMethodEmptySource()
	{
    	try {
    		self::$client->createJudge('', 1, 'testcase', '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}
	}
	
	public function testCreateJudgeMethodNonexistingCompiler()
	{
		$nonexistingCompiler = 9999;
    	try {
    		self::$client->createJudge('nonempty_source', $nonexistingCompiler, 'testcase', '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
	
	public function testUpdateJudgeMethodEmptySource()
	{
		$judge_id = 100;
    	try {
    		self::$client->updateJudge($judge_id, '', 1, '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}
	}
	
	public function testUpdateJudgeMethodNonexistingJudge()
	{
		$nonexistingJudge = 99999999;
    	try {
    		self::$client->updateJudge($nonexistingJudge, 'nonempty_source', 1, '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
	
	public function testUpdateJudgeMethodNonexistingCompiler()
	{
		$judge_id = 100;
		$nonexistingCompiler = 9999;
    	try {
    		self::$client->updateJudge($judge_id, 'nonempty_source', $nonexistingCompiler, '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
	
	public function testUpdateJudgeMethodForeignJudge()
	{
    	try {
    		self::$client->updateJudge(1, 'nonempty_source', 1, '');
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 403);
    	}
	}
	
	public function testGetSubmissionMethodNonexistingSubmission()
	{
		$nonexistingSubmission = 9999999999;
    	try {
    		self::$client->getSubmission($nonexistingSubmission);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
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
	
	public function testCreateSubmissionMethodEmptySource()
	{
    	try {
    		self::$client->createSubmission('TEST', '', 1);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 400);
    	}
	}
	
	public function testCreateSubmissionMethodNonexistingProblem()
	{
    	try {
    		self::$client->createSubmission('NON_EXISTING_CODE', 'nonempty_source', 1);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
	
	public function testCreateSubmissionMethodNonexistingCompiler()
	{
		$nonexistingCompiler = 9999;
    	try {
    		self::$client->createSubmission('TEST', 'nonempty_source', $nonexistingCompiler);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
	
	public function testCreateSubmissionMethodNonexistingUser()
	{
		$nonexistingUser = 999999;
    	try {
    		self::$client->createSubmission('TEST', 'nonempty_source', 1, $nonexistingUser);
    		$this->assertTrue(false);
    	} catch (SphereEngineResponseException $e) {
    		$this->assertTrue($e->getCode() == 404);
    	}
	}
}
