<?php

require_once('./autoload.php');

$se1 = new SphereEngine\Api("access token");
$se2 = new SphereEngine\Api("access token");

$problems_client = $se1->getProblemsClient("v3", "endpointName");
$compilers_client = $se2->getCompilersClient("v3", "endpointName");


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

echo "<h2>Compilers</h2>";

foreach ($compilers_unit_tests as $name => $t) {
    echo "<strong>" . $name . "</strong> " . (($t($compilers_client)) ? "<span style=\"color: green\">succeed</span>" : "<span style=\"color: red\">failed</span>") . "<br />";  
}


echo "<h2>Problems</h2>";

foreach ($problems_unit_tests as $name => $t) {
    echo "<strong>" . $name . "</strong> " . (($t($problems_client)) ? "<span style=\"color: green\">succeed</span>" : "<span style=\"color: red\">failed</span>") . "<br />";
}


