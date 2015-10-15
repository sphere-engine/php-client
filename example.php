<?php

require_once('./autoload.php');

$se_compilers = new SphereEngine\Api("access_token", "v3", "endpoint");
$compilersClient = $se_compilers->getCompilersClient();
$se_problems = new SphereEngine\Api("access_token", "v3", "endpoint");
$problemsClient = $se_problems->getProblemsClient();


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
    // SUBMISSIONS
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
    // SUBMISSIONS
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
    // JUDGES
    'judges all' => function ($client) {
        return isset($client->judges->all()['items']);
    },
    'judges get' => function ($client) {
        return $client->judges->get(1)['id'] == 1;
    },
    'judges get nonexisting judge' => function ($client) {
        try {
            $r = $client->judges->get(10000);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'judges create success' => function ($client) {
        return isset($client->judges->create("// judge source", 1, "testcase", "UnitTest Judge")['id']);
    },
    'judges create nonexisting compiler' => function ($client) {
        try {
            $r = $client->judges->create("// judge source", 10000, "testcase", "UnitTest Judge");
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'judges create empty source' => function ($client) {
        try {
            $r = $client->judges->create("", 1, "testcase", "UnitTest Judge");
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 400) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'judges update success' => function ($client) {
        return $client->judges->update(1042, "// updated source", 11, "UnitTest Judge updated");
    },
    'judges update nonexisting judge' => function ($client) {
        try {
            $r = $client->judges->update(9999999999, "// updated source", 11, "UnitTest Judge updated");
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'judges update nonexisting compiler' => function ($client) {
        try {
            $r = $client->judges->update(1042, "// updated source", 10000, "UnitTest Judge updated");
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'judges update empty source' => function ($client) {
        try {
            $r = $client->judges->update(1042, "", 1, "UnitTest Judge updated");
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 400) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    // PROBLEMS
    'problems all' => function ($client) {
        return isset($client->problems->all()['items']);
    },
    'problems get' => function ($client) {
        return $client->problems->get('UT1952')['code'] == 'UT1952';
    },
    'problems get nonexisting problem' => function ($client) {
        try {
            $r = $client->problems->get('NONEXISTING');
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems create success' => function ($client) {
        $r = rand(1000, 9999);
        return isset($client->problems->create('UT' . $r, 'Unit Test' . $r)['code']);
    },
    'problems create not unique code' => function ($client) {
        try {
            $r = $client->problems->create('TEST', 'Test');
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 400) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems create code invalid' => function ($client) {
        try {
            $r = $client->problems->create('^$%!@#$', 'Test');
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 400) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems create empty code' => function ($client) {
        try {
            $r = $client->problems->create('', 'Test');
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 400) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems create empty name' => function ($client) {
        try {
            $r = rand(1000, 9999);
            $r = $client->problems->create('UT' . $r, '');
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 400) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems create nonexisting masterjudge' => function ($client) {
        try {
            $r = rand(1000, 9999);
            $r = $client->problems->create('UT' . $r, 'Unit Test' . $r, "", "binary", 0, 10000);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems update success' => function ($client) {
        return $client->problems->update('UT1952', 'Update name');
    },
    'problems update nonexisting problem' => function ($client) {
        try {
            $r = $client->problems->update('NONEXISTING', 'Updated name');
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems update nonexisting masterjudge' => function ($client) {
        try {
            $r = $client->problems->update('UT1952', 'Updated name', '', 'binary', 0, 10000);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems update nonexisting masterjudge' => function ($client) {
        try {
            $r = $client->problems->update('UT1952', 'Updated name', '', 'binary', 0, 10000);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems update empty code' => function ($client) {
        try {
            $r = $client->problems->update('', 'Updated name');
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 400) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems update empty name' => function ($client) {
        try {
            $r = $client->problems->update('UT1952', '');
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 400) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    // TESTCASES
    'problems testcases all' => function ($client) {
        return isset($client->problems->allTestcases('UT1952')['testcases']);
    },
    'problems testcases get' => function ($client) {
        return $client->problems->getTestcase('UT1952', 0)['number'] == 0;
    },
    'problems testcases get nonexisting problem' => function ($client) {
        try {
            $r = $client->problems->getTestcase('NONEXISTING', 0);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems testcases get nonexisting testcase' => function ($client) {
        try {
            $r = $client->problems->getTestcase('UT1952', 100000);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems testcases create success' => function ($client) {
        return isset($client->problems->createTestcase('UT1952')['number']);
    },
    'problems testcases create nonexisting problem' => function ($client) {
        try {
            $r = $client->problems->createTestcase('NONEXISTING');
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems testcases create nonexisting judge' => function ($client) {
        try {
            $r = $client->problems->createTestcase('UT1952', '', '', 1, 100000);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems testcases update success' => function ($client) {
        return $client->problems->updateTestcase('UT1952', 0, 'updated input', 'updated output', 2);
    },
    'problems testcases update nonexisting problem' => function ($client) {
        try {
            $r = $client->problems->updateTestcase('NONEXISTING', 0, 'updated input', 'updated output', 2);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems testcases update nonexisting testcase' => function ($client) {
        try {
            $r = $client->problems->updateTestcase('UT1952', 100000, 'updated input', 'updated output', 2);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems testcases update nonexisting judge' => function ($client) {
        try {
            $r = $client->problems->updateTestcase('UT1952', 0, 'updated input', 'updated output', 2, 10000);
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems testcases getfile' => function ($client) {
        return $client->problems->getTestcaseFile('UT1952', 0, 'input') == 'updated input';
    },
    'problems testcases getfile nonexisting problem' => function ($client) {
        try {
            $r = $client->problems->getTestcaseFile('NONEXISTING', 0, 'input');
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems testcases getfile nonexisting testcase' => function ($client) {
        try {
            $r = $client->problems->getTestcaseFile('UT1952', 100000, 'input');
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
    'problems testcases getfile nonexisting file' => function ($client) {
        try {
            $r = $client->problems->getTestcaseFile('UT1952', 0, 'nonexisting');
            return 0;
        } catch (SphereEngine\SphereEngineResponseException $e) {
            if ($e->getCode() == 404) {
                return 1;
            } else {
                return 0;
            }
        }
    },
];

echo "<h2>Compilers</h2>";


foreach ($compilers_unit_tests as $name => $t) {
    $parts = explode(' ', $name);
    $parts[0] = '<strong>' . $parts[0] . '</strong>';
    echo implode(' ', $parts) . (($t($compilersClient)) ? " <span style=\"color: green\">succeed</span>" : " <span style=\"color: red\">failed</span>") . "<br />";  
}


echo "<h2>Problems</h2>";

foreach ($problems_unit_tests as $name => $t) {
    $parts = explode(' ', $name);
    $parts[0] = '<strong>' . $parts[0] . '</strong>';
    echo implode(' ', $parts) . (($t($problemsClient)) ? " <span style=\"color: green\">succeed</span>" : " <span style=\"color: red\">failed</span>") . "<br />";
}





