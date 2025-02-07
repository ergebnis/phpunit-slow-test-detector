--TEST--
With custom configuration setting the "maximum-duration" parameter in the XML configuration file
--FILE--
<?php

declare(strict_types=1);

use PHPUnit\TextUI;

$_SERVER['argv'][] = '--configuration=test/EndToEnd/Version12/Configuration/MaximumDuration/phpunit.xml';

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$application = new TextUI\Application();

$application->run($_SERVER['argv']);
--EXPECTF--
PHPUnit %s

Runtime: %s
Configuration: %s/EndToEnd/Version12/Configuration/MaximumDuration/phpunit.xml

............                                                      12 / 12 (100%)

Detected 11 tests where the duration exceeded the maximum duration.

 1. 1.2%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #10 (1200)
 2. 1.1%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #9 (1100)
 3. 1.0%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #8 (1000)
 4. 0.9%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #7 (900)
 5. 0.8%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #6 (800)
 6. 0.7%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #5 (700)
 7. 0.6%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #4 (600)
 8. 0.5%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #3 (500)
 9. 0.4%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #2 (400)
10. 0.3%s (0.100) Ergebnis\PHPUnit\SlowTestDetector\Test\EndToEnd\Version12\Configuration\MaximumDuration\SleeperTest::testSleeperSleepsLongerThanMaximumDurationFromXmlConfigurationWithDataProvider with data set #1 (300)

There is 1 additional slow test that is not listed here.

Time: %s, Memory: %s

OK (12 tests, 12 assertions)
