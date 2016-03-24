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
        
        $this->expectException(SphereEngineResponseException::classname);
        $this->expectExceptionCode(401);
        $client->test();
    }

    public function testAutorizationSuccess()
    {
    	self::$client->test();
    	$this->assertTrue(true);
    }

    public function testTestMethodSuccess()
    {
        $this->assertTrue(count(self::$client->test()) > 0);
    }
    
    public function testCompilersMethodSuccess()
    {
    	$this->assertEquals("C++", self::$client->getCompilers()['items'][0]['name']);
    }
    
    public function testGetProblemsMethodSuccess()
    {
    	$this->assertEquals(10, self::$client->getProblems()['paging']['limit']);
    	$this->assertEquals(11, self::$client->getProblems(11)['paging']['limit']);
    }
    
    public function testGetProblemMethodSuccess()
    {
    	$this->assertEquals('TEST', self::$client->getProblem('TEST')['code']);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemMethodWrongCode()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->getProblem('NON_EXISTING_PROBLEM');
    }
    
    public function testCreateProblemMethodSuccess()
    {
    	$r = rand(1000000,9999999) . rand(1000000,9999999); // 14-digits random string
    	$problem_code = 'UT' . $r;
    	$problem_name = 'UT' . $r;
    	$problem_body = 'UT' . $r . ' body';
    	$problem_type = 'maximize';
    	$problem_interactive = 1;
    	$problem_masterjudgeId = 1000;
    	$this->assertEquals(
    			$problem_code, 
    			self::$client->createProblem(
    					$problem_code, 
    					$problem_name,
    					$problem_body,
    					$problem_type,
    					$problem_interactive,
    					$problem_masterjudgeId
    				)['code'],
    			'Creation method should return new problem code');
    	$p = self::$client->getProblem($problem_code);
    	$this->assertEquals($problem_code, $p['code'], 'Problem code');
    	$this->assertEquals($problem_name, $p['name'], 'Problem name');
    	$this->assertEquals($problem_body, $p['body'], 'Problem body');
    	$this->assertEquals($problem_type, $p['type'], 'Problem type');
    	$this->assertEquals($problem_interactive, $p['interactive'], 'Problem interactive');
    	$this->assertEquals($problem_masterjudgeId, $p['masterjudge']['id'], 'Problem masterjudgeId');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemMethodCodeTaken()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(400);
    	self::$client->createProblem('TEST', 'Taken problem code');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemMethodCodeEmpty()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(400);
    	self::$client->createProblem('', 'Empty problem code');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemMethodCodeInvalid()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(400);
    	self::$client->createProblem('!@#$%^', 'Invalid problem code');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemMethodEmptyName()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(400);
    	self::$client->createProblem('UNIQUE_CODE', '');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemMethodNonexistingMasterjudge()
    {
    	$nonexistingMasterjudgeId = 9999;
    	
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->createProblem(
    			'UNIQUE_CODE', 
    			'Nonempty name', 
    			'body', 
    			'binary',
    			0,
    			$nonexistingMasterjudgeId);
    }
    
    public function testUpdateProblemMethodSuccess()
    {
    	$r = rand(1000000,9999999) . rand(1000000,9999999); // 14-digits random string
    	// create problem to update
    	$problem_code = 'UT' . $r;
    	$problem_name = 'UT' . $r;
    	self::$client->createProblem($problem_code, $problem_name);
    	
    	$new_problem_name = $problem_name . 'updated';
    	$new_problem_body = 'update';
    	$new_problem_type = 'maximize';
    	$new_problem_interactive = 1;
    	$new_problem_masterjudgeId = 1000;
    	self::$client->updateProblem(
    			$problem_code,
    			$new_problem_name,
    			$new_problem_body,
    			$new_problem_type,
    			$new_problem_interactive,
    			$new_problem_masterjudgeId);
		$p = self::$client->getProblem($problem_code);
    	$this->assertEquals($problem_code, $p['code'], 'Problem code');
    	$this->assertEquals($new_problem_name, $p['name'], 'Problem name');
    	$this->assertEquals($new_problem_body, $p['body'], 'Problem body');
    	$this->assertEquals($new_problem_type, $p['type'], 'Problem type');
    	$this->assertEquals($new_problem_interactive, $p['interactive'], 'Problem interactive');
    	$this->assertEquals($new_problem_masterjudgeId, $p['masterjudge']['id'], 'Problem masterjudgeId');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblem('NON_EXISTING_CODE', 'Nonexisting problem code');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemMethodNonexistingMasterjudge()
    {
    	$nonexistingMasterjudgeId = 9999;
    	
    	$this->expectException(SphereEngineResponseException::classname);
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
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(400);
    	self::$client->updateProblem('', 'Nonempty name');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemMethodEmptyName()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(400);
    	self::$client->updateProblem('TEST', '');
    }
    
    public function testUpdateProblemActiveTestcasesMethodSuccess()
    {
    	self::$client->updateProblemActiveTestcases('TEST', []);
    	$this->assertEquals("", self::$client->getProblem('TEST')['seq']); 
    	self::$client->updateProblemActiveTestcases('TEST', [0]);
    	$this->assertEquals("#0", self::$client->getProblem('TEST')['seq']);
    }
    
    public function testGetTestcasesMethodSuccess()
    {
    	$this->assertEquals(0, self::$client->getProblemTestcases('TEST')['testcases'][0]['number']);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemTestcasesMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcases('NON_EXISTING_CODE');
    }
    
    public function testGetProblemTestcaseMethodSuccess()
    {
    	$this->assertEquals(0, self::$client->getProblemTestcase('TEST', 0)['number']);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemTestcaseMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcase('NON_EXISTING_CODE', 0);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemTestcaseMethodNonexistingTestcase()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcase('TEST', 1);
    }
    
    public function testCreateProblemTestcaseMethodSuccess()
    {
    	$r = rand(1000000,9999999) . rand(1000000,9999999); // 14-digits random string
    	// create problem to create testcases
    	$problem_code = 'UT' . $r;
    	$problem_name = 'UT' . $r;
    	self::$client->createProblem($problem_code, $problem_name);
    	 
    	self::$client->createProblemTestcase($problem_code, "in0", "out0", 10, 2, 0);
    	$ptc = self::$client->getProblemTestcase($problem_code, 0);
    	$this->assertEquals(0, $ptc['number'], 'Testcase number');
    	$this->assertEquals(false, $ptc['active'], 'Testcase active');
    	$this->assertEquals(10, $ptc['limits']['time'], 'Testcase timelimit');
    	$this->assertEquals(3, $ptc['input']['size'], 'Testcase input size');
    	$this->assertEquals(4, $ptc['output']['size'], 'Testcase output size');
    	$this->assertEquals(2, $ptc['judge']['id'], 'Testcase judge');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemTestcaseMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->createProblemTestcase("NON_EXISTING_CODE", "in0", "out0", 10, 2, 1);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testCreateProblemTestcaseMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->createProblemTestcase("TEST", "in0", "out0", 10, $nonexistingJudge, 1);
    }

    public function testUpdateProblemTestcaseMethodSuccess()
    {
    	$r = rand(1000000,9999999) . rand(1000000,9999999); // 14-digits random string
    	// create problem and testcase to update the testcase
    	$problem_code = 'UT' . $r;
    	$problem_name = 'UT' . $r;
    	self::$client->createProblem($problem_code, $problem_name);
    	self::$client->createProblemTestcase($problem_code, "in0", "out0", 1, 1, 1);
    	
    	$new_testcase_input = "in0updated";
    	$new_testcase_output = "out0updated";
    	$new_testcase_timelimit = 10;
    	$new_testcase_judge = 2;
    	$new_testcase_active = 0;
    	
    	self::$client->updateProblemTestcase(
    			$problem_code,
    			0,
    			$new_testcase_input, 
    			$new_testcase_output, 
    			$new_testcase_timelimit, 
    			$new_testcase_judge, 
    			$new_testcase_active);
    	
    	$ptc = self::$client->getProblemTestcase($problem_code, 0);
    	$this->assertEquals(0, $ptc['number'], 'Testcase number');
    	$this->assertEquals(false, $ptc['active'], 'Testcase active');
    	$this->assertEquals($new_testcase_timelimit, $ptc['limits']['time'], 'Testcase timelimit');
    	$this->assertEquals(strlen($new_testcase_input), $ptc['input']['size'], 'Testcase input size');
    	$this->assertEquals(strlen($new_testcase_output), $ptc['output']['size'], 'Testcase output size');
    	$this->assertEquals($new_testcase_judge, $ptc['judge']['id'], 'Testcase judge');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemTestcaseMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblemTestcase("NON_EXISTING_CODE", 0, 'updated input');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemTestcaseMethodNonexistingTestcase()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblemTestcase("TEST", 1, 'updated input');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testUpdateProblemTestcaseMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblemTestcase("TEST", 0, 'updated input', 'updated output', 1, $nonexistingJudge, 0);
    }

    public function testGetProblemTestcaseFileMethodSuccess()
    {
    	$this->assertEquals("1", self::$client->getProblemTestcaseFile('TEST', 0, 'input')[0]);
    	$this->assertEquals("2", self::$client->getProblemTestcaseFile('TEST', 0, 'output')[3]);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemTestcaseFileMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcaseFile("NON_EXISTING_CODE", 0, 'input');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemTestcaseFileMethodNonexistingTestcase()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcaseFile("TEST", 1, 'input');
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetProblemTestcaseFileMethodNonexistingFile()
    {
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcaseFile("TEST", 0, 'fakefile');
    }
    
    public function testGetJudgesMethodSuccess()
    {
    	$this->assertEquals(10, self::$client->getJudges()['paging']['limit']);
    	$this->assertEquals(11, self::$client->getJudges(11)['paging']['limit']);
    }
    
    public function testGetJudgeMethodSuccess()
    {
    	$this->assertEquals(1, self::$client->getJudge(1)['id']);
    }
    
    /**
     * @requires PHPUnit 5
     */
    public function testGetJudgeMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	
    	$this->expectException(SphereEngineResponseException::classname);
    	$this->expectExceptionCode(404);
    	self::$client->getJudge($nonexistingJudge);
    }
    
	public function testCreateJudgeMethodSuccess()
	{
		$judge_source = 'source';
		$judge_compiler = 1;
		$judge_type = 'testcase';
		$judge_name = 'UT judge';
		
		$response = self::$client->createJudge(
						$judge_source,
						$judge_compiler,
						$judge_type,
						$judge_name
						);
		$judge_id = $response['id'];
		$this->assertTrue($judge_id > 0, 'Creation method should return new judge ID');
		$j = self::$client->getJudge($judge_id);
		$this->assertEquals($judge_source, $j['source'], 'Judge source');
		$this->assertEquals($judge_compiler, $j['compiler']['id'], 'Judge compiler ID');
		$this->assertEquals($judge_type, $j['type'], 'Judge type');
		$this->assertEquals($judge_name, $j['name'], 'Judge name');
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testCreateJudgeMethodEmptySource()
	{
		$this->expectException(SphereEngineResponseException::classname);
		$this->expectExceptionCode(400);
		self::$client->createJudge('', 1, 'testcase', '');
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testCreateJudgeMethodNonexistingCompiler()
	{
		$nonexistingCompiler = 9999;
		
		$this->expectException(SphereEngineResponseException::classname);
		$this->expectExceptionCode(404);
		self::$client->createJudge('nonempty source', $nonexistingCompiler, 'testcase', '');
	}
	
	public function testUpdateJudgeMethodSuccess()
	{
		$response = self::$client->createJudge('source', 1, 'testcase', 'UT judge');
		$judge_id = $response['id'];
		 
		$new_judge_source = 'updated source';
		$new_judge_compiler = 11;
		$new_judge_name = 'UT judge updated';
		
		self::$client->updateJudge(
				$judge_id,
				$new_judge_source,
				$new_judge_compiler,
				$new_judge_name);
		
		$j = self::$client->getJudge($judge_id);
		$this->assertEquals($new_judge_source, $j['source'], 'Judge source');
		$this->assertEquals($new_judge_compiler, $j['compiler']['id'], 'Judge compiler ID');
		$this->assertEquals($new_judge_name, $j['name'], 'Judge name');
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testUpdateJudgeMethodEmptySource()
	{
		$response = self::$client->createJudge('source', 1, 'testcase', 'UT judge');
		$judge_id = $response['id'];
		
		$this->expectException(SphereEngineResponseException::classname);
		$this->expectExceptionCode(400);
		self::$client->updateJudge($judge_id, '', 1, '');
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testUpdateJudgeMethodNonexistingJudge()
	{
		$nonexistingJudge = 99999999;
		
		$this->expectException(SphereEngineResponseException::classname);
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
	
		$this->expectException(SphereEngineResponseException::classname);
		$this->expectExceptionCode(404);
		self::$client->updateJudge($judge_id, 'nonempty source', $nonexistingCompiler, '');
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testUpdateJudgeMethodForeignJudge()
	{
		$this->expectException(SphereEngineResponseException::classname);
		$this->expectExceptionCode(403);
		self::$client->updateJudge(1, 'nonempty source', 1, '');
	}
	
	public function testGetSubmissionMethodSuccess()
	{
		$this->assertEquals(1, self::$client->getSubmission(1)['id']);
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testGetSubmissionMethodNonexistingSubmission()
	{
		$nonexistingSubmission = 9999999999;
		$this->expectException(SphereEngineResponseException::classname);
		$this->expectExceptionCode(404);
		self::$client->getSubmission($nonexistingSubmission);
	}
	
	public function testCreateSubmissionMethodSuccess()
	{
		$submission_problem_code = 'TEST';
		$submission_source = 'source';
		$submission_compiler = 1;
		
		$response = self::$client->createSubmission(
				$submission_problem_code,
				$submission_source,
				$submission_compiler);
		$submission_id = $response['id'];
		$this->assertTrue($submission_id > 0, 'Creation method should return new submission ID');
		$s = self::$client->getSubmission($submission_id);
		$this->assertEquals($submission_problem_code, $s['problem']['code'], 'Submission problem code');
		$this->assertEquals($submission_source, $s['source'], 'Submission source');
		$this->assertEquals($submission_compiler, $s['compiler']['id'], 'Submission compiler ID');
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testCreateSubmissionMethodEmptySource()
	{
		$this->expectException(SphereEngineResponseException::classname);
		$this->expectExceptionCode(400);
		self::$client->createSubmission('TEST', '', 1);
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testCreateSubmissionMethodNonexistingProblem()
	{
		$this->expectException(SphereEngineResponseException::classname);
		$this->expectExceptionCode(404);
		self::$client->createSubmission('NON_EXISTING_CODE', 'nonempty source', 1);
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testCreateSubmissionMethodNonexistingCompiler()
	{
		$nonexistingCompiler = 9999;
		
		$this->expectException(SphereEngineResponseException::classname);
		$this->expectExceptionCode(404);
		self::$client->createSubmission('TEST', 'nonempty source', $nonexistingCompiler);
	}
	
	/**
	 * @requires PHPUnit 5
	 */
	public function testCreateSubmissionMethodNonexistingUser()
	{
		$nonexistingUser = 9999999999;
	
		$this->expectException(SphereEngineResponseException::classname);
		$this->expectExceptionCode(404);
		self::$client->createSubmission('TEST', 'nonempty source', 1, $nonexistingUser);
	}
}
