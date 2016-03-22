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
        
        $this->expectException(SphereEngineResponseException::class);
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
    }
    
    public function testGetProblemMethodSuccess()
    {
    	$this->assertEquals('TEST', self::$client->getProblem('TEST')['code']);
    }
    
    public function testGetProblemMethodWrongCode()
    {
    	$this->expectException(SphereEngineResponseException::class);
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
    
    public function testCreateProblemMethodCodeTaken()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(400);
    	self::$client->createProblem('TEST', 'Taken problem code');
    }
    
    public function testCreateProblemMethodCodeEmpty()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(400);
    	self::$client->createProblem('', 'Empty problem code');
    }
    
    public function testCreateProblemMethodCodeInvalid()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(400);
    	self::$client->createProblem('!@#$%^', 'Invalid problem code');
    }
    
    public function testCreateProblemMethodEmptyName()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(400);
    	self::$client->createProblem('UNIQUE_CODE', '');
    }
    
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
    
    public function testUpdateProblemMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblem('NON_EXISTING_CODE', 'Nonexisting problem code');
    }
    
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
    
    public function testUpdateProblemMethodEmptyCode()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(400);
    	self::$client->updateProblem('', 'Nonempty name');
    }
    
    public function testUpdateProblemMethodEmptyName()
    {
    	$this->expectException(SphereEngineResponseException::class);
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
    
    public function testGetProblemTestcasesMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcases('NON_EXISTING_CODE');
    }
    
    public function testGetProblemTestcaseMethodSuccess()
    {
    	$this->assertEquals(0, self::$client->getProblemTestcase('TEST', 0)['number']);
    }
    
    public function testGetProblemTestcaseMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->getProblemTestcase('NON_EXISTING_CODE', 0);
    }
    
    public function testGetProblemTestcaseMethodNonexistingTestcase()
    {
    	$this->expectException(SphereEngineResponseException::class);
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
    
    public function testCreateProblemTestcaseMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->createProblemTestcase("NON_EXISTING_CODE", "in0", "out0", 10, 2, 1);
    }
    
    public function testCreateProblemTestcaseMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	
    	$this->expectException(SphereEngineResponseException::class);
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
    
    public function testUpdateProblemTestcaseMethodNonexistingProblem()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblemTestcase("NON_EXISTING_CODE", 0, 'updated input');
    }
    
    public function testUpdateProblemTestcaseMethodNonexistingTestcase()
    {
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblemTestcase("TEST", 1, 'updated input');
    }
    
    public function testUpdateProblemTestcaseMethodNonexistingJudge()
    {
    	$nonexistingJudge = 9999;
    	
    	$this->expectException(SphereEngineResponseException::class);
    	$this->expectExceptionCode(404);
    	self::$client->updateProblemTestcase("TEST", 0, 'updated input', 'updated output', 1, $nonexistingJudge, 0);
    }

    public function testGetProblemTestcaseFileMethodSuccess()
    {
    	$this->assertEquals("1", self::$client->getProblemTestcaseFile('TEST', 0, 'input')[0]);
    	$this->assertEquals("2", self::$client->getProblemTestcaseFile('TEST', 0, 'output')[3]);
    }
}
