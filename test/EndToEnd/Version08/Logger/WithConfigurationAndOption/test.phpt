--TEST--
Logger with configuration and option
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version08/Logger/WithConfigurationAndOption/phpunit.xml';
$_SERVER['argv'][] = '--log-junit';
$_SERVER['argv'][] = 'php://memory';

// https://github.com/php/php-src/issues/11104
if (!\defined('STDOUT')) {
    \define('STDOUT', \fopen('php://stdout', 'wb'));
    \define('STDERR', \fopen('php://stderr', 'wb'));
}

require_once __DIR__ . '/../../../../../vendor/autoload.php';

PHPUnit\TextUI\Command::main();
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version08/Logger/WithConfigurationAndOption/phpunit.xml

......                                                              6 / 6 (100%)<?xml version="1.0" encoding="UTF-8"?>
<testsuites><testsuite name="Slow Tests" tests="6" failures="6" errors="0"><testcase name="Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDuration" file="%stest/EndToEnd/Version08/Logger/WithConfigurationAndOption/SleeperTest.php" line="24"><failure type="slow_test"><![CDATA[The actual duration of 0.5%s exceeds the maximum allowed duration of 0.500.]]></failure></testcase><testcase name="Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #0 (600)" file="%stest/EndToEnd/Version08/Logger/WithConfigurationAndOption/SleeperTest.php" line="38"><failure type="slow_test"><![CDATA[The actual duration of 0.6%s exceeds the maximum allowed duration of 0.500.]]></failure></testcase><testcase name="Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1 (700)" file="%stest/EndToEnd/Version08/Logger/WithConfigurationAndOption/SleeperTest.php" line="38"><failure type="slow_test"><![CDATA[The actual duration of 0.7%s exceeds the maximum allowed duration of 0.500.]]></failure></testcase><testcase name="Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (800)" file="%stest/EndToEnd/Version08/Logger/WithConfigurationAndOption/SleeperTest.php" line="38"><failure type="slow_test"><![CDATA[The actual duration of 0.8%s exceeds the maximum allowed duration of 0.500.]]></failure></testcase><testcase name="Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3 (900)" file="%stest/EndToEnd/Version08/Logger/WithConfigurationAndOption/SleeperTest.php" line="38"><failure type="slow_test"><![CDATA[The actual duration of 0.9%s exceeds the maximum allowed duration of 0.500.]]></failure></testcase><testcase name="Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #4 (1000)" file="%stest/EndToEnd/Version08/Logger/WithConfigurationAndOption/SleeperTest.php" line="38"><failure type="slow_test"><![CDATA[The actual duration of 1.0%s exceeds the maximum allowed duration of 0.500.]]></failure></testcase></testsuite></testsuites>


Detected 6 tests where the duration exceeded the maximum duration.

1. 1.0%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #4 (1000)
2. 0.9%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #3 (900)
3. 0.8%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #2 (800)
4. 0.7%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #1 (700)
5. 0.6%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDurationWithDataProvider with data set #0 (600)
6. 0.5%s (0.500) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version08\Logger\WithConfigurationAndOption\SleeperTest::testSleeperSleepsLongerThanDefaultMaximumDuration

Time: %s, Memory: %s

OK (6 tests, 6 assertions)
