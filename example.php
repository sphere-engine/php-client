<?php

require_once('./autoload.php');

$se_compilers = new SphereEngine\Api("access_token", "v3", "endpoint");
$compilersClient = $se_compilers->getCompilersClient();
$se_problems = new SphereEngine\Api("access_token", "v3", "endpoint");
$problemsClient = $se_problems->getProblemsClient();


// try {
//     $r = $problemsClient->submissions->create('UT1952', '', 11);
// } catch (SphereEngine\SphereEngineResponseException $e) {
//     var_dump($e);
// }

var_dump($problemsClient->judges->all());

$compilers_unit_tests = [
    'wrong access token' => function ($not_used) {
        $client = (new SphereEngine\Api("fake_access_token", "v3", "endpoint"))->getCompilersClient();
        try {
            $client->test();
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 401) {
                return 1;
            }
            return 0;
        }
    },
    'test' => function ($client) {
        return $client->test()['pi'] == 3.14;
    },
    'compilers' => function ($client) {
        return $client->compilers()[11][0] == 'C';
    },
    'submissions get existing' => function ($client) {
        return $client->submissions->get(38436074)['result'] == 15;
    },
    'submissions get non existing' => function ($client) {
        try {
            $r = $client->submissions->get(9999999999);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            }
            return 0;
        }
    },
    'submissions get access denied' => function ($client) {
        try {
            $r = $client->submissions->get(1);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 401) {
                return 1;
            }
            return 0;
        }
    },
    'submissions create success' => function ($client) {
        $res = $client->submissions->create('int main() {}', 11, 'Robsontest');
        return isset($res['id']);
    },
];

$problems_unit_tests = [
    'wrong access token' => function ($not_used) {
        $client = (new SphereEngine\Api("fake_access_token", "v3", "endpoint"))->getProblemsClient();
        try {
            $client->test();
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 401) {
                return 1;
            }
            return 0;
        }
    },
    'test' => function ($client) {
        return isset($client->test()['message']);
    },
    'compilers' => function ($client) {
        return isset($client->compilers()['items']);
    },
    'submissions get' => function ($client) {
        return $client->submissions->get(23355)['id'] == 23355;
    },
    'submissions get nonexisting submission' => function ($client) {
        try {
            $r = $client->submissions->get(9999999999);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'submissions create success' => function ($client) {
        return isset($client->submissions->create('UT1952', '//submission source', 11)['id']);
    },
    'submissions create nonexisting problem' => function ($client) {
        try {
            $r = $client->submissions->create('NONEXISTING', '//submission source', 11);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'submissions create nonexisting compiler' => function ($client) {
        try {
            $r = $client->submissions->create('UT1952', '//submission source', 2015);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'submissions create empty source' => function ($client) {
        try {
            $r = $client->submissions->create('UT1952', '', 11);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 400) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'submissions get' => function ($client) {
        return $client->submissions->get(23355)['id'] == 23355;
    },
];

/*
$compilers_unit_tests = [
    'test 200' => function ($client) {
            $res = $client->test();
            return $res['code'] == 200;
        },
    'compilers 200' => function ($client) {
            $res = $client->test();
            return $res['code'] == 200;
        },
    'submissions get 200' => function ($client) {
            $res = $client->submissions->get(38436074);
            return $res['code'] == 200;
        },
    'submissions create 200' => function ($client) {
            $res = $client->submissions->create('int main() {}', 11, 'Robsontest');
            return $res['code'] == 200;
        }
    ];

$problems_unit_tests = [
    'test 200' => function ($client) {
        $res = $client->test();
        return $res['code'] == 200;
    },
    'compilers 200' => function ($client) {
        $res = $client->test();
        return $res['code'] == 200;
    },
    // SUBMISSIONS
    'submissions get 200' => function ($client) {
        $res = $client->submissions->get(23355);
        return $res['code'] == 200;
    },
    'submissions create 201' => function ($client) {
        $res = $client->submissions->create('UT1952', '//submission source', 11);
        return $res['code'] == 201;
    },
    // JUDGES
    'judges all 200' => function ($client) {
        $res = $client->judges->all();
        return $res['code'] == 200;
    },
    'judges get 200' => function ($client) {
        $res = $client->judges->get(1);
        return $res['code'] == 200;
    },
    'judges create 201' => function ($client) {
        $res = $client->judges->create("// judge source", 1, "testcase", "UnitTest Judge");
        return $res['code'] == 201;
    },
    'judges update 200' => function ($client) {
        $res = $client->judges->update(1042, "// updated source", 11, "UnitTest Judge updated");
        return $res['code'] == 200;
    },
    // PROBLEMS
    'problems all 200' => function ($client) {
        $res = $client->problems->all();
        return $res['code'] == 200;
    },
    'problems get 200' => function ($client) {
        $res = $client->problems->get('UT1952');
        return $res['code'] == 200;
    },
    'problems create 201' => function ($client) {
        $r = rand(1000, 9999);
        $res = $client->problems->create('UT' . $r, 'Unit Test' . $r);
        return $res['code'] == 201;
    },
    'problems update 200' => function ($client) {
        $res = $client->problems->update('UT1952', 'Updated name');
        return $res['code'] == 200;
    },
    // TESTCASES
    'testcases all 200' => function ($client) {
        $res = $client->problems->allTestcases('UT1952');
        return $res['code'] == 200;
    },
    'testcases get 200' => function ($client) {
        $res = $client->problems->getTestcase('UT1952', 0);
        return $res['code'] == 200;
    },
    'testcases create 201' => function ($client) {
        $res = $client->problems->createTestcase('UT1952');
        return $res['code'] == 201;
    },
    'testcases update 200' => function ($client) {
        $res = $client->problems->updateTestcase('UT1952', 0, "input", "output", 2);
        return $res['code'] == 200;
    },
    'testcases getfile 200' => function ($client) {
        $res = $client->problems->getTestcaseFile('UT1952', 0, "input");
        return $res['code'] == 200;
    },
    ];
*/
echo "<h2>Compilers</h2>";


foreach ($compilers_unit_tests as $name => $t) {
    echo "<strong>" . $name . "</strong> " . (($t($compilersClient)) ? "<span style=\"color: green\">succeed</span>" : "<span style=\"color: red\">failed</span>") . "<br />";  
}


echo "<h2>Problems</h2>";

foreach ($problems_unit_tests as $name => $t) {
    echo "<strong>" . $name . "</strong> " . (($t($problemsClient)) ? "<span style=\"color: green\">succeed</span>" : "<span style=\"color: red\">failed</span>") . "<br />";
}





