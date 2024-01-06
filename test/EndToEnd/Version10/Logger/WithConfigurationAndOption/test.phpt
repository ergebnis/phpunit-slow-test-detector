--TEST--
Logger with configuration and option
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version10/Logger/WithConfigurationAndOption/phpunit.xml';
$_SERVER['argv'][] = '--no-output';
$_SERVER['argv'][] = '--log-junit';
$_SERVER['argv'][] = 'php://memory';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
<?xml version="1.0" encoding="UTF-8"?>
<testsuites><testsuite name="Slow Tests" tests="6" failures="6" errors="0"><testcase name="Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDuration" file="%stest/EndToEnd/Version10/Logger/WithConfigurationAndOption/SleeperTest.php" line="22"><failure type="slow_test"><![CDATA[The actual duration of 0.5%s exceeds the maximum allowed duration of 0.500.]]></failure></testcase><testcase name="Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#0" file="%stest/EndToEnd/Version10/Logger/WithConfigurationAndOption/SleeperTest.php" line="34"><failure type="slow_test"><![CDATA[The actual duration of 0.6%s exceeds the maximum allowed duration of 0.500.]]></failure></testcase><testcase name="Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#1" file="%stest/EndToEnd/Version10/Logger/WithConfigurationAndOption/SleeperTest.php" line="34"><failure type="slow_test"><![CDATA[The actual duration of 0.7%s exceeds the maximum allowed duration of 0.500.]]></failure></testcase><testcase name="Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#2" file="%stest/EndToEnd/Version10/Logger/WithConfigurationAndOption/SleeperTest.php" line="34"><failure type="slow_test"><![CDATA[The actual duration of 0.8%s exceeds the maximum allowed duration of 0.500.]]></failure></testcase><testcase name="Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#3" file="%stest/EndToEnd/Version10/Logger/WithConfigurationAndOption/SleeperTest.php" line="34"><failure type="slow_test"><![CDATA[The actual duration of 0.9%s exceeds the maximum allowed duration of 0.500.]]></failure></testcase><testcase name="Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version10\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider#4" file="%stest/EndToEnd/Version10/Logger/WithConfigurationAndOption/SleeperTest.php" line="34"><failure type="slow_test"><![CDATA[The actual duration of 1.0%s exceeds the maximum allowed duration of 0.500.]]></failure></testcase></testsuite></testsuites>
