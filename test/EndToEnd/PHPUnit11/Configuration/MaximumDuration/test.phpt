--TEST--
With custom configuration setting the "maximum-duration" parameter in the XML configuration file
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/PHPUnit11/Configuration/MaximumDuration/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
%a

............                                                      12 / 12 (100%)

Detected 11 tests where the duration exceeded the global maximum duration (00:00.100).

 # Duration  Test
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
 1 00:01.2%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #10 (1200)
 2 00:01.1%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #9 (1100)
 3 00:01.0%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #8 (1000)
 4 00:00.9%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #7 (900)
 5 00:00.8%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #6 (800)
 6 00:00.7%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #5 (700)
 7 00:00.6%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #4 (600)
 8 00:00.5%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #3 (500)
 9 00:00.4%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #2 (400)
10 00:00.3%s Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\PHPUnit11\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

There is 1 additional slow test that is not listed here.

%a
